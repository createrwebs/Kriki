# Builder AI edit

`POST /lab/builder/ai-edit` remains behind authenticated, verified session and CSRF middleware. Body: a valid catalog blueprint, nonempty prompt (or existing summary), `scope` (`all`, `page`, `section`), and selected page/section UUIDs when scoped.

The result is an editable blueprint preview. Saving the draft, materializing source, installing managed modules and publishing remain separate explicit actions. Widget copy/markers alone do not create a database, live cart, payment recipient or upload endpoint.

`all` may change the full blueprint. `page` may change only the selected existing page, preserving its identity; global title, brief, brand, theme, all other pages and their order remain exact. `section` may change only the selected existing section, preserving its identity, page metadata, sibling sections and order. Unknown/mismatched targets or scope values return422 before any provider call. A provider result missing the target is rejected in favor of the bounded local editor.

Configured model selection uses the existing model catalog. Provider calls require existing Lab credit quota and settle response usage using the existing credit meter, including malformed returned content that already consumed vendor tokens. Exhausted credit returns the normal quota error before network. No configured provider or provider failure uses the deterministic local editor with `source: smart-assistant`; this fallback consumes no vendor credits. `source: ai` requires an actual valid provider result. These guarantees do not establish live provider availability or replace review of generated content.
