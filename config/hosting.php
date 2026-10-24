<?php

return [

    // Experimental: enable only after migrations and deployment/origin checks.
    'enabled' => (bool) env('HOSTING_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Default Hosting Provider
    |--------------------------------------------------------------------------
    |
    | The provider used when none is specified in a deploy request.
    | Supported: "local" (more providers can be registered in HostingServiceProvider)
    |
    */

    'default_provider' => env('HOSTING_PROVIDER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Deployment Artifact Storage
    |--------------------------------------------------------------------------
    |
    | Where uploaded build artifacts (zips) are stored before deployment.
    |
    */

    'artifact_disk' => env('HOSTING_ARTIFACT_DISK', 'local'),
    'artifact_path' => env('HOSTING_ARTIFACT_PATH', 'lab/deployments'),

    /*
    |--------------------------------------------------------------------------
    | Artifact Limits
    |--------------------------------------------------------------------------
    */

    'artifact_max_bytes' => (int) env('HOSTING_ARTIFACT_MAX_BYTES', 50 * 1024 * 1024),
    'artifact_max_files' => (int) env('HOSTING_ARTIFACT_MAX_FILES', 5000),

    /*
    |--------------------------------------------------------------------------
    | Deployment Strategies
    |--------------------------------------------------------------------------
    |
    | Adapted from Reflex's deploy.py --strategy flag.
    |
    */

    'strategies' => ['immediate', 'rolling', 'bluegreen', 'canary'],
    'default_strategy' => env('HOSTING_DEFAULT_STRATEGY', 'immediate'),

    /*
    |--------------------------------------------------------------------------
    | Security Scanner
    |--------------------------------------------------------------------------
    |
    | Adapted from Reflex's scan.py --fail-on flag.
    |
    */

    'scan' => [
        'enabled' => (bool) env('HOSTING_SCAN_ENABLED', true),
        'default_fail_on' => env('HOSTING_SCAN_FAIL_ON', 'low'),
        'max_file_bytes' => (int) env('HOSTING_SCAN_MAX_FILE_BYTES', 1_000_000),
        'max_zip_bytes' => (int) env('HOSTING_SCAN_MAX_ZIP_BYTES', 50 * 1024 * 1024),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Tokens
    |--------------------------------------------------------------------------
    |
    | Adapted from Reflex's auth.py create-token --expires-in-days.
    |
    */

    'tokens' => [
        'default_expiry_days' => (int) env('HOSTING_TOKEN_EXPIRY_DAYS', 90),
        'max_expiry_days' => (int) env('HOSTING_TOKEN_MAX_EXPIRY_DAYS', 365),
        'prefix' => env('HOSTING_TOKEN_PREFIX', 'krik_'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Deployment History
    |--------------------------------------------------------------------------
    */

    'history' => [
        'per_page' => (int) env('HOSTING_HISTORY_PER_PAGE', 20),
        'max_retention_days' => (int) env('HOSTING_HISTORY_RETENTION_DAYS', 90),
    ],

];
