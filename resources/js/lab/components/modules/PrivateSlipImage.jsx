import { useEffect, useRef, useState } from 'react'
import { paymentImage } from '../../lib/modules.js'

export default function PrivateSlipImage({ projectUuid, moduleUuid, attempt }) {
    const [image, setImage] = useState(null)
    const [loading, setLoading] = useState(false)
    const [error, setError] = useState('')
    const request = useRef(null)
    useEffect(() => {
        setImage(null); setError(''); setLoading(false)
        return () => request.current?.abort()
    }, [projectUuid, moduleUuid, attempt.uuid])
    useEffect(() => {
        if (!image) return undefined
        const timer = setTimeout(() => { setImage(null); setError('ลิงก์หมดอายุ กดดูสลิปอีกครั้งเพื่อตรวจสิทธิ์ใหม่') }, Math.max(0, Math.min(300_000, Date.parse(image.expires_at) - Date.now())))
        return () => clearTimeout(timer)
    }, [image])
    async function view() {
        if (image) { setImage(null); return }
        request.current?.abort()
        const controller = new AbortController()
        request.current = controller
        setLoading(true); setError('')
        try {
            const result = await paymentImage(projectUuid, moduleUuid, attempt.uuid, controller.signal)
            if (!controller.signal.aborted) setImage(result)
        } catch (failure) { if (!controller.signal.aborted) setError(failure.message) }
        finally { if (!controller.signal.aborted) setLoading(false) }
    }
    return <div className="mt-2">
        <button type="button" onClick={view} disabled={loading}>{loading ? 'กำลังตรวจสิทธิ์…' : image ? 'ปิดภาพสลิป' : attempt.image_available ? 'ดูสลิปส่วนตัว (5 นาที)' : 'ดูหลักฐานรุ่นเดิม (5 นาที)'}</button>
        {error ? <p role="alert">{error}</p> : null}
        {image ? <img src={image.url} referrerPolicy="no-referrer" onError={() => { setImage(null); setError('ไม่สามารถเปิดภาพหลักฐานได้ กรุณาตรวจสิทธิ์และลองใหม่') }} alt={`สลิปโอนเงินของ ${attempt.payer_name}`} className="max-h-80 rounded border border-krikkit-line object-contain" /> : null}
    </div>
}
