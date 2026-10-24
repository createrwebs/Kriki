@php($hero = $landing->section('hero'))
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
    <figure class="sales-workbench">
        <div class="sales-window-bar"><span class="sales-desk-brand">Krikkit Lab</span><span class="sales-window-caption">จากคำสั่งของคุณ สู่หน้าตาของโปรเจกต์</span><span class="sales-preview-tag">ภาพแนวทาง</span></div>
        <div class="sales-workbench-body">
            <div class="sales-desk-sidebar">
              <div class="sales-brief">
                <span class="sales-brief-mark" aria-hidden="true">K</span>
                <div><p class="sales-mini-title">{{ $hero['preview_label'] ?? 'เริ่มจากคำอธิบายของคุณ' }}</p><p>{{ $hero['preview_prompt'] ?? 'สร้างเว็บไซต์ธุรกิจภาษาไทย พร้อมหน้าแรก บริการ และติดต่อ' }}</p></div>
              </div>
              <div class="sales-desk-pages">
                <p class="sales-mini-title">โครงหน้าตัวอย่าง</p>
                <ul><li><krikkit:icon name="document-text" class="size-4" />หน้าแรก</li><li><krikkit:icon name="document-text" class="size-4" />ผลงานของเรา</li><li><krikkit:icon name="document-text" class="size-4" />ติดต่อ</li></ul>
              </div>
              <p class="sales-desk-guide">เริ่มจากภาพรวม<br>แล้วคุยเพื่อปรับทีละส่วน</p>
            </div>
            <div class="sales-preview-frame">
                <div class="sales-preview-tabs"><span><krikkit:icon name="eye" class="size-4" />ตัวอย่างหน้าเว็บ</span><span>studio / index</span></div>
                <img src="{{ Vite::asset('resources/js/lab/assets/starters/editorial-portfolio.svg') }}" width="1600" height="1000" fetchpriority="high" alt="ภาพประกอบแนวทางเว็บไซต์จาก starter: หัวเรื่องและผลงานแบบเรียบ ไม่ใช่ภาพระบบลูกค้าที่เปิดใช้งานแล้ว">
            </div>
        </div>
        <figcaption>{{ $hero['preview_note'] ?? 'ภาพแนวทางเริ่มต้น ไม่ใช่ระบบที่เปิดใช้งานแล้ว' }}</figcaption>
    </figure>
</section>
