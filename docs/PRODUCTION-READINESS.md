# Production readiness check

Krikkit now has a read-only preflight command that checks the local runtime and release gates that can be verified without contacting Supabase, payment providers or production DNS.

```powershell
& 'C:/MAMP/bin/php/php8.5.10/php.exe' `
  -d extension_dir=C:/MAMP/bin/php/php8.5.10/ext `
  -d extension=openssl -d extension=pdo_mysql -d extension=mbstring `
  -d extension=curl -d extension=fileinfo -d extension=zip -d extension=intl `
  artisan krikkit:readiness
```

For CI or deployment tooling use `--json` for a stable machine-readable report, or `--strict` to return a non-zero exit code for warnings as well as failures. The command never prints secret values and never mutates application data.

The checks cover PHP/runtime extensions, `APP_KEY`, `APP_URL`, `APP_DEBUG`, AI model/provider mapping, queue/cache/session/mail drivers, writable storage, publish domain configuration, managed business-write state, experimental hosting state and credential artifacts at the project root.

Warnings are intentional until the corresponding external gate is accepted. `business_writes_enabled=false` must remain closed until Supabase schema/RLS/Storage, origin isolation and browser acceptance pass. Hosting also remains disabled until its migration, concurrency and origin review is complete.

The repository requires PHP `^8.4.1`, matching the locked Symfony/Laravel dependency graph. Run `composer validate --check-lock` and `composer audit` before release. A clean local audit does not replace live payment, Supabase, DNS, browser or independent security acceptance.
