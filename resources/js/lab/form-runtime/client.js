export async function loadDefinition(apiOrigin, formUuid) {
    const response = await fetch(`${new URL(apiOrigin).origin}/api/lab-forms/${encodeURIComponent(formUuid)}`, { credentials: 'omit', headers: { Accept: 'application/json' } })
    const payload = await response.json()
    if (! response.ok) throw new Error(payload.message || 'โหลดฟอร์มไม่ได้ กรุณาลองใหม่')
    return payload
}

export function prepareBookingPayload(values, fields, additional) {
    const answers = {}
    for (const field of fields.filter(item => item.id.startsWith('field_') && ! item.archived)) {
        const value = additional[field.id]
        if (value === '' || value === undefined || value === null) continue
        answers[field.id] = field.type === 'number' ? Number(value) : value
    }
    return {
        core: {
            customer_name: values.customer_name || '', phone: values.phone || '', notes: values.notes || '',
            requested_at: values.requested_at ? new Date(`${values.requested_at}+07:00`).toISOString() : '',
            party_size: Number(values.party_size),
        },
        answers,
    }
}

export function createBookingSender(apiOrigin, formUuid, transport = fetch) {
    let previousPayload = null
    let key = null
    return async (payload) => {
        const serialized = JSON.stringify(payload)
        if (serialized !== previousPayload) { previousPayload = serialized; key = crypto.randomUUID() }
        const response = await transport(`${new URL(apiOrigin).origin}/api/lab-forms/${encodeURIComponent(formUuid)}/submissions`, {
            method: 'POST', credentials: 'omit', headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({ ...payload, idempotency_key: key }),
        })
        const data = await response.json()
        if (! response.ok) {
            const error = new Error(data.message || 'ส่งคำขอไม่สำเร็จ กรุณาลองใหม่')
            error.status = response.status
            error.errors = data.errors || {}
            throw error
        }
        if (typeof data.reference !== 'string' || ! data.reference || ! ['pending', 'confirmed', 'cancelled'].includes(data.status)) {
            throw new Error('ยังไม่ได้รับเลขอ้างอิงจากระบบ กรุณาลองส่งข้อมูลเดิมอีกครั้ง')
        }
        return data
    }
}
