export const ENTITY_TYPES = ['text', 'textarea', 'integer', 'decimal', 'boolean', 'date', 'datetime', 'select', 'reference']
export const MAX_FIELDS = 50

const UUID = /^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i
const plain = (value, min, max) => typeof value === 'string' && value.trim().length >= min && [...value].length <= max && !/[\u0000-\u001f\u007f]/.test(value)

export function validateEntityDefinition(definition, previous = null) {
  const errors = {}
  const fail = (path, message) => { if (!errors[path]) errors[path] = message }
  if (!definition || typeof definition !== 'object' || Array.isArray(definition)) return { definition: 'Definition must be an object.' }
  for (const key of Object.keys(definition)) if (!['entity_uuid', 'label', 'version', 'fields'].includes(key)) fail(key, 'Unsupported schema key.')
  if (!UUID.test(definition.entity_uuid || '')) fail('entity_uuid', 'Use a UUID.')
  if (!plain(definition.label, 1, 120)) fail('label', 'Use plain text of 1–120 characters.')
  if (!Number.isInteger(definition.version) || definition.version < 1) fail('version', 'Version must be positive.')
  if (!definition.fields || typeof definition.fields !== 'object' || Array.isArray(definition.fields)) fail('fields', 'Fields must be an object keyed by UUID.')
  else if (Object.keys(definition.fields).length > MAX_FIELDS) fail('fields', 'An entity may contain at most 50 fields.')
  else validateFields(definition.fields, previous?.fields || {}, fail)
  if (previous) {
    if (previous.entity_uuid !== definition.entity_uuid) fail('entity_uuid', 'Entity UUID cannot change.')
    if (definition.version !== previous.version + 1) fail('version', 'Schema changes increment version by one.')
    for (const [id, field] of Object.entries(previous.fields || {})) if (field.archived && (!definition.fields?.[id] || !definition.fields[id].archived)) fail(`fields.${id}`, 'Archived fields must remain for history.')
  }
  return errors
}

function validateFields(fields, previous, fail) {
  const labels = new Set()
  for (const [id, field] of Object.entries(fields)) {
    const path = `fields.${id}`
    if (!UUID.test(id)) { fail(path, 'Field keys must be UUIDs.'); continue }
    if (!field || typeof field !== 'object' || Array.isArray(field)) { fail(path, 'Field must be an object.'); continue }
    for (const key of Object.keys(field)) if (!['label', 'type', 'required', 'archived', 'config'].includes(key)) fail(`${path}.${key}`, 'Unsupported field key.')
    if (!plain(field.label, 1, 120)) fail(`${path}.label`, 'Use plain text of 1–120 characters.')
    const label = String(field.label || '').trim().toLocaleLowerCase()
    if (labels.has(label)) fail(`${path}.label`, 'Field labels must be unique.')
    labels.add(label)
    if (!ENTITY_TYPES.includes(field.type)) fail(`${path}.type`, 'Unsupported field type.')
    for (const flag of ['required', 'archived']) if (typeof field[flag] !== 'boolean') fail(`${path}.${flag}`, 'Field flag must be boolean.')
    validateConfig(field, path, fail)
    if (previous[id]?.type && previous[id].type !== field.type) fail(`${path}.type`, 'Changing field type is not additive.')
    if (previous[id]?.archived && !field.archived) fail(`${path}.archived`, 'Archived fields cannot be restored silently.')
  }
}

function validateConfig(field, path, fail) {
  const config = field.config || {}
  if (!config || typeof config !== 'object' || Array.isArray(config)) { fail(`${path}.config`, 'Config must be an object.'); return }
  if (field.type === 'decimal') {
    if (!Number.isInteger(config.precision) || config.precision < 1 || config.precision > 18 || !Number.isInteger(config.scale) || config.scale < 0 || config.scale > 2 || config.scale > config.precision) fail(`${path}.config`, 'Decimal precision/scale is invalid.')
    for (const key of Object.keys(config)) if (!['precision', 'scale'].includes(key)) fail(`${path}.config.${key}`, 'Unsupported config key.')
  } else if (field.type === 'select') {
    if (!Array.isArray(config.options) || config.options.length < 1 || config.options.length > 50 || config.options.some(option => typeof option !== 'string' || option.trim() === '')) fail(`${path}.config.options`, 'Select options must contain 1–50 non-empty values.')
    for (const key of Object.keys(config)) if (key !== 'options') fail(`${path}.config.${key}`, 'Unsupported config key.')
  } else if (field.type === 'reference') {
    if (!UUID.test(config.entity_uuid || '')) fail(`${path}.config.entity_uuid`, 'Reference target must be a UUID.')
    for (const key of Object.keys(config)) if (key !== 'entity_uuid') fail(`${path}.config.${key}`, 'Unsupported config key.')
  } else if (Object.keys(config).length) fail(`${path}.config`, 'This field type does not accept config.')
}
