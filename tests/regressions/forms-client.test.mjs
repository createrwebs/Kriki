import { test } from 'node:test'
import assert from 'node:assert/strict'
import { formsRequest } from '../../resources/js/lab/lib/forms.js'

test('owner transport preserves structured validation errors and conflicts', async () => {
    await assert.rejects(formsRequest('/fixture', {}, async () => ({ ok: false, status: 409, json: async () => ({ message: 'changed', errors: { definition: ['bad'] } }) })), error => error.status === 409 && error.payload.errors.definition[0] === 'bad')
})
