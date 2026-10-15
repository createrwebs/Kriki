export async function formsRequest(path, options = {}, transport = fetch) {
    const response = await transport(path, {
        ...options,
        credentials: 'same-origin',
        headers: {
            Accept: 'application/json', 'Content-Type': 'application/json',
            'X-CSRF-TOKEN': typeof document === 'undefined' ? '' : document.querySelector('meta[name="csrf-token"]')?.content || '',
            ...options.headers,
        },
    })
    const data = await response.json()
    if (! response.ok) {
        const error = new Error(data.message || `Request failed (${response.status})`)
        error.status = response.status
        error.payload = data
        throw error
    }
    return data
}
const root = (project) => `/lab/${encodeURIComponent(project)}/forms`
const formPath = (project, form) => `${root(project)}/${encodeURIComponent(form)}`
export const listForms = (project) => formsRequest(root(project))
export const readForm = (project, form) => formsRequest(formPath(project, form))
export const createForm = (project, title) => formsRequest(root(project), { method: 'POST', body: JSON.stringify({ title }) })
export const saveFormVersion = (project, form, expected_version, definition) => formsRequest(`${formPath(project, form)}/versions`, { method: 'POST', body: JSON.stringify({ expected_version, definition }) })
export const setFormState = (project, form, expected_version, state) => formsRequest(`${formPath(project, form)}/state`, { method: 'PATCH', body: JSON.stringify({ expected_version, state }) })
export const listFormSubmissions = (project, form, filters = {}) => formsRequest(`${formPath(project, form)}/submissions?${new URLSearchParams(Object.entries(filters).filter(([, value]) => value !== ''))}`)
export const getFormSubmission = (project, form, submission) => formsRequest(`${formPath(project, form)}/submissions/${encodeURIComponent(submission)}`)
export const setSubmissionStatus = (project, form, submission, expected_status, status) => formsRequest(`${formPath(project, form)}/submissions/${encodeURIComponent(submission)}/status`, { method: 'PATCH', body: JSON.stringify({ expected_status, status }) })
export const embedForm = (project, form) => formsRequest(`${formPath(project, form)}/embed`, { method: 'POST' })
export const proposeFormChange = (project, form, expected_version, operations) => formsRequest(`${formPath(project, form)}/proposals`, { method: 'POST', body: JSON.stringify({ expected_version, operations }) })
