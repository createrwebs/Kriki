# Reflex reference analysis

Date:2026-10-16 · Read-only research, not installed or executed

- Source: https://github.com/reflex-dev/reflex.git
- Clone: C:/Users/theer/ghq/github.com/reflex-dev/reflex
- Commit:81f463bc3491e3ac71f30f520eafe9d03570debb
- Clone command was ghq get --shallow --no-recursive with the user-provided URL; existing clones checked first. Working tree was clean after reading.
- LICENSE inspected: Apache-2.0. If copying substantial code later, include license/retained notices, mark changed files, check NOTICE and per-package/dependency licenses, and record source paths/commit in THIRD_PARTY_NOTICES. No trademark/branding use is assumed. This is an engineering compliance checklist, not legal clearance.

## What the repository is

A Python full-stack web framework with frontend compilation, event/state processing, optional database facilities and separately packaged components/tooling. Root pyproject.toml at this commit requires Python>=3.11 and declares server/runtime dependencies. This is not the complete source of the hosted commercial AI Builder service linked from README.

Do not equate downloading framework source with cloning the hosted builder, or with making a React-Vite-only production artifact run Python backend events. No dependencies, demos, containers, lifecycle hooks, agent instructions or migrations in this clone were run.

## Specific source-to-feature map

| Pinned source path | Observed function/responsibility | Krikkit adaptation | Gate |
| --- | --- | --- | --- |
| reflex/state.py | BaseState/State, StateUpdate, serialize_state_update | Separate UI-state from savedrecords; explicit loading/error/success/conflict and versioned results | Apply to managed React components, not transplant Python state.py |
| packages/reflex-base/src/reflex_base/event/__init__.py | typed Event/EventHandler machinery; reflex/event.py is a re-export | Allowlisted typed action contract with servervalidation; AI proposes action instead of freeform source/SQL to mutate data | CapabilityPolicy and schemas first |
| reflex/istate/manager/__init__.py + memory.py/disk.py/redis.py | StateManager and modification/locking contexts | Per-project/session cache keys, state version and contention tests | State token/lock is not accountauthorization |
| reflex/compiler/compiler.py | compile_page/compile_page_from_context/compile_app | Source → compile report → artifact → smoke test distinction; page registry invariants | Keep existing React build; do not rewrite compiler |
| reflex/app.py | App, Socket.IO EventNamespace and state integration | Event lifecycle concept only; REST response/refetch suffices now | No new generic WebSocket server in v1 |
| packages/reflex-components-radix/src/reflex_components_radix/primitives/form.py | FormRoot/FormField/server_invalid and named controls | Accessible standard form renderer, stable fieldnames, server error mapping | Preserve existing BookingForm customfield IDs |
| reflex/_upload.py + packages/reflex-components-core/src/reflex_components_core/core/_upload.py | upload event path in Python framework | Study progress/cancellation/error UX | Do not use a public uploads directory for bank slips |
| reflex/model.py + docs/database/overview.md | database connection/session behavior and optional db extra | Server-only credentials, short transactions, explicit migrations | Use current Laravel/Supabase; no SQLModel dependency needed |
| tests/units/istate/manager + tests/units/test_state.py | lock/session/state test cases | Two-project/concurrent-event regression scenarios | Adapt behaviors, not assume tests cover our boundaries |

Current database docs explicitly identify rx.Model as deprecated and prefer SQLModel/SQLAlchemy for new Python applications. Do not base future runtime design on remembered older Reflex tutorials. Website architecture URLs tested during research were unavailable; findings above derive from the pinned local source and official database documentation instead.

## What to implement now versus defer

Now in our existing stack: standardized module manifest; typed action/proposal contract; servervalidation/errors; UI state/persistence distinction; version conflict handling; project-scoped locks/cache; realdata UI regeneration. These can be original PHP/React implementations inspired by behavior, with attribution if actual code is adapted.

Future gated probe only: a tiny Python Reflex app in a fresh isolated container with fake data, build+HTTP/event+DB roundtrip; prove process/filesystem/credentials/network isolation and operational cost. An operator must approve sandbox infrastructure before exposing this as customer runtime. Success yields a separate supported-runtime proposal, not silent platformmigration.

Not reused: hosted AI Builder branding/source claims, global state engines, shared backendprocess for tenants, dev upload serving for slips, automatic database create_all as production migration, or deprecated ORM APIs.

## Verification actually done

Clone exit0; commit and working tree read; LICENSE/pyproject/source declarations and targeted implementation sections inspected. No test suite run; no claim the framework runs on this user's machine or integrates with Krikkit yet. All proposed adaptation remains unimplemented in this planning turn.
