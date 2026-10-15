const core = {
    customer_name: ['text', true], phone: ['text', true], requested_at: ['datetime', true],
    party_size: ['integer', true], notes: ['textarea', false],
}
const customTypes = new Set(['text', 'textarea', 'number', 'select', 'checkbox'])
const fieldIdPattern = /^field_[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12}$/i
const plainLabel = (value) => typeof value === 'string' && value.trim().length > 0
    && [...value].length <= 120 && ! /<[^>]*>|javascript\s*:/iu.test(value)
function requireCondition(condition, message) {
    if (! condition) throw new Error(message)
}

export function validateFormDefinition(definition, previous = null) {
    requireCondition(definition && Object.keys(definition).every(key => ['template', 'fields'].includes(key)), 'Unknown definition properties')
    requireCondition(definition.template === 'table_booking' && Array.isArray(definition.fields)
        && definition.fields.length >= 5 && definition.fields.length <= 100, 'Invalid booking definition')
    const byId = new Map()
    let activeCustom = 0
    for (const field of definition.fields) {
        requireCondition(field && Object.keys(field).every(key => ['id', 'label', 'type', 'required', 'archived', 'options'].includes(key)), 'Unknown field properties')
        requireCondition(typeof field.id === 'string' && ! byId.has(field.id) && plainLabel(field.label)
            && typeof field.required === 'boolean' && typeof field.archived === 'boolean', 'Invalid or duplicate field')
        byId.set(field.id, field)
        if (Object.hasOwn(core, field.id)) {
            requireCondition(field.type === core[field.id][0] && field.required === core[field.id][1] && ! field.archived, 'Core booking fields are protected')
        } else {
            requireCondition(fieldIdPattern.test(field.id) && customTypes.has(field.type), 'Invalid custom field')
            if (! field.archived) activeCustom++
        }
        if (field.type === 'select') {
            requireCondition(Array.isArray(field.options) && field.options.length >= 1 && field.options.length <= 30, 'Select needs 1–30 options')
            const ids = new Set()
            for (const option of field.options) {
                requireCondition(option && Object.keys(option).every(key => ['id', 'label'].includes(key))
                    && typeof option.id === 'string' && /^[a-zA-Z0-9_-]{1,64}$/.test(option.id)
                    && ! ids.has(option.id) && plainLabel(option.label), 'Invalid select option')
                ids.add(option.id)
            }
        } else requireCondition(! Object.hasOwn(field, 'options'), 'Options require select type')
    }
    requireCondition(Object.keys(core).every(id => byId.has(id)) && activeCustom <= 20, 'Core missing or more than 20 custom fields')
    for (const field of previous?.fields || []) {
        requireCondition(byId.has(field.id) && byId.get(field.id).type === field.type, 'Archive old fields instead of removing/changing types')
    }
    return definition
}

export function applyFieldOperations(definition, operations) {
    requireCondition(Array.isArray(operations) && operations.length <= 100, 'Invalid operations')
    let next = structuredClone(validateFormDefinition(definition))
    for (const operation of operations) {
        requireCondition(operation && ['add', 'label', 'required', 'reorder', 'archive'].includes(operation.op), 'Unsupported operation')
        if (operation.op === 'add') {
            next.fields = [...next.fields, structuredClone(operation.field)]
        } else if (operation.op === 'reorder') {
            const ids = operation.ids
            requireCondition(Array.isArray(ids) && ids.length === next.fields.length
                && new Set(ids).size === ids.length && ids.every(id => next.fields.some(field => field.id === id)), 'Reorder must contain every field exactly once')
            next.fields = ids.map(id => next.fields.find(field => field.id === id))
        } else {
            requireCondition(next.fields.some(field => field.id === operation.id), 'Unknown field ID')
            next.fields = next.fields.map(field => {
                if (field.id !== operation.id) return field
                if (operation.op === 'label') return { ...field, label: operation.label }
                if (operation.op === 'required') return { ...field, required: operation.required }
                return { ...field, archived: true }
            })
        }
        validateFormDefinition(next, definition)
    }
    return next
}
