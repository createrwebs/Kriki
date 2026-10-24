@php
    $problem = $landing->section('problem');
    $platform = $landing->section('platform');
    $agents = $landing->section('agents');
    $walk = $landing->section('walkthrough');
    $integrations = $landing->section('integrations');
    $exampleImages = ['editorial-portfolio', 'premium-store', 'auto-repair'];
    $features = app(\App\Support\Site\ProductCapabilities::class)->publicFeatures();
    // Omit only the old bundled sample quote; never silently hide operator copy.
    $legacySampleQuote = ($platform['quote'] ?? '') === 'We went from a sentence to a page we could click through before the meeting ended.'
        && ($platform['quote_name'] ?? '') === 'Studio lead'
        && ($platform['quote_role'] ?? '') === 'Independent practice';
@endphp

<section id="about" class="sales-intro sales-wrap">
    <h2>{{ $problem['title'] }}</h2><p>{{ $problem['copy'] }}</p>
</section>

<section id="agents" class="sales-examples sales-wrap">
    <div class="sales-section-heading"><div><p class="sales-kicker">ลองเริ่มจากแนวทางที่ใช่</p><h2>{{ $agents['title'] }}</h2></div><p>{{ $agents['copy'] }}</p></div>
    <div class="sales-example-grid">
        @foreach ($agents['items'] ?? [] as $index => $item)
            <article class="sales-example">
                <button type="button" class="sales-example-select" x-on:click="example = {{ $index }}" :aria-pressed="(example === {{ $index }}).toString()" aria-controls="sales-example-prompt-{{ $index }}">
                    <span class="sales-example-image"><img src="{{ Vite::asset('resources/js/lab/assets/starters/'.$exampleImages[$index % count($exampleImages)].'.svg') }}" width="1600" height="1000" loading="lazy" alt="ภาพแนวทางเริ่มต้นสำหรับ {{ $item['title'] }}"></span>
                    <span class="sales-example-title">{{ $item['title'] }}<span class="sales-example-action">ดูแนวทาง</span></span>
                </button>
                <p>{{ $item['copy'] }}</p>
            </article>
        @endforeach
    </div>
    @foreach ($agents['items'] ?? [] as $index => $item)
        <div id="sales-example-prompt-{{ $index }}" class="sales-prompt-panel" x-show="example === {{ $index }}" @if ($index !== 0) x-cloak @endif>
            <div><p class="sales-mini-title">Prompt ตัวอย่าง · {{ $item['title'] }}</p><p class="sales-prompt-copy">{{ filled($item['prompt'] ?? null) ? $item['prompt'] : $item['copy'] }}</p><p class="sales-fine">ปรับรายละเอียดให้เป็นธุรกิจของคุณก่อนใช้ ภาพเป็นแนวทาง ไม่ใช่ผลลัพธ์ที่รับประกัน</p></div>
            <a class="sales-button sales-button-secondary" href="{{ auth()->check() ? route('lab') : $landing->guestEntryHref() }}">{{ auth()->check() ? 'เปิด Lab เพื่อเริ่มสร้าง' : 'เข้าสู่พื้นที่สร้างเว็บ' }}</a>
        </div>
    @endforeach
</section>

<section id="platform" class="sales-benefits">
    <div class="sales-wrap sales-benefits-layout">
        <div class="sales-benefits-heading"><p class="sales-kicker">{{ $platform['eyebrow'] }}</p><h2>{{ $platform['title'] }}</h2><p>{{ $platform['copy'] }}</p><a class="sales-text-link" href="#integrations">ดูสิ่งที่ใช้ได้และข้อจำกัด</a>
            @if (! empty($platform['stats']))
                <ul class="sales-facts">@foreach ($platform['stats'] as $stat)<li>{{ $stat }}</li>@endforeach</ul>
            @endif
        </div>
        <div class="sales-benefit-list">
            @foreach ($platform['layers'] ?? [] as $layer)
                <article>
                    <span class="sales-benefit-icon" aria-hidden="true"><krikkit:icon name="{{ ['squares-2x2', 'adjustments-horizontal', 'code-bracket', 'circle-stack'][$loop->index % 4] }}" class="size-6" /></span>
                    <div><h3>{{ $layer['title'] }}</h3><p>{{ $layer['copy'] }}</p></div>
                </article>
            @endforeach
        </div>
        @if (filled($platform['quote'] ?? null) && ! $legacySampleQuote)
            <figure class="sales-operator-proof"><blockquote>{{ $platform['quote'] }}</blockquote><figcaption>{{ $platform['quote_name'] ?? '' }}@if (filled($platform['quote_role'] ?? null)) · {{ $platform['quote_role'] }}@endif</figcaption></figure>
        @endif
    </div>
</section>

<section id="walkthrough" class="sales-workflow sales-wrap">
    <div class="sales-section-heading"><div><p class="sales-kicker">{{ $walk['eyebrow'] }}</p><h2>{{ $walk['title'] }}</h2></div><p>{{ $walk['copy'] }}</p></div>
    <ol class="sales-step-list">
        @foreach ($walk['tabs'] ?? [] as $index => $step)
            <li><span class="sales-step-number">{{ $index + 1 }}</span><p class="sales-mini-title">{{ $step['label'] }}</p><h3>{{ $step['title'] }}</h3><p>{{ $step['copy'] }}</p>
                @if (filled($step['image'] ?? null))
                    <img src="{{ $landing->mediaUrl($step['image']) }}" width="600" height="400" loading="lazy" alt="{{ $step['title'] }}">
                @endif
            </li>
        @endforeach
    </ol>
</section>

<section id="integrations" class="sales-readiness sales-wrap">
    <div class="sales-section-heading"><div><p class="sales-kicker">{{ $integrations['eyebrow'] }}</p><h2>{{ $integrations['title'] }}</h2></div><p>เว็บไซต์สวยเป็นจุดเริ่มต้น การบันทึกข้อมูลและการรับเงินต้องมีระบบที่เชื่อมและทดสอบแล้ว</p></div>
    <dl class="sales-capability-list">
        @foreach ($features as $feature)
            <div data-capability="{{ $feature['key'] }}" data-status="{{ $feature['status'] }}">
                <dt><span>{{ $feature['title'] }}</span><span class="sales-status sales-status-{{ $feature['status'] }}">{{ $feature['label'] }}</span></dt>
                <dd>{{ $feature['limitations'] }}</dd>
            </div>
        @endforeach
    </dl>
</section>

<section id="pricing" class="sales-pricing sales-wrap">
    <div><p class="sales-kicker">แพ็กเกจที่เหมาะกับจังหวะของคุณ</p><h2>เริ่มจากงานที่ต้องการ<br>แล้วเลือกขอบเขตให้พอดี</h2><p>เปรียบเทียบแพ็กเกจที่เปิดขาย เลือกเครดิต จำนวนโปรเจกต์ และสิทธิ์ให้เหมาะกับงานของคุณ</p><p class="sales-fine">ค่าแพลตฟอร์มแยกจากค่าบริการ AI ฐานข้อมูล และบริการภายนอก ตรวจเงื่อนไขก่อนซื้อ</p></div>
    <div class="sales-pricing-actions"><a class="sales-button" href="{{ $landing->href($integrations['cta_href'] ?? '', route('pricing')) }}">{{ $integrations['cta_label'] }}</a><a class="sales-text-link" href="{{ route('contact') }}">ให้เราช่วยดูว่างานนี้เหมาะไหม</a></div>
</section>
