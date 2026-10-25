# GitHub complete source and promotion boundary

Implemented repair to the existing importer, not complete Gate G or permission to execute imported code.

## Commit and file identity

The importer resolves the selected/default branch once and uses the resulting40-character commit for root discovery, recursive trees, blobs or ZIP download. A successful import response adds `commit_sha`; the remembered remote keeps the actual selected/default ref, not an assumed main branch. This is per-operation provenance evidence, not the planned persistent inspection/job history.

Recursive tree responses must explicitly declare `truncated:false` with a complete list of typed entries. Paths, modes, blob SHA and nonnegative integer sizes are validated. Missing/malformed metadata is rejected before staging; no ZIP retry bypasses that failure. GitHub documents the tree flag and blob modes in [Git trees](https://docs.github.com/en/rest/git/trees).

Selected subtrees reject symbolic links, submodules, unsafe/ambiguous Windows paths, case/type collisions and excess depth. Source ceilings remain2500files/2MiB each/100MiB total, with lower settings respected. The fetcher validates the whole selected manifest before requesting blobs, checks response-size limits during transfer, and requires every blob response to match its advertised SHA, size, Base64 encoding and actual Git object hash. Any download/decoding/identity failure aborts the import and preserves live source. The JSON representation is defined by [Git blobs](https://docs.github.com/en/rest/git/blobs); object hashing follows [Git object storage](https://git-scm.com/book/en/v2/Git-Internals-Git-Objects).

Binary assets and dependency lockfiles are retained as inert disk source. Existing editor/snapshot text checks stay in place; importing does not install dependencies, run hooks or create a runnable artifact. Existing hidden-path filters still apply and skipped paths are counted. A full source/license/secret/runtime inspection, complete reviewed diff and persistent approval remain separate G1–G3 work.

Both staged map and archive/tree writers now reject live `.env` files, recognized private-key/credential containers, known token prefixes, long privileged-key assignments and privileged-role JWTs in text. Error bodies do not echo paths, source or credential values. Safe example placeholders are allowed; template files containing detected real-looking credentials are rejected too. Tree ingestion inspects the exact bounded bytes it writes. This is known-pattern detection, not a comprehensive secret audit, license approval or permission to execute/publish source; custom encodings/unknown credential formats still require reviewed inspection. No external scanning service or real token is used by the tests.

## Promotion admission

The existing import controller passes its server-issued admission to a pre-promotion boundary. Under the workspace filesystem lock, the existing promotion transaction locks actor/project/lease, rechecks current ownership, writable state, import kind, lease UUID, active status and expiry. A cancelled, expired or replaced worker receives409 before source rename; a frozen/foreign actor receives403. Stale workers cannot finalize a replacement UUID. Content CAS, staging backup and rollback still protect concurrent source edits.

Fetching never holds this database transaction. The callback is internal PHP code, never a request/AI field. Trusted internal filesystem callers retain their existing contract; separate GitHub pull/adaptation approval and production resource enforcement remain pending. This admission is not an OS sandbox or crash-recovery journal.

## Local evidence

`github-source-completeness.php` uses actual Laravel HTTP through the local Postman GitHub simulation, including failed/invalid/truncated/corrupt/oversized responses, path/mode collisions and successful pinned binary/lock source. `github-import-fence.php` invalidates actual in-flight admissions on HTTP receipt and checks preservation/status/nonce cleanup, then verifies valid promotion. Neither builds nor executes imported source. Earlier archive/import/source/SDK checks remain required; live GitHub/private OAuth/rate-limit behavior and final independent review are not established by the simulation.
