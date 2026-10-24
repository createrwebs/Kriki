<div class="space-y-8">
    <div>
        <h1 class="text-lg font-semibold tracking-tight text-krikkit-fg">{{ __('dashboard.Landing page') }}</h1>
        <p class="mt-0.5 text-xs text-krikkit-muted">
            {{ __('dashboard.Copy, links, buttons, and images on the public home page.') }}
        </p>
    </div>

    <section class="rounded-xl border border-krikkit-line bg-krikkit-soft p-5">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div><h2 class="text-sm font-semibold text-krikkit-fg">หน้าขายโฉมใหม่ ภาษาไทย</h2><p class="mt-1 text-sm text-krikkit-muted">รูปแบบหน้าเว็บอัปเดตแล้ว ข้อความที่คุณตั้งไว้จะไม่ถูกแทนที่จนกว่าจะยืนยัน</p></div>
            <div class="flex flex-wrap gap-3"><a href="{{ route('features') }}" target="_blank" rel="noopener" class="text-sm underline">ดูหน้าผลิตภัณฑ์</a><krikkit:button type="button" variant="outline" wire:click="previewSalesCopy">ตรวจชุดข้อความใหม่</krikkit:button></div>
        </div>
        @if ($copyProposalOpen)
            <div class="mt-5 border-t border-krikkit-line pt-5">
                <p class="text-sm font-semibold text-krikkit-fg">{{ $proposedCopy['hero']['title'] }}</p>
                <p class="mt-2 text-sm text-krikkit-muted">{{ $proposedCopy['hero']['copy'] }}</p>
                <p class="mt-3 text-sm text-krikkit-muted">การยืนยันจะแทนข้อความและลิงก์ทุกส่วนด้วยค่าเริ่มต้นชุดใหม่ ภาพที่อัปโหลดไว้ยังอยู่ และเก็บสำเนาข้อความเดิมไว้หนึ่งชุด หากยังต้องการข้อความเดิม ให้กดยกเลิก</p>
                <div class="mt-4 divide-y divide-krikkit-line">
                    @php($sectionLabels = ['header' => 'ส่วนหัวและเมนู', 'hero' => 'ข้อความเปิดหน้า', 'problem' => 'บทนำ', 'platform' => 'ประโยชน์และข้อมูลสรุป', 'agents' => 'ตัวอย่างและ prompt', 'walkthrough' => 'ขั้นตอนการทำงาน', 'integrations' => 'ความสามารถ', 'faq' => 'คำถามที่พบบ่อย', 'cta' => 'ปุ่มเริ่มต้น', 'footer' => 'ส่วนท้ายและลิงก์'])
                    @foreach ($copyComparison as $sectionKey => $comparison)
                        <details class="py-3"><summary class="cursor-pointer text-sm font-medium text-krikkit-fg">เปรียบเทียบ: {{ $sectionLabels[$sectionKey] }}</summary><div class="mt-3 grid gap-5 sm:grid-cols-2">
                            @foreach (['current' => 'ข้อความและลิงก์ปัจจุบัน', 'proposed' => 'ชุดใหม่ที่จะนำมาใช้'] as $side => $sideLabel)
                                <div class="rounded-lg border border-krikkit-line p-3"><h3 class="mb-2 text-sm font-semibold text-krikkit-fg">{{ $sideLabel }}</h3><ul class="space-y-1 text-sm text-krikkit-muted">@foreach ($comparison[$side] as $copyValue)<li class="break-words">{{ $copyValue }}</li>@endforeach</ul></div>
                            @endforeach
                        </div></details>
                    @endforeach
                </div>
                <div class="mt-4 flex flex-wrap gap-3"><krikkit:button type="button" wire:click="applySalesCopy" wire:loading.attr="disabled">ยืนยันใช้ข้อความชุดใหม่</krikkit:button><krikkit:button type="button" variant="outline" wire:click="$set('copyProposalOpen', false)">ยกเลิก</krikkit:button></div>
            </div>
        @endif
    </section>

    <nav class="-mx-4 flex gap-1 overflow-x-auto border-b border-krikkit-line px-4 pb-px sm:-mx-5 sm:px-5 lg:mx-0 lg:flex-wrap lg:overflow-visible lg:px-0" aria-label="{{ __('dashboard.Landing page') }}">
        @foreach ($tabs as $key => $label)
            <button
                type="button"
                wire:click="selectTab('{{ $key }}')"
                @class([
                    '-mb-px shrink-0 border-b px-3 py-2 text-sm font-medium transition',
                    'border-krikkit-fg text-krikkit-fg' => $tab === $key,
                    'border-transparent text-krikkit-muted hover:text-krikkit-fg' => $tab !== $key,
                ])
            >{{ $label }}</button>
        @endforeach
    </nav>

    <form wire:submit="save" class="space-y-10">
        @include('livewire.dashboard.landing.options.'.$tab, ['hrefHint' => $hrefHint, 'asciiPreview' => $asciiPreview])

        <div class="flex justify-end">
            <krikkit:button type="submit" wire:loading.attr="disabled">
                {{ __('dashboard.Save changes') }}
            </krikkit:button>
        </div>
    </form>
</div>
