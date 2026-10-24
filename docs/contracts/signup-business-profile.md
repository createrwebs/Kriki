# Signup business profile contract

Scope: existing web registration form, `POST /register`; this is not a new public API. Task 1 supplies storage and validation. Atomic persistence and form presentation are integrated in later signup tasks.

| Flat POST field | Validation / default |
| --- | --- |
| `name` | Required string, max 255; existing behavior |
| `email` | Required valid email string, max 255, unique in users |
| `password`, `password_confirmation` | Existing password policy and confirmation |
| `company_name` | Optional nullable string, max 255 characters |
| `business_description` | Optional nullable string, max 2000 characters |
| `website_budget_amount` | Optional nullable decimal **string**; 1–12 integer digits and optionally 1–2 fractional digits, from `0` through `999999999999.99`. No signs, exponent, grouping, non-finite values or extra precision. Send an exact decimal string, never a floating-point number. Empty form values are converted to null by existing web middleware. |
| `website_budget_currency` | Optional `THB` or `USD`; absent, null or blank currency becomes `THB`; unsupported values/types fail validation. No currency conversion. |

`RegisterRequest::authData(): array` returns only validated `name`, `email`, `password`. `RegisterRequest::businessProfileData(): array` returns exactly `company_name`, `business_description`, `website_budget_amount`, `website_budget_currency`, with missing nullable fields set to null and the default currency applied. Neither accepts ownership, role, status or preferences from submitted input. Server registration assigns these independently.

Storage is additive: `user_business_profiles` has one unique user foreign key with cascade deletion, nullable company/description, nullable `decimal(14,2)` amount and `char(3)` currency defaulting to `THB`. `User::businessProfile()` and `UserBusinessProfile::user()` define ownership. Profile creation uses the user relationship; `user_id` is excluded from the profile mass-assignment allowlist. Budget reads use Laravel `decimal:2` strings, and budget amount/currency are hidden in default array/JSON serialization. The relation is not automatically loaded or appended to users. Existing accounts do not require profiles or backfill.

Integration target: successful registration remains a 302 redirect to the existing home route, with the existing invited/member account and email verification behavior. Registration validation errors remain a 302 redirect with session errors and escaped old input, including when an Accept JSON header is supplied: the existing exception policy in bootstrap/app.php enables JSON errors only for API/Lab paths, not this web route. Passwords must not be flashed. Existing CSRF, guest/auth and rate-limit middleware remain in force. Controller/HTTP integration is verified separately from Task 1 storage tests.

The budget describes the website project; it is not a package price, AI credit limit or payment authorization. Signup performs no AI, billing, charge or provider operation. Do not automatically include budget in AI context, logs, exports or public bootstrap/preview data. Future uses require explicit field selection; never serialize the whole profile into AI context.

Local verification: `tests/regressions/signup-business-profile.php` forces SQLite memory before provider bootstrap, uses the actual additive migration, fakes HTTP and notifications, and never migrates the configured customer database. No external Postman workspace is used.
