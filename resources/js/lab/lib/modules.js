import { formsRequest } from './forms.js'

const root = project => `/lab/${encodeURIComponent(project)}/modules`
export const listModules = project => formsRequest(root(project))
export const selectModule = (project, key, enabled, expected_revision) => formsRequest(`${root(project)}/capabilities/${encodeURIComponent(key)}`, {
    method: 'PUT', body: JSON.stringify({ enabled, expected_revision }),
})
export const prepareModule = (project, payload) => formsRequest(root(project), { method: 'POST', body: JSON.stringify(payload) })
export const setModuleState = (project, module, state, expected_revision) => formsRequest(`${root(project)}/${encodeURIComponent(module)}/state`, {
    method: 'PATCH', body: JSON.stringify({ state, expected_revision }),
})
export const proposeModuleSchema = (project, module, expected_revision) => formsRequest(`${root(project)}/${encodeURIComponent(module)}/proposals`, {
    method: 'POST', body: JSON.stringify({ expected_revision }),
})
export const applyModuleProposal = (project, proposal) => formsRequest(`${root(project)}/proposals/${encodeURIComponent(proposal.uuid)}/apply`, {
    method: 'POST', body: JSON.stringify({ impact_hash: proposal.impact_hash, expected_revision: proposal.expected_revision }),
})
export const cancelModuleProposal = (project, proposal) => formsRequest(`${root(project)}/proposals/${encodeURIComponent(proposal.uuid)}/cancel`, { method: 'POST', body: '{}' })
export const listContacts = (project, module) => formsRequest(`${root(project)}/${encodeURIComponent(module)}/contacts`)
export const transitionContact = (project, module, message, expected_version, status) => formsRequest(`${root(project)}/${encodeURIComponent(module)}/contacts/${encodeURIComponent(message)}`, { method: 'PATCH', body: JSON.stringify({ expected_version, status }) })
export const listNews = (project, module) => formsRequest(`${root(project)}/${encodeURIComponent(module)}/news`)
export const createNews = (project, module, payload) => formsRequest(`${root(project)}/${encodeURIComponent(module)}/news`, { method: 'POST', body: JSON.stringify(payload) })
export const updateNews = (project, module, article, payload) => formsRequest(`${root(project)}/${encodeURIComponent(module)}/news/${encodeURIComponent(article)}`, { method: 'PATCH', body: JSON.stringify(payload) })
export const listProducts = (project, module) => formsRequest(`${root(project)}/${encodeURIComponent(module)}/products`)
export const createProduct = (project, module, payload) => formsRequest(`${root(project)}/${encodeURIComponent(module)}/products`, { method: 'POST', body: JSON.stringify(payload) })
export const updateProduct = (project, module, product, payload) => formsRequest(`${root(project)}/${encodeURIComponent(module)}/products/${encodeURIComponent(product)}`, { method: 'PATCH', body: JSON.stringify(payload) })
export const listOrders = (project, module, status = '') => formsRequest(`${root(project)}/${encodeURIComponent(module)}/orders${status ? `?status=${encodeURIComponent(status)}` : ''}`)
export const getPaymentSettings = (project, module) => formsRequest(`${root(project)}/${encodeURIComponent(module)}/payment-settings`)
export const savePaymentSettings = (project, module, payload) => formsRequest(`${root(project)}/${encodeURIComponent(module)}/payment-settings`, { method: 'PUT', body: JSON.stringify(payload) })
export const reviewPaymentAttempt = (project, module, attempt, payload) => formsRequest(`${root(project)}/${encodeURIComponent(module)}/payment-attempts/${encodeURIComponent(attempt)}/review`, { method: 'POST', body: JSON.stringify(payload) })
export const embedModule = (project, module, route, expected_app_hash) => formsRequest(`${root(project)}/${encodeURIComponent(module)}/embed`, { method: 'POST', body: JSON.stringify({ route, expected_app_hash }) })
