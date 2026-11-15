# Initial AI planner contract

`POST /lab/builder/ai-plan` accepts an authenticated, verified owner request:

```json
{
  "kind": "business",
  "template_id": "business-studio",
  "title": "คลินิกตัวอย่าง",
  "prompt": "คลินิกสุขภาพโทนเขียว เพิ่มฟอร์มจองคิวและข่าวสาร"
}
```

The endpoint creates a proposal from the selected trusted template. If an enabled AI provider is available it may refine the proposal; otherwise the deterministic smart assistant applies the supported palette, page and widget rules locally. Both paths sanitize and validate the result with `BlueprintSchema` before returning it.

The response contains `blueprint`, `source` (`ai` or `smart-assistant`), a bounded `plan` summary (pages, section counts and detected widget markers), and a message that the owner must review the proposal. The endpoint does not save a draft, create a Lab project, install a module, apply schema changes, touch business records, or publish a site. The existing explicit Save → Create project → Modules setup flow remains the only path that persists or provisions work.

Provider errors fall back locally. Quota denials and database errors remain explicit errors; no automatic retry or silent business write is performed. Live vendor behavior and production acceptance remain separate release gates.
