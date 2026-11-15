# Entity definition contract

`EntityDefinition` is the provider-neutral schema shared by the Lab data
planner and future record runtime. It is metadata only: it does not contain a
table name, SQL, customer rows or credentials.

Each definition has an `entity_uuid`, a plain text `label`, a positive
`version`, and a field map keyed by stable field UUID. Supported field types are
`text`, `textarea`, `integer`, `decimal`, `boolean`, `date`, `datetime`,
`select`, and `reference`.

The first slice enforces at most 20 entities per project at the registry layer
and at most 50 fields per entity. Field labels are unique within an entity.
Renaming a field keeps its UUID; changing an existing type is rejected;
archived fields must remain in later definitions so historical answers retain
their meaning. Decimal fields accept precision 1–18 and scale 0–2. Select
fields accept 1–50 options. Reference fields contain only a target entity UUID.

The PHP and JavaScript validators are intentionally parallel and are tested
against the same fixture. Applying a change to Supabase remains a separate
owner-reviewed additive proposal and is not implied by validation success.

Local verification on 8 November 2026 passed the PHP contract suite (8 checks)
and the JavaScript contract suite (4 tests). The PHP validator accepts the
canonical UUID-keyed field map and rejects list-shaped field payloads.
