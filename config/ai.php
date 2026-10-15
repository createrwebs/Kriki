<?php

use App\Ai\Drivers\AnthropicDriver;
use App\Ai\Drivers\GoogleGeminiDriver;
use App\Ai\Drivers\OpenAiCompatibleDriver;
use App\Ai\Drivers\OpenAiResponsesDriver;

/**
 * AI gateway config.
 *
 * Adding a model: append one entry under `models` pointing at a `providers` key.
 * Adding a vendor: add a `providers` entry with an existing `driver` (or register a new driver class).
 * Prompts are shared by key (e.g. `lab`) — never per-model.
 * Workspace API keys / default model can override env via the dashboard AI settings page.
 */

return [

    'default_model' => env('AI_DEFAULT_MODEL', 'claude-sonnet-4-5'),

    /*
    | Lab credits: token-weighted spend mapped to integer credits.
    | ~1 credit for a short chat on a cheap model; mid-teens for a large codegen turn.
    */
    'credits' => [
        'tokens_per_credit' => (int) env('AI_CREDITS_TOKENS_PER_CREDIT', 25000),
        'output_weight' => (float) env('AI_CREDITS_OUTPUT_WEIGHT', 4),
        'cached_weight' => (float) env('AI_CREDITS_CACHED_WEIGHT', 0.25),
        'cache_creation_weight' => (float) env('AI_CREDITS_CACHE_CREATION_WEIGHT', 1.25),
        'baseline_output_per_mtok' => (float) env('AI_CREDITS_BASELINE_OUTPUT', 5.0),
        'vfs_bytes_per_credit' => (int) env('AI_CREDITS_VFS_BYTES', 80000),
        'tool_calls_per_credit' => (int) env('AI_CREDITS_TOOL_CALLS', 6),
        'model_coefficients' => [
            // Optional overrides. Default is output_per_mtok / baseline (Haiku 4.5 = 1.0).
        ],
    ],

    /*
    | Demo/mock replies when a provider has no API key are disabled.
    | Missing keys always throw ProviderException — configure ANTHROPIC_API_KEY /
    | OPENAI_API_KEY (or workspace AI settings) for Lab chat to work.
    */
    'demo_when_unkeyed' => env('AI_DEMO_WHEN_UNKEYED', false),

    /*
    | Outbound provider HTTP (Guzzle). disable_proxy avoids IDE/sandbox HTTP_PROXY
    | inheritance that can hang Lab chat and show up as browser "Failed to fetch".
    */
    'http' => [
        'timeout' => (int) env('AI_HTTP_TIMEOUT', 120),
        'connect_timeout' => (int) env('AI_HTTP_CONNECT_TIMEOUT', 15),
        'disable_proxy' => filter_var(env('AI_HTTP_DISABLE_PROXY', true), FILTER_VALIDATE_BOOL),
        'force_ipv4' => filter_var(env('AI_HTTP_FORCE_IPV4', true), FILTER_VALIDATE_BOOL),
    ],

    'prompts' => [
        // Ordered layers: behavior first, then UI generation directive.
        'lab' => [
            resource_path('prompts/lab/system.md'),
            resource_path('prompts/lab/design.md'),
        ],
        'translate' => resource_path('prompts/translate/system.md'),
        'title' => resource_path('prompts/title/system.md'),
    ],

    'drivers' => [
        'openai_compatible' => OpenAiCompatibleDriver::class,
        'openai_responses' => OpenAiResponsesDriver::class,
        'anthropic' => AnthropicDriver::class,
        'google_gemini' => GoogleGeminiDriver::class,
    ],

    'providers' => [

        'anthropic' => [
            'driver' => 'anthropic',
            'label' => 'Claude',
            'api_key' => env('ANTHROPIC_API_KEY'),
            'base_url' => env('ANTHROPIC_BASE_URL', 'https://modelflare.dev'),
            'version' => env('ANTHROPIC_VERSION', '2023-06-01'),
            // Executor turns stream complete site files (32k output budget) —
            // long generations need more than the old 120s window.
            'timeout' => (int) env('ANTHROPIC_TIMEOUT', 600),
        ],

        'openai' => [
            'driver' => env('OPENAI_API_DRIVER', 'openai_compatible'),
            'label' => 'OpenAI',
            'api_key' => env('OPENAI_API_KEY'),
            'base_url' => env('OPENAI_BASE_URL', 'https://modelflare.dev/v1'),
            'timeout' => (int) env('AI_PROVIDER_TIMEOUT', 600),
        ],

        'xai' => [
            'driver' => 'openai_compatible',
            'label' => 'xAI',
            'api_key' => env('XAI_API_KEY'),
            'base_url' => env('XAI_BASE_URL', 'https://modelflare.dev/v1'),
            'timeout' => (int) env('AI_PROVIDER_TIMEOUT', 600),
        ],

        'deepseek' => [
            'driver' => 'openai_compatible',
            'label' => 'DeepSeek',
            'api_key' => env('DEEPSEEK_API_KEY'),
            'base_url' => env('DEEPSEEK_BASE_URL', 'https://modelflare.dev/v1'),
            'timeout' => (int) env('AI_PROVIDER_TIMEOUT', 600),
        ],

        'groq' => [
            'driver' => 'openai_compatible',
            'label' => 'Groq',
            'api_key' => env('GROQ_API_KEY'),
            'base_url' => env('GROQ_BASE_URL', 'https://modelflare.dev/v1'),
            'timeout' => (int) env('AI_PROVIDER_TIMEOUT', 600),
        ],

        'mistral' => [
            'driver' => 'openai_compatible',
            'label' => 'Mistral',
            'api_key' => env('MISTRAL_API_KEY'),
            'base_url' => env('MISTRAL_BASE_URL', 'https://modelflare.dev/v1'),
            'timeout' => (int) env('AI_PROVIDER_TIMEOUT', 600),
        ],

        'zhipuai' => [
            'driver' => 'openai_compatible',
            'label' => 'ZhipuAI',
            'api_key' => env('ZHIPUAI_API_KEY'),
            'base_url' => env('ZHIPUAI_BASE_URL', 'https://open.bigmodel.cn/api/paas/v4'),
            'timeout' => (int) env('AI_PROVIDER_TIMEOUT', 600),
        ],

        'google' => [
           'driver' => 'openai_compatible',
            'label' => 'Google AI Studio',
            'api_key' => env('GOOGLE_AI_API_KEY'),
            'base_url' => env('GOOGLE_AI_BASE_URL', 'https://modelflare.dev/v1'),
            'timeout' => (int) env('AI_PROVIDER_TIMEOUT', 600),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cost scenarios (v0 / Lovable-style site building)
    |--------------------------------------------------------------------------
    | Token counts are planning estimates for a generation product — not billing.
    | Tune as you observe real Lab sessions.
    */
    'cost_scenarios' => [

        'landing_draft' => [
            'label' => 'ร่างหน้า Landing',
            'blurb' => 'คำสั้น ๆ หนึ่งข้อ → ฮีโร่ ส่วนต่าง ๆ เนื้อหา CTA การสร้างครั้งแรกแบบ Lovable-style',
            'input_tokens' => 2_500,
            'output_tokens' => 3_500,
        ],

        'marketing_site' => [
            'label' => 'เว็บไซต์การตลาด',
            'blurb' => 'หน้า Landing แบบเต็ม (เมนูนำทาง คุณสมบัติ ราคา FAQ) พร้อมโครงสร้างและเนื้อหา เหมือนการสร้าง v0 ที่มั่นคง',
            'input_tokens' => 12_000,
            'output_tokens' => 22_000,
        ],

        'iterate_pass' => [
            'label' => 'ชุดปรับปรุง ',
            'blurb' => ' “ทำให้เว็บไซต์เด่นขึ้น / สลับส่วน” หลังจากการสร้างพื้นฐาน.',
            'input_tokens' => 40_000,
            'output_tokens' => 18_000,
        ],

        'saas_multipage' => [
            'label' => 'SaaS multi-page',
            'blurb' => 'หน้าแรก + ราคา + รายละเอียดผลิตภัณฑ์ พร้อมการปรับปรุงแก้ไข — ใกล้เคียงกับเว็บไซต์สินค้าขนาดเล็กมากขึ้น',
            'input_tokens' => 45_000,
            'output_tokens' => 70_000,
        ],

    ],

    'models' => [
        'claude-fable-5-1' => [
            'provider' => 'anthropic',
            'api_model' => 'claude-fable-5-1-20250929',
            'label' => 'Claude Fable 5.1',
            'family' => 'claude',
            'pricing' => ['input_per_mtok' => 3.00, 'output_per_mtok' => 15.00],
            'max_output' => 64000,
        ],
        'claude-fable-5' => [
            'provider' => 'anthropic',
            'api_model' => 'claude-fable-5-20250929',
            'label' => 'Claude Fable 5',
            'family' => 'claude',
            'pricing' => ['input_per_mtok' => 3.00, 'output_per_mtok' => 15.00],
            'max_output' => 64000,
        ],
        'claude-opus-5' => [
            'provider' => 'anthropic',
            'api_model' => 'claude-opus-5-20250929',
            'label' => 'Claude Opus 5',
            'family' => 'claude',
            'pricing' => ['input_per_mtok' => 3.00, 'output_per_mtok' => 15.00],
            'max_output' => 64000,
        ],
         'claude-sonnet-5' => [
            'provider' => 'anthropic',
            'api_model' => 'claude-sonnet-5-20250929',
            'label' => 'Claude Sonnet 5',
            'family' => 'claude',
            'pricing' => ['input_per_mtok' => 3.00, 'output_per_mtok' => 15.00],
            'max_output' => 64000,
        ],
        'claude-haiku-4-5' => [
            'provider' => 'anthropic',
            'api_model' => 'claude-haiku-4-5-20251001',
            'label' => 'Claude Haiku 4.5',
            'family' => 'claude',
            'pricing' => ['input_per_mtok' => 1.00, 'output_per_mtok' => 5.00],
            'max_output' => 64000,
        ],
         'gpt-6-astra' => [
            'provider' => 'openai',
            'api_model' => 'gpt-6-astra',
            'label' => 'GPT-6 Astra',
            'family' => 'openai',
            'pricing' => ['input_per_mtok' => 20.00, 'output_per_mtok' => 50.00],
            'max_output' => 128000,
        ],
        'gpt-6.1-sol' => [
            'provider' => 'openai',
            'api_model' => 'gpt-6.1-sol',
            'label' => 'GPT-6.1 Sol',
            'family' => 'openai',
            'max_output' => 32768,
        ],
        'gpt-5.6-sol' => [
            'provider' => 'openai',
            'api_model' => 'gpt-5.6-sol',
            'label' => 'GPT-5.6 Sol',
            'family' => 'openai',
            'pricing' => ['input_per_mtok' => 5.00, 'output_per_mtok' => 30.00],
            'max_output' => 128000,
        ],
         'gpt-5.5' => [
            'provider' => 'openai',
            'api_model' => 'gpt-5.5',
            'label' => 'GPT-5.5',
            'family' => 'openai',
            'pricing' => ['input_per_mtok' => 5.00, 'output_per_mtok' => 30.00],
            'max_output' => 128000,
        ],
       

        'grok-4-6' => [
            'provider' => 'xai',
            'api_model' => 'grok-4.6',
            'label' => 'Grok 4.6',
            'family' => 'grok',
            'pricing' => ['input_per_mtok' => 2.00, 'output_per_mtok' => 6.00],
            'max_output' => 32768,
        ],
        'grok-4-5' => [
            'provider' => 'xai',
            'api_model' => 'grok-4.5',
            'label' => 'Grok 4.5',
            'family' => 'grok',
            'pricing' => ['input_per_mtok' => 2.00, 'output_per_mtok' => 6.00],
            'max_output' => 32768,
        ],

        'deepseek-v4-pro' => [
            'provider' => 'deepseek',
            'api_model' => 'deepseek-v4-pro',
            'label' => 'DeepSeek V4 Pro',
            'family' => 'deepseek',
            'pricing' => ['input_per_mtok' => 0.27, 'output_per_mtok' => 1.10],
            'max_output' => 8192,
        ],
        'deepseek-v4.1-flash' => [
            'provider' => 'deepseek',
            'api_model' => 'deepseek-v4.1-flash',
            'label' => 'DeepSeek V4.1 Flash',
            'family' => 'deepseek',
            'pricing' => ['input_per_mtok' => 0.55, 'output_per_mtok' => 2.19],
            'max_output' => 8192,
        ],

        'llama-3.3-70b' => [
            'provider' => 'groq',
            'api_model' => 'llama-3.3-70b-versatile',
            'label' => 'Llama 3.3 70B',
            'family' => 'groq',
            'pricing' => ['input_per_mtok' => 0.59, 'output_per_mtok' => 0.79],
            'max_output' => 32768,
        ],
        'llama-3.1-8b' => [
            'provider' => 'groq',
            'api_model' => 'llama-3.1-8b-instant',
            'label' => 'Llama 3.1 8B',
            'family' => 'groq',
            'pricing' => ['input_per_mtok' => 0.05, 'output_per_mtok' => 0.08],
            'max_output' => 8192,
        ],

        'mistral-large' => [
            'provider' => 'mistral',
            'api_model' => 'mistral-large-latest',
            'label' => 'Mistral Large',
            'family' => 'mistral',
            'pricing' => ['input_per_mtok' => 2.00, 'output_per_mtok' => 6.00],
            'max_output' => 32768,
        ],
        'mistral-small' => [
            'provider' => 'mistral',
            'api_model' => 'mistral-small-latest',
            'label' => 'Mistral Small',
            'family' => 'mistral',
            'pricing' => ['input_per_mtok' => 0.20, 'output_per_mtok' => 0.60],
            'max_output' => 16384,
        ],

        'glm-5.3' => [
            'provider' => 'zhipuai',
            'api_model' => 'glm-5.3',
            'label' => 'GLM-5.3',
            'family' => 'zhipuai',
            'pricing' => ['input_per_mtok' => 0.70, 'output_per_mtok' => 0.70],
            'max_output' => 8192,
        ],
        'glm-5.3-flash' => [
            'provider' => 'zhipuai',
            'api_model' => 'glm-5.3-flash',
            'label' => 'GLM-5.3 Flash',
            'family' => 'zhipuai',
            'pricing' => ['input_per_mtok' => 0.10, 'output_per_mtok' => 0.10],
            'max_output' => 4096,
        ],

        'gemini-3.8-flash' => [
            'provider' => 'google',
            'api_model' => 'gemini-3.8-flash',
            'label' => 'Gemini 3.8 Flash',
            'family' => 'gemini',
            'pricing' => ['input_per_mtok' => 0.30, 'output_per_mtok' => 2.50],
            'max_output' => 65536,
        ],
        'gemini-3.7-flash' => [
            'provider' => 'google',
            'api_model' => 'gemini-3.7-flash',
            'label' => 'Gemini 3.7 Flash',
            'family' => 'gemini',
            'pricing' => ['input_per_mtok' => 1.25, 'output_per_mtok' => 10.00],
            'max_output' => 65536,
        ],

    ],

];
