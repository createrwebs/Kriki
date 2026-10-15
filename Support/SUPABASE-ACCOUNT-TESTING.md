# Supabase: existing account-scoped workflow

The current selection belongs to a Krikkit account, not a Lab website. All Lab
workspaces for that account resolve the same currently selected Supabase project.
Changing that selection does not automatically rewrite existing generated `.env`
files or migrate data. Do not present this as isolated per-site administration.

## Setup

1. An authorized platform administrator configures the Supabase OAuth application
   in Dashboard → API Integration → Supabase. Keep the client secret on the server.
2. Configure an OAuth callback matching the Supabase app registration. Production
   requires HTTPS. When APP_ENV=local, exact loopback HTTP hosts are also supported,
   for example `http://localhost:8889/lab/oauth/supabase/return`. Save the same URL
   in Krikkit's redirect override. Do not disable certificate validation.
3. The customer connects their Supabase account from Lab's Supabase action, then
   selects a project they own. Schema changes require explicit user authorization.
4. Survey reads that account's selected schema. The generated browser client uses
   only the URL and publishable key. Service-role and management keys stay server-side.

An unconnected account now receives a missing connection even if platform-level
database credentials exist. Disconnecting must not restore platform privileges.
Explicit system-context calls retain the legacy platform configuration.

## Reproducible local regression

Run `tests/regressions/datastore-account-isolation.php` with PHP extensions
openssl, pdo_sqlite, mbstring and curl enabled. It uses an in-memory database and
fake HTTP, never the local customer tables or a live Supabase project. It checks
unconnected and partial attachments, linked-account schema survey, private-key
non-disclosure, disconnect behavior and preservation of other accounts.

These checks do not verify a live OAuth handshake, live SQL/RLS, bookings, payment
processing, or publishing. Those need a configured test Supabase project and
separate end-to-end verification. The existing platform billing is not merchant
checkout for customer websites.
