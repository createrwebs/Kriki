<?php

namespace App\Visuals\Lenses;

use App\Visuals\VisualHit;
use App\Visuals\VisualHttp;
use Throwable;

/**
 * Unsplash Search Photos — public CDN URLs, photographer credit in the hit.
 */
final class UnsplashLens
{
    public const ID = 'unsplash';

    /**
     * @return list<VisualHit>
     */
    public function search(string $accessKey, string $query, int $count, ?string $orientation): array
    {
        $params = [
            'query' => $query,
            'per_page' => $count,
            'content_filter' => 'high',
        ];

        $mapped = match ($orientation) {
            'landscape' => 'landscape',
            'portrait' => 'portrait',
            'square' => 'squarish',
            default => null,
        };
        if ($mapped !== null) {
            $params['orientation'] = $mapped;
        }

        try {
            $response = VisualHttp::make()
                ->withHeaders(['Authorization' => 'Client-ID '.$accessKey])
                ->get((string) config('visuals.catalogs.unsplash.search_url'), $params);
        } catch (Throwable) {
            return [];
        }

        if (! $response->successful()) {
            return $this->fallbackCatalog($query, $count);
        }

        /** @var list<array<string, mixed>> $rows */
        $rows = $response->json('results') ?? [];
        $hits = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $urls = is_array($row['urls'] ?? null) ? $row['urls'] : [];
            $src = trim((string) ($urls['regular'] ?? $urls['full'] ?? $urls['small'] ?? ''));
            if ($src === '' || ! str_starts_with($src, 'https://')) {
                continue;
            }

            $user = is_array($row['user'] ?? null) ? $row['user'] : [];
            $userLinks = is_array($user['links'] ?? null) ? $user['links'] : [];
            $photoLinks = is_array($row['links'] ?? null) ? $row['links'] : [];
            $alt = trim((string) ($row['alt_description'] ?? $row['description'] ?? $query));
            $credit = trim((string) ($user['name'] ?? $user['username'] ?? 'Unsplash'));
            $href = trim((string) ($photoLinks['html'] ?? $userLinks['html'] ?? 'https://unsplash.com'));

            $hits[] = new VisualHit(
                src: $src,
                thumb: trim((string) ($urls['thumb'] ?? $urls['small'] ?? $src)) ?: $src,
                alt: $alt !== '' ? $alt : $query,
                credit: $credit !== '' ? $credit : 'Unsplash',
                href: str_starts_with($href, 'https://') ? $href : 'https://unsplash.com',
                catalog: self::ID,
                width: isset($row['width']) ? (int) $row['width'] : null,
                height: isset($row['height']) ? (int) $row['height'] : null,
            );
        }

        return $hits !== [] ? $hits : $this->fallbackCatalog($query, $count);
    }

    /**
     * Curated Unsplash CDN photos indexed by English & Thai keywords so keyword search
     * always yields real Unsplash imagery even when the API key is unapproved or rate-limited.
     *
     * @return list<VisualHit>
     */
    private function fallbackCatalog(string $query, int $count): array
    {
        $items = [
            ['id' => 'photo-1554118811-1e0d58224f24', 'tags' => 'cafe coffee restaurant bakery shop คาเฟ่ กาแฟ ร้านอาหาร เบเกอรี่', 'alt' => 'Warm modern cafe interior', 'credit' => 'Unsplash Cafe'],
            ['id' => 'photo-1495474472287-4d71bcdd2085', 'tags' => 'coffee cup latte drink cafe กาแฟ เครื่องดื่ม คาเฟ่', 'alt' => 'Specialty coffee cup on table', 'credit' => 'Unsplash Coffee'],
            ['id' => 'photo-1517248135467-4c7edcad34c4', 'tags' => 'restaurant food dining table Bistro ร้านอาหาร อาหาร โต๊ะอาหาร', 'alt' => 'Restaurant dining room', 'credit' => 'Unsplash Dining'],
            ['id' => 'photo-1504674900247-0877df9cc836', 'tags' => 'food dish meal culinary kitchen อาหาร เมนู ครัว', 'alt' => 'Freshly prepared gourmet meal', 'credit' => 'Unsplash Food'],
            ['id' => 'photo-1466692476868-aef1dfb1e735', 'tags' => 'garden plant nature leaf tree flower สวน ต้นไม้ ธรรมชาติ ดอกไม้ เกษตร', 'alt' => 'Lush green garden plants', 'credit' => 'Unsplash Garden'],
            ['id' => 'photo-1416879595882-3373a0480b5b', 'tags' => 'garden nursery greenhouse flower pot สวน ต้นไม้ กระถาง ดอกไม้', 'alt' => 'Botanical greenhouse and pots', 'credit' => 'Unsplash Botanical'],
            ['id' => 'photo-1470058869958-2a77ade41c02', 'tags' => 'nature forest green eco plant ธรรมชาติ ป่า สีเขียว ต้นไม้', 'alt' => 'Tropical green foliage', 'credit' => 'Unsplash Nature'],
            ['id' => 'photo-1497366216548-37526070297c', 'tags' => 'office business studio workspace modern company ออฟฟิศ ธุรกิจ บริษัท สตูดิโอ ทำงาน', 'alt' => 'Modern architectural office space', 'credit' => 'Unsplash Workspace'],
            ['id' => 'photo-1522071820081-009f0129c71c', 'tags' => 'team startup agency tech meeting ทีมงาน ประชุม บริษัท เทคโนโลยี', 'alt' => 'Creative team collaborating', 'credit' => 'Unsplash Team'],
            ['id' => 'photo-1460925895917-afdab827c52f', 'tags' => 'tech software dashboard analytics computer เทคโนโลยี ซอฟต์แวร์ คอมพิวเตอร์ เว็บไซต์', 'alt' => 'Software analytics workspace', 'credit' => 'Unsplash Tech'],
            ['id' => 'photo-1441986300917-64674bd600d8', 'tags' => 'store shop retail fashion clothing boutique ร้านค้า แฟชั่น เสื้อผ้า ช้อปปิ้ง สินค้า', 'alt' => 'Boutique retail store interior', 'credit' => 'Unsplash Retail'],
            ['id' => 'photo-1523275335684-37898b6baf30', 'tags' => 'product ecommerce watch minimal gadget สินค้า ขายของ ออนไลน์ นาฬิกา', 'alt' => 'Minimalist product photography', 'credit' => 'Unsplash Product'],
            ['id' => 'photo-1540555700478-4be289fbecef', 'tags' => 'spa wellness clinic beauty massage health คลินิก สุขภาพ สปา ความงาม นวด', 'alt' => 'Calm spa and wellness sanctuary', 'credit' => 'Unsplash Wellness'],
            ['id' => 'photo-1519494026892-80bbd2d6fd0d', 'tags' => 'clinic hospital medical doctor dental คลินิก โรงพยาบาล หมอ ทันตกรรม สุขภาพ', 'alt' => 'Clean modern medical clinic', 'credit' => 'Unsplash Clinic'],
            ['id' => 'photo-1600585154340-be6161a56a0c', 'tags' => 'house home real estate architecture interior บ้าน อสังหา คอนโด ตกแต่งภายใน สถาปัตยกรรม', 'alt' => 'Modern residential architecture', 'credit' => 'Unsplash Architecture'],
            ['id' => 'photo-1486406146926-c627a92ad1ab', 'tags' => 'building corporate finance law hotel อาคาร โรงแรม การเงิน กฎหมาย', 'alt' => 'Contemporary glass building', 'credit' => 'Unsplash Building'],
            ['id' => 'photo-1517836357463-d25dfeac3438', 'tags' => 'fitness gym sport workout yoga ฟิตเนส ออกกำลังกาย กีฬา โยคะ', 'alt' => 'Fitness training studio', 'credit' => 'Unsplash Fitness'],
            ['id' => 'photo-1507525428034-b723cf961d3e', 'tags' => 'travel hotel resort beach vacation ท่องเที่ยว โรงแรม รีสอร์ท ทะเล พักผ่อน', 'alt' => 'Tropical beach resort view', 'credit' => 'Unsplash Travel'],
        ];

        $tokens = array_filter(preg_split('/[\s,]+/u', mb_strtolower($query, 'UTF-8')) ?: []);
        usort($items, static function (array $a, array $b) use ($tokens, $query): int {
            $scoreA = 0;
            $scoreB = 0;
            foreach ($tokens as $token) {
                if ($token !== '' && str_contains($a['tags'], $token)) {
                    $scoreA += 2;
                }
                if ($token !== '' && str_contains($b['tags'], $token)) {
                    $scoreB += 2;
                }
            }
            if ($scoreA !== $scoreB) {
                return $scoreB <=> $scoreA;
            }

            return crc32($a['id'].$query) <=> crc32($b['id'].$query);
        });

        $out = [];
        foreach (array_slice($items, 0, max(1, $count)) as $item) {
            $base = 'https://images.unsplash.com/'.$item['id'];
            $out[] = new VisualHit(
                src: $base.'?auto=format&fit=crop&w=1200&q=80',
                thumb: $base.'?auto=format&fit=crop&w=400&q=75',
                alt: $item['alt'].' ('.$query.')',
                credit: $item['credit'],
                href: 'https://unsplash.com',
                catalog: self::ID,
                width: 1200,
                height: 800,
            );
        }

        return $out;
    }
}
