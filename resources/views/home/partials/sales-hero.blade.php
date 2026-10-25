@php
    $hero = $landing->section('hero');
    $heroExamples = [
        ['label' => 'ธุรกิจและบริการ', 'image' => 'editorial-portfolio', 'prompt' => $hero['preview_prompt'] ?? 'สร้างเว็บไซต์ธุรกิจภาษาไทย พร้อมหน้าแรก บริการ และติดต่อ'],
        ['label' => 'ร้านค้า', 'image' => 'premium-store', 'prompt' => 'ออกแบบหน้าร้านที่สินค้าเป็นจุดเด่น พร้อมตะกร้าและหน้าชำระเงิน'],
        ['label' => 'งานบริการ', 'image' => 'auto-repair', 'prompt' => 'จัดระเบียบลูกค้า ใบงาน และสถานะงานบริการให้อยู่ในที่เดียว'],
    ];
@endphp
<section id="overview" class="sales-hero sales-wrap">
    <div class="sales-hero-copy">
        <p class="sales-kicker">{{ $hero['eyebrow'] }}</p>
        <h1>{{ $hero['title'] }}</h1>
        <p class="sales-lead">{{ $hero['copy'] }}</p>
        <div class="sales-actions">
            <a class="sales-button" href="{{ $landing->href($hero['primary_href'] ?? '', auth()->check() ? route('lab') : $landing->guestEntryHref()) }}">{{ $hero['primary_label'] }}</a>
            <a class="sales-text-link" href="{{ $landing->href($hero['secondary_href'] ?? '', '#agents') }}">{{ $hero['secondary_label'] }}</a>
        </div>
        <p class="sales-fine">{{ $hero['footnote'] }}</p>
    </div>
    <figure class="sales-workbench" data-ui x-data="{ selected: 0 }">
        <div class="sales-stage-heading"><span class="sales-desk-brand">พื้นที่เริ่มต้นของคุณ</span><span class="sales-preview-tag">ตัวอย่างแนวทาง</span></div>
        <div class="sales-stage-options" role="group" aria-label="เลือกตัวอย่างเว็บไซต์">
            @foreach ($heroExamples as $index => $example)
                <button type="button" x-on:click="selected = {{ $index }}" :aria-pressed="(selected === {{ $index }}).toString()" aria-controls="sales-hero-example-{{ $index }}">{{ $example['label'] }}</button>
            @endforeach
        </div>
        @foreach ($heroExamples as $index => $example)
            <div id="sales-hero-example-{{ $index }}" x-show="selected === {{ $index }}" @if ($index !== 0) x-cloak @endif>
                <div class="sales-preview-frame"><img src="{{ Vite::asset('resources/js/lab/assets/starters/'.$example['image'].'.svg') }}" width="1600" height="1000" @if ($index === 0) fetchpriority="high" @else loading="lazy" @endif alt="ภาพแนวทางเริ่มต้นสำหรับ{{ $example['label'] }}"></div>
                <div class="sales-brief"><krikkit:icon name="sparkles" class="size-5 shrink-0" /><div><span>{{ $hero['preview_label'] ?? 'เริ่มจากคำอธิบายของคุณ' }}</span><p>{{ $example['prompt'] }}</p></div></div>
            </div>
        @endforeach
        <figcaption>{{ $hero['preview_note'] ?? 'ภาพแนวทางเริ่มต้น ไม่ใช่ระบบที่เปิดใช้งานแล้ว' }}</figcaption>
    </figure>
</section>
