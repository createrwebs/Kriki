import { test } from 'node:test'
import assert from 'node:assert/strict'
import { readFileSync } from 'node:fs'
import { applyFieldOperations, validateFormDefinition } from '../../resources/js/lab/lib/formDefinition.js'

const fixture = JSON.parse(readFileSync(new URL('../fixtures/forms/booking-v1.json', import.meta.url)))
const field = { id: 'field_11111111-1111-4111-8111-111111111111', label: 'แพ้อาหาร', type: 'text', required: false, archived: false }
test('template stays canonical and immutable', () => {
    assert.deepEqual(validateFormDefinition(fixture), fixture)
    const result = applyFieldOperations(fixture, [{ op: 'add', field }])
    assert.equal(result.fields.length, 6)
    assert.equal(fixture.fields.length, 5)
})
test('Thai label rename, archive, required and reorder preserve IDs', () => {
    const initial = applyFieldOperations(fixture, [{ op: 'add', field }])
    const result = applyFieldOperations(initial, [
        { op: 'label', id: field.id, label: 'รายละเอียดแพ้อาหาร' },
        { op: 'required', id: field.id, required: true },
        { op: 'reorder', ids: [field.id, ...fixture.fields.map(item => item.id)] },
        { op: 'archive', id: field.id },
    ])
    assert.equal(result.fields[0].id, field.id)
    assert.equal(result.fields[0].archived, true)
    assert.equal(initial.fields[5].label, 'แพ้อาหาร')
})
test('unknown operation, missing IDs, core archive and duplicate reorder rejected', () => {
    assert.throws(() => applyFieldOperations(fixture, [{ op: 'sql', sql: 'select 1' }]))
    assert.throws(() => applyFieldOperations(fixture, [{ op: 'archive', id: 'missing' }]))
    assert.throws(() => applyFieldOperations(fixture, [{ op: 'archive', id: 'phone' }]))
    assert.throws(() => applyFieldOperations(fixture, [{ op: 'reorder', ids: fixture.fields.map(() => 'phone') }]))
})
test('field limits and HTML agree with PHP contract', () => {
    assert.throws(() => validateFormDefinition({ ...fixture, fields: [...fixture.fields, { ...field, label: '<b>bad</b>' }] }))
    assert.throws(() => validateFormDefinition({ ...fixture, fields: [...fixture.fields, ...Array.from({ length: 21 }, (_, i) => ({ ...field, id: `field_${String(i).padStart(8, '0')}-1111-4111-8111-111111111111` }))] }))
    assert.throws(() => validateFormDefinition({ ...fixture, fields: [...fixture.fields, { ...field, type: 'select', options: Array.from({ length: 31 }, (_, i) => ({ id: `opt_${i}`, label: `${i}` })) }] }))
})
