@php
    $site = app(\App\Support\Site\SiteSettings::class);
    $landing = app(\App\Support\Site\LandingCopy::class);
    $hasBlog = \App\Support\Content\PublicIndex::blogIsLive();
@endphp

<x-layouts.app>
    <div lang="th" x-data="{ menu: false, example: 0, faq: -1 }"
        x-on:keydown.escape.window="if (menu) { menu = false; $refs.menuButton.focus() }"
        class="krikkit-sales min-h-screen">
        @if ($localPresentationPreview ?? false)
            <p class="sales-local-note" role="note">โหมดดูหน้าตาในเครื่อง · ไม่เชื่อมข้อมูลลูกค้าและไม่ส่งฟอร์มจริง</p>
        @endif
        <a href="#main" class="sales-skip">ข้ามไปเนื้อหา</a>
        @include('home.partials.header')
        <main id="main">
            @include('home.partials.sales-hero')
            @include('home.partials.sales-sections')
            @include('home.partials.faq')
            <section id="start" class="sales-final sales-wrap">
                <div><p class="sales-kicker">พื้นที่สำหรับไอเดียถัดไปของคุณ</p><h2>{{ $landing->section('cta')['copy'] }}</h2></div>
                <div class="sales-actions">
                    <a class="sales-button" href="{{ $landing->href($landing->section('cta')['primary_href'] ?? '', auth()->check() ? route('lab') : $landing->guestEntryHref()) }}">{{ $landing->section('cta')['primary_label'] }}</a>
                    <a class="sales-text-link" href="{{ $landing->href($landing->section('cta')['guest_secondary_href'] ?? '', route('contact')) }}">{{ $landing->section('cta')['guest_secondary_label'] }}</a>
                </div>
            </section>
        </main>
        @include('home.partials.footer')
    </div>
</x-layouts.app>
