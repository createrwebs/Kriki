<?php

namespace App\Support\Site;

use App\Support\Content\PublicIndex;
use Illuminate\Support\Facades\Storage;

final class LandingCopy
{
    public function __construct(private readonly SiteSettings $site) {}

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'header' => [
                'links' => [
                    ['label' => 'ทำอะไรได้', 'href' => '#platform'],
                    ['label' => 'ตัวอย่าง', 'href' => '#agents'],
                    ['label' => 'วิธีเริ่มต้น', 'href' => '#walkthrough'],
                    ['label' => 'แพ็กเกจ', 'href' => 'pricing'],
                    ['label' => 'คำถามที่พบบ่อย', 'href' => '#questions'],
                ],
                'guest_link_label' => 'เข้าสู่ระบบ', 'guest_link_href' => 'login',
                'guest_cta_label' => 'เริ่มสร้างเว็บ', 'guest_cta_href' => '',
                'auth_link_label' => 'โปรเจกต์ของฉัน', 'auth_cta_label' => 'เปิดพื้นที่สร้างเว็บ',
                'mobile_cta_label' => 'เริ่มสร้างเว็บ', 'mobile_cta_href' => '',
            ],
            'hero' => [
                'eyebrow' => 'พื้นที่สร้างเว็บด้วย AI ของคุณ',
                'title' => 'ไอเดียของคุณ เป็นเว็บที่แก้ต่อได้จริง',
                'copy' => 'อธิบายเป็นภาษาไทย เลือกแนวทางที่ชอบ แล้วค่อย ๆ ปรับหน้าเว็บ เนื้อหา และฟอร์มในพื้นที่เดียว ไม่ต้องเริ่มทุกอย่างจากหน้าว่าง',
                'primary_label' => 'เริ่มสร้างเว็บของคุณ', 'primary_href' => '',
                'secondary_label' => 'ดูตัวอย่างที่เริ่มได้', 'secondary_href' => '#agents',
                'footnote' => 'ตรวจผลงานก่อนเผยแพร่ · ความสามารถและเครดิตขึ้นอยู่กับแพ็กเกจ',
                'preview_label' => 'จากคำอธิบาย สู่ภาพที่จับต้องได้',
                'preview_prompt' => 'สร้างเว็บไซต์สตูดิโอออกแบบ โทนเรียบ มีผลงานเด่น เรื่องราวของทีม และหน้าติดต่อ ปรับให้ใช้บนมือถือได้',
                'preview_note' => 'ภาพแนวทางเริ่มต้น ไม่ใช่ระบบร้านค้าที่เปิดใช้งานแล้ว',
            ],
            'problem' => [
                'title' => 'เริ่มจากสิ่งที่อยากทำ ไม่ใช่สิ่งที่ต้องเรียนรู้ทั้งหมด',
                'copy' => 'Krikkit รวมคำสั่ง ไฟล์ และตัวอย่างเว็บไว้ด้วยกัน คุณจึงตรวจสิ่งที่ AI ทำ และคุยเพื่อปรับต่อเป็นส่วน ๆ ได้ โดยยังมีโค้ดให้แก้ไขต่อ',
            ],
            'platform' => [
                'eyebrow' => 'ออกแบบให้ค่อย ๆ ไปต่อได้',
                'title' => 'ไม่ได้จบแค่ภาพสวย แต่มีงานให้คุณต่อยอด',
                'copy' => 'เห็นทิศทางก่อนเริ่ม เลือกสิ่งที่ต้องการ และเก็บการทำงานไว้ในโปรเจกต์เดียว',
                'layers' => [
                    ['title' => 'มีตัวอย่างให้เริ่ม ไม่ต้องเดาจากหน้าว่าง', 'copy' => 'เลือกแนวเว็บไซต์หรือ dashboard แล้วปรับโทนสี ความหนาแน่น และรายละเอียดก่อนส่งให้ AI สร้าง'],
                    ['title' => 'แก้ทีละหน้า ทีละส่วน', 'copy' => 'กำหนดหน้าที่ต้องการและช่องข้อมูลใน brief จากนั้นตรวจและสั่งแก้เนื้อหา โครงหน้า หรือองค์ประกอบต่อได้'],
                    ['title' => 'โค้ดอยู่ในโปรเจกต์ของคุณ', 'copy' => 'ดูไฟล์ที่ AI สร้าง เปรียบเทียบกับ preview และนำไปพัฒนาต่อได้ตามความสามารถของแพ็กเกจ'],
                    ['title' => 'เชื่อมข้อมูลเมื่อคุณพร้อม', 'copy' => 'ต่อยอดฟอร์มจองผ่านบัญชี Supabase ที่เชื่อมไว้ โดยต้องตั้งค่าฐานข้อมูลและทดสอบสิทธิ์ก่อนรับข้อมูลจริง'],
                ],
                'quote' => '', 'quote_name' => '', 'quote_role' => '',
                'stats' => ['ตัวอย่างก่อนเริ่ม', 'ไฟล์ที่แก้ต่อได้', 'หลายหน้าในโปรเจกต์เดียว'],
            ],
            'agents' => [
                'title' => 'เว็บแบบไหน ที่คุณอยากเริ่มวันนี้?',
                'copy' => 'เลือกดูแนวทางพร้อม prompt ตัวอย่าง ภาพเหล่านี้เป็น starter preview ไม่ใช่คำรับประกันผลลัพธ์หรือระบบที่เชื่อมข้อมูลเสร็จแล้ว',
                'items' => [
                    ['title' => 'เว็บไซต์ธุรกิจ', 'copy' => 'แนะนำบริการ ผลงาน และช่องทางติดต่อ ให้คนรู้จักธุรกิจในภาพเดียว', 'prompt' => 'สร้างเว็บไซต์สตูดิโอออกแบบ โทนเรียบ มีหน้าแรก บริการ ผลงาน และติดต่อ ใช้ภาษาไทยและรองรับมือถือ'],
                    ['title' => 'ร้านค้าและแคตตาล็อก', 'copy' => 'จัดหมวดสินค้าและหน้ารายละเอียด เริ่มจากหน้าร้านก่อนต่อระบบออเดอร์ในอนาคต', 'prompt' => 'สร้างเว็บแคตตาล็อกของแต่งบ้านโทนอบอุ่น มีหมวดสินค้า ตัวกรอง และหน้ารายละเอียด ระบุชัดว่ายังไม่เชื่อมรับชำระเงินจริง'],
                    ['title' => 'Dashboard และงานบริการ', 'copy' => 'วางหน้ารายการ ลูกค้า และช่องข้อมูล เช่น งานซ่อมรถ พร้อมระบุฐานข้อมูลที่ต้องเชื่อม', 'prompt' => 'ออกแบบ dashboard อู่ซ่อมรถ มีหน้าลูกค้า รถ ใบงานซ่อม และอะไหล่ ระบุช่องทะเบียนรถ อาการ และสถานะงาน เริ่มจาก UI พร้อมแสดงสิ่งที่ต้องเชื่อมฐานข้อมูล'],
                ],
            ],
            'walkthrough' => [
                'eyebrow' => 'เส้นทางจากไอเดียถึงงานชิ้นแรก',
                'title' => 'คุณเป็นคนกำหนดทิศทาง AI ช่วยลงมือ',
                'copy' => 'ไม่ต้องสั่งให้สมบูรณ์ตั้งแต่ครั้งแรก เริ่มจากโครงที่ตรวจได้ แล้วเพิ่มรายละเอียดทีละขั้น',
                'ascii_source' => null,
                'tabs' => [
                    ['label' => 'เล่าสิ่งที่ต้องการ', 'title' => 'บอกธุรกิจ กลุ่มลูกค้า และเป้าหมาย', 'copy' => 'ใช้ภาษาที่คุ้นเคย หรือเริ่มจาก prompt ตัวอย่างแล้วเปลี่ยนให้เป็นธุรกิจของคุณ', 'image' => ''],
                    ['label' => 'เลือกหน้าและรายละเอียด', 'title' => 'กำหนดขอบเขตก่อนเริ่มสร้าง', 'copy' => 'เลือกหน้า โทนสี และช่องข้อมูลที่จำเป็น ฟอร์มที่บันทึกข้อมูลต้องเชื่อมระบบเพิ่มเติม', 'image' => ''],
                    ['label' => 'ดูและปรับงาน', 'title' => 'ตรวจ preview ควบคู่กับไฟล์จริง', 'copy' => 'แก้เฉพาะจุดที่ยังไม่ใช่ ทั้งข้อความ หน้าตา และการทำงาน แล้วทดสอบปุ่มและหน้าต่าง ๆ', 'image' => ''],
                    ['label' => 'ทดสอบก่อนนำขึ้น', 'title' => 'ตรวจให้ครบก่อนส่งถึงลูกค้า', 'copy' => 'เช็กมือถือ ลิงก์ ฟอร์ม สิทธิ์ข้อมูล และ build จากนั้นใช้ช่องทางเผยแพร่ที่ตั้งค่าไว้', 'image' => ''],
                ],
            ],
            'integrations' => [
                'eyebrow' => 'ความสามารถที่ต้องรู้ก่อนเริ่ม',
                'title' => 'เลือกใช้ให้ตรงงาน รู้ข้อจำกัดก่อนตัดสินใจ',
                'cta_label' => 'ดูรายละเอียดแพ็กเกจ', 'cta_href' => 'pricing',
            ],
            'faq' => [
                'title' => 'ก่อนเริ่ม คุณอาจอยากรู้',
                'items' => [
                    ['q' => 'ไม่เขียนโค้ด เริ่มได้ไหม?', 'a' => 'เริ่มจากอธิบายสิ่งที่ต้องการและเลือกตัวอย่างได้ แต่ควรตรวจผลงานและทดสอบก่อนเผยแพร่ งานเชื่อมฐานข้อมูลหรือระบบเฉพาะทางอาจต้องมีผู้พัฒนาช่วยตั้งค่า'],
                    ['q' => 'สร้างเว็บหลายหน้าได้หรือเปล่า?', 'a' => 'กำหนดหลายหน้าในโปรเจกต์เดียวได้ เช่น หน้าแรก บริการ และติดต่อ ผลที่สร้างต้องทดสอบเส้นทางและการนำทางอีกครั้ง ไม่ใช่เพียงเปลี่ยนภาพในหน้าเดียว'],
                    ['q' => 'ฟอร์มที่ AI สร้างจะเก็บข้อมูลเลยไหม?', 'a' => 'ไม่เสมอไป ฟอร์มบนหน้าจอกับฟอร์มที่เชื่อมฐานข้อมูลเป็นคนละส่วน ระบบมีฟอร์มจองที่จัดการได้ แต่ต้องเชื่อม Supabase และเตรียมฐานข้อมูลก่อนใช้งานจริง'],
                    ['q' => 'ขายสินค้า รับ PromptPay และแนบสลิปได้แล้วไหม?', 'a' => 'ระบบร้านค้า ตะกร้า ออเดอร์ PromptPay และหลังบ้านตรวจสลิปยังอยู่ในแผนพัฒนา ภาพหน้าร้านตัวอย่างไม่ใช่ระบบชำระเงินพร้อมใช้งาน'],
                    ['q' => 'เอาโค้ดจาก GitHub มาใช้ได้ไหม?', 'a' => 'มีการเชื่อมและนำเข้า source จาก GitHub แต่ต้องตรวจภาษา framework dependency และทดสอบ build ไม่รับประกันว่า repository ทุกแบบจะทำงานได้'],
                    ['q' => 'ราคาและค่า AI คิดอย่างไร?', 'a' => 'ดูแพ็กเกจที่เปิดขายในหน้าราคา จำนวนโปรเจกต์ เครดิต และสิทธิ์ขึ้นอยู่กับแพ็กเกจ ค่า AI ฐานข้อมูลหรือบริการภายนอกให้ตรวจเงื่อนไขเพิ่มเติม ไม่ใช่ค่ารับชำระเงินของร้านค้า'],
                ],
            ],
            'cta' => [
                'copy' => 'เริ่มให้เห็นภาพ แล้วค่อยทำให้เป็นของคุณ',
                'primary_label' => 'เริ่มสร้างเว็บของคุณ', 'primary_href' => '',
                'guest_secondary_label' => 'คุยเรื่องการใช้งาน', 'guest_secondary_href' => 'contact',
            ],
            'footer' => [
                'blurb' => 'พื้นที่สร้างและต่อยอดเว็บไซต์ด้วย AI จากไอเดียภาษาไทย สู่ไฟล์ที่คุณตรวจและปรับต่อได้',
                'subscribe_placeholder' => 'อีเมลของคุณ', 'subscribe_label' => 'รับข่าวสาร',
                'columns' => [
                    ['heading' => 'ผลิตภัณฑ์', 'links' => [
                        ['label' => 'ทำอะไรได้', 'href' => '#platform'], ['label' => 'ตัวอย่างเริ่มต้น', 'href' => '#agents'],
                        ['label' => 'วิธีใช้งาน', 'href' => '#walkthrough'], ['label' => 'แพ็กเกจ', 'href' => 'pricing'],
                    ]],
                    ['heading' => 'ช่วยเหลือ', 'links' => [
                        ['label' => 'คำถามที่พบบ่อย', 'href' => '#questions'], ['label' => 'ติดต่อเรา', 'href' => 'contact'],
                        ['label' => 'เข้าสู่ระบบ', 'href' => 'login'],
                    ]],
                ],
                'legal_heading' => 'นโยบาย', 'legal_extra' => [], 'social' => [],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        $copy = $this->merge(self::defaults(), $this->site->bag('landing'));

        if (isset($copy['header']['links']) && is_array($copy['header']['links'])) {
            $copy['header']['links'] = $this->retargetPricingLinks($copy['header']['links']);
            $copy['header']['links'] = $this->retargetContactLinks($copy['header']['links']);
        }

        if (isset($copy['header']) && is_array($copy['header'])) {
            $copy['header'] = $this->normalizeGuestAuthButtons($copy['header']);
        }

        if (isset($copy['hero']) && is_array($copy['hero'])) {
            $copy['hero'] = $this->normalizeGuidedDemoLink($copy['hero']);
        }

        $ctaHref = trim((string) ($copy['cta']['guest_secondary_href'] ?? ''));
        if (in_array($ctaHref, ['login', '#start', ''], true)) {
            $copy['cta']['guest_secondary_href'] = 'contact';
        }

        foreach ($copy['footer']['columns'] ?? [] as $index => $column) {
            if (! is_array($column) || ! isset($column['links']) || ! is_array($column['links'])) {
                continue;
            }
            $copy['footer']['columns'][$index]['links'] = $this->retargetPricingLinks($column['links']);
            $copy['footer']['columns'][$index]['links'] = $this->retargetContactLinks($column['links']);
        }

        return $copy;
    }

    /**
     * @return array<string, mixed>
     */
    public function section(string $key): array
    {
        $all = $this->all();
        $section = $all[$key] ?? [];

        return is_array($section) ? $section : [];
    }

    public function href(?string $stored, string $fallback = '#'): string
    {
        $stored = trim((string) $stored);
        if ($stored === '') {
            return $fallback;
        }

        return match ($stored) {
            'lab' => route('lab'),
            'dashboard' => route('dashboard.home'),
            'login' => route('login'),
            'register' => route('register'),
            'home' => route('home'),
            'pricing' => route('pricing'),
            '#pricing' => route('pricing'),
            'contact' => route('contact'),
            'blog' => $this->blogHref(),
            default => $this->safeUrl($stored) ? $stored : $fallback,
        };
    }

    private function safeUrl(string $url): bool
    {
        if (preg_match('/[\x00-\x20\x7f\\\\]/', $url)) {
            return false;
        }
        if (str_starts_with($url, '#')) {
            return true;
        }
        if (str_starts_with($url, '/')) {
            return ! str_starts_with($url, '//');
        }

        return (bool) preg_match('/^(https?:\/\/|mailto:|tel:)/i', $url);
    }

    public function guestEntryHref(): string
    {
        return $this->site->allowRegistration() ? route('register') : route('login');
    }

    public function mediaUrl(?string $value, ?string $fallback = null): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return (string) $fallback;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, '/')) {
            if (! $this->safeUrl($value)) {
                return (string) $fallback;
            }
            return $value;
        }

        if (str_contains($value, ':') || str_contains($value, '..') || str_contains($value, '\\')) {
            return (string) $fallback;
        }

        return Storage::disk('public')->url($value);
    }

    public function asciiUrl(): string
    {
        $walk = $this->section('walkthrough');

        return $this->mediaUrl(
            is_string($walk['ascii_source'] ?? null) ? $walk['ascii_source'] : null,
            asset('images/home/ascii-source.png'),
        );
    }

    /**
     * @return list<array{label: string, title: string, copy: string, image: string}>
     */
    public function walkTabs(): array
    {
        $tabs = $this->section('walkthrough')['tabs'] ?? [];
        if (! is_array($tabs)) {
            return [];
        }

        $out = [];
        foreach ($tabs as $tab) {
            if (! is_array($tab)) {
                continue;
            }
            $out[] = [
                'label' => (string) ($tab['label'] ?? ''),
                'title' => (string) ($tab['title'] ?? ''),
                'copy' => (string) ($tab['copy'] ?? ''),
                'image' => $this->mediaUrl(
                    is_string($tab['image'] ?? null) ? $tab['image'] : null,
                    '',
                ),
            ];
        }

        return $out;
    }

    /**
     * @return list<array{label: string, href: string}>
     */
    public function headerLinks(bool $hasBlog): array
    {
        $links = $this->section('header')['links'] ?? [];
        if (! is_array($links)) {
            return [];
        }

        $out = [];
        foreach ($links as $link) {
            if (! is_array($link)) {
                continue;
            }
            $href = trim((string) ($link['href'] ?? ''));
            if ($href === 'blog' && ! $hasBlog) {
                $href = '#questions';
            }
            $out[] = [
                'label' => (string) ($link['label'] ?? ''),
                'href' => $this->href($href, '#'),
            ];
        }

        return $out;
    }

    private function blogHref(): string
    {
        return PublicIndex::blogIsLive()
            ? route('blog')
            : '#questions';
    }

    /**
     * Saved landing copy still used Contact / Join waitlist as the guest header actions.
     *
     * @param  array<string, mixed>  $header
     * @return array<string, mixed>
     */
    private function normalizeGuestAuthButtons(array $header): array
    {
        $linkLabel = trim((string) ($header['guest_link_label'] ?? ''));
        $linkHref = trim((string) ($header['guest_link_href'] ?? ''));
        if (strcasecmp($linkLabel, 'Contact') === 0 && in_array($linkHref, ['contact', 'login', 'register', ''], true)) {
            $header['guest_link_label'] = 'Log in';
            $header['guest_link_href'] = 'login';
        }

        $ctaLabel = trim((string) ($header['guest_cta_label'] ?? ''));
        $ctaHref = trim((string) ($header['guest_cta_href'] ?? ''));
        if (strcasecmp($ctaLabel, 'Join waitlist') === 0 && in_array($ctaHref, ['', 'register', 'login', '#start', 'contact'], true)) {
            $header['guest_cta_label'] = 'Register';
            $header['guest_cta_href'] = 'register';
        }

        $mobileLabel = trim((string) ($header['mobile_cta_label'] ?? ''));
        $mobileHref = trim((string) ($header['mobile_cta_href'] ?? ''));
        if (strcasecmp($mobileLabel, 'Book a demo') === 0 && in_array($mobileHref, ['', 'login', 'register', '#start'], true)) {
            $header['mobile_cta_href'] = 'contact';
        }

        return $header;
    }

    /**
     * Saved landing copy still pointed the guided demo CTA at the walkthrough.
     *
     * @param  array<string, mixed>  $hero
     * @return array<string, mixed>
     */
    private function normalizeGuidedDemoLink(array $hero): array
    {
        $label = trim((string) ($hero['secondary_label'] ?? ''));
        $href = trim((string) ($hero['secondary_href'] ?? ''));
        if (
            strcasecmp($label, 'Book a guided demo') === 0
            && in_array($href, ['#walkthrough', '#questions', '#start', 'login', 'register', ''], true)
        ) {
            $hero['secondary_href'] = 'contact';
        }

        return $hero;
    }

    /**
     * Saved landing copy still pointed Contact at login.
     *
     * @param  list<mixed>  $links
     * @return list<mixed>
     */
    private function retargetContactLinks(array $links): array
    {
        foreach ($links as $index => $link) {
            if (! is_array($link)) {
                continue;
            }
            if (strcasecmp((string) ($link['label'] ?? ''), 'Contact') !== 0) {
                continue;
            }

            $href = trim((string) ($link['href'] ?? ''));
            if (in_array($href, ['login', 'register', '#start', ''], true)) {
                $links[$index]['href'] = 'contact';
            }
        }

        return $links;
    }

    /**
     * Saved landing copy still pointed Pricing at the waitlist CTA.
     *
     * @param  list<mixed>  $links
     * @return list<mixed>
     */
    private function retargetPricingLinks(array $links): array
    {
        foreach ($links as $index => $link) {
            if (! is_array($link)) {
                continue;
            }
            if (strcasecmp((string) ($link['label'] ?? ''), 'Pricing') !== 0) {
                continue;
            }

            $href = trim((string) ($link['href'] ?? ''));
            if (in_array($href, ['#pricing', '#start', ''], true)) {
                $links[$index]['href'] = 'pricing';
            }
        }

        return $links;
    }

    /**
     * @param  array<string, mixed>  $base
     * @param  array<string, mixed>  $over
     * @return array<string, mixed>
     */
    private function merge(array $base, array $over): array
    {
        foreach ($over as $key => $value) {
            if (is_array($value) && isset($base[$key]) && is_array($base[$key]) && $this->associative($base[$key])) {
                $base[$key] = $this->merge($base[$key], $value);

                continue;
            }

            $base[$key] = $value;
        }

        return $base;
    }

    /**
     * @param  array<mixed>  $value
     */
    private function associative(array $value): bool
    {
        return $value !== [] && array_keys($value) !== range(0, count($value) - 1);
    }
}
