import test from 'node:test'
import assert from 'node:assert/strict'
import fs from 'node:fs'
import { validateEntityDefinition } from '../../resources/js/lab/lib/entityDefinition.js'

const fixture = JSON.parse(fs.readFileSync(new URL('../fixtures/data/entity-v1.json', import.meta.url)))

test('fixture and PHP contract shape validate in JavaScript', () => {
  assert.deepEqual(validateEntityDefinition(fixture), {})
})

test('rename keeps stable field identity', () => {
  const next = structuredClone(fixture)
  next.version = 2
  const field = Object.keys(next.fields)[0]
  next.fields[field].label = 'ทะเบียนรถ'
  assert.deepEqual(validateEntityDefinition(next, fixture), {})
})

test('duplicate labels and type changes fail closed', () => {
  const duplicate = structuredClone(fixture)
  duplicate.fields['10000000-0000-4000-8000-000000000005'] = { label: 'ประเภท', type: 'text', required: false, archived: false, config: {} }
  assert.ok(Object.keys(validateEntityDefinition(duplicate)).some(key => key.endsWith('.label')))
  const changed = structuredClone(fixture)
  const field = Object.keys(changed.fields)[0]
  changed.version = 2
  changed.fields[field].type = 'integer'
  assert.ok(Object.keys(validateEntityDefinition(changed, fixture)).some(key => key.endsWith('.type')))
})

test('archive preserves history and invalid decimal bounds fail', () => {
  const archived = structuredClone(fixture)
  archived.version = 2
  const field = Object.keys(archived.fields)[0]
  archived.fields[field].archived = true
  assert.deepEqual(validateEntityDefinition(archived, fixture), {})
  const decimal = structuredClone(fixture)
  const decimalField = Object.keys(decimal.fields)[0]
  decimal.fields[decimalField] = { label: 'ราคา', type: 'decimal', required: false, archived: false, config: { precision: 19, scale: 3 } }
  assert.ok(Object.keys(validateEntityDefinition(decimal)).some(key => key.includes('config')))
})
