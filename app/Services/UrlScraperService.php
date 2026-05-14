<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UrlScraperService
{
    public static function cleanUrl(string $url): string
    {
        // Amazon: keep only /dp/ASIN
        if (preg_match('#(https?://(?:www\.)?amazon\.[a-z.]+/.*?/dp/[A-Z0-9]+)#i', $url, $m)) {
            return $m[1];
        }

        // Strip common tracking params
        $parsed = parse_url($url);
        if (! isset($parsed['query'])) {
            return $url;
        }

        parse_str($parsed['query'], $params);
        $junk = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
            'fbclid', 'gclid', 'ref', 'ref_', 'dib', 'dib_tag', 'crid', 'qid',
            'sprefix', 'sr', 'keywords', 'th', 'psc'];
        $params = array_diff_key($params, array_flip($junk));

        $clean = $parsed['scheme'] . '://' . $parsed['host'] . ($parsed['path'] ?? '/');
        if ($params) {
            $clean .= '?' . http_build_query($params);
        }

        return $clean;
    }

    private const USER_AGENTS = [
        'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)',
        'Twitterbot/1.0',
        'Slackbot-LinkExpanding 1.0 (+https://api.slack.com/robots)',
        'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
    ];

    /**
     * @return array{title: ?string, price: ?float, image_url: ?string, description: ?string, clean_url: ?string, debug?: array}
     */
    public function scrape(string $url): array
    {
        $cleanUrl = self::cleanUrl($url);
        $debug = [];

        foreach (self::USER_AGENTS as $ua) {
            $result = $this->scrapeDirect($cleanUrl, $ua, $debug);

            if ($result['title'] || $result['image_url']) {
                return [...$result, 'clean_url' => $cleanUrl];
            }
        }

        return ['title' => null, 'price' => null, 'image_url' => null, 'description' => null, 'clean_url' => $cleanUrl, 'debug' => $debug];
    }

    /**
     * @return array{title: ?string, price: ?float, image_url: ?string, description: ?string}
     */
    /**
     * @param array<int, array<string, mixed>> $debug
     */
    private function scrapeDirect(string $url, string $userAgent, array &$debug = []): array
    {
        $empty = ['title' => null, 'price' => null, 'image_url' => null, 'description' => null];
        $uaShort = substr($userAgent, 0, 30);

        try {
            $response = Http::timeout(8)
                ->connectTimeout(4)
                ->withUserAgent($userAgent)
                ->withHeaders(['Accept-Language' => 'fr-FR,fr;q=0.9'])
                ->get($url);
        } catch (\Exception $e) {
            $debug[] = ['ua' => $uaShort, 'error' => get_class($e).': '.$e->getMessage()];

            return $empty;
        }

        $status = $response->status();
        $bodyLen = strlen($response->body());
        $debug[] = ['ua' => $uaShort, 'status' => $status, 'body_length' => $bodyLen];

        if (! $response->successful()) {
            return $empty;
        }

        $html = $response->body();

        libxml_use_internal_errors(true);
        $doc = new \DOMDocument;
        @$doc->loadHTML('<?xml encoding="UTF-8">'.$html);
        libxml_clear_errors();
        $xpath = new \DOMXPath($doc);

        $jsonLd = $this->parseJsonLd($html);

        return [
            'title' => $this->extractTitle($xpath, $jsonLd),
            'price' => $this->extractPrice($xpath, $jsonLd, $html),
            'image_url' => $this->extractImage($xpath, $jsonLd),
            'description' => $this->extractDescription($xpath, $jsonLd),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function parseJsonLd(string $html): array
    {
        if (preg_match_all('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/si', $html, $matches)) {
            foreach ($matches[1] as $json) {
                $data = json_decode(trim($json), true);
                if (! is_array($data)) {
                    continue;
                }

                if (isset($data['@graph'])) {
                    foreach ($data['@graph'] as $item) {
                        if (isset($item['@type']) && in_array($item['@type'], ['Product', 'IndividualProduct'])) {
                            return $item;
                        }
                    }
                }

                if (isset($data['@type']) && in_array($data['@type'], ['Product', 'IndividualProduct'])) {
                    return $data;
                }
            }
        }

        return [];
    }

    /**
     * @param array<string, mixed> $jsonLd
     */
    private function extractTitle(\DOMXPath $xpath, array $jsonLd): ?string
    {
        $og = $xpath->query('//meta[@property="og:title"]/@content');
        $ogTitle = $og->length > 0 ? html_entity_decode(trim($og->item(0)->nodeValue), ENT_QUOTES, 'UTF-8') : null;

        $jsonLdName = ! empty($jsonLd['name']) ? html_entity_decode(trim($jsonLd['name']), ENT_QUOTES, 'UTF-8') : null;

        // Pick the longest between OG and JSON-LD — longer = more descriptive
        if ($ogTitle && $jsonLdName) {
            return mb_strlen($ogTitle) >= mb_strlen($jsonLdName) ? $ogTitle : $jsonLdName;
        }

        if ($ogTitle) {
            return $ogTitle;
        }

        if ($jsonLdName) {
            return $jsonLdName;
        }

        $title = $xpath->query('//title');
        if ($title->length > 0) {
            $text = trim($title->item(0)->textContent);
            $text = preg_replace('/\s*[-|]\s*[^-|]+$/', '', $text);

            return html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        }

        return null;
    }

    /**
     * @param array<string, mixed> $jsonLd
     */
    private function extractPrice(\DOMXPath $xpath, array $jsonLd, string $html): ?float
    {
        $price = $jsonLd['offers']['price']
            ?? $jsonLd['offers'][0]['price']
            ?? $jsonLd['offers']['lowPrice']
            ?? null;

        if ($price !== null) {
            return (float) $price;
        }

        $og = $xpath->query('//meta[@property="product:price:amount"]/@content');
        if ($og->length > 0) {
            return (float) $og->item(0)->nodeValue;
        }

        if (preg_match('/(\d+[\.,]\d{2})\s*€/', $html, $m)) {
            return (float) str_replace(',', '.', $m[1]);
        }

        return null;
    }

    /**
     * @param array<string, mixed> $jsonLd
     */
    private function extractImage(\DOMXPath $xpath, array $jsonLd): ?string
    {
        $image = $jsonLd['image'] ?? null;
        if (is_array($image)) {
            $image = $image[0] ?? $image['url'] ?? null;
        }
        if (is_string($image) && filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }

        $og = $xpath->query('//meta[@property="og:image"]/@content');
        if ($og->length > 0) {
            $url = trim($og->item(0)->nodeValue);
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                return $url;
            }
        }

        // Amazon-specific: look for main product image
        $landing = $xpath->query('//img[@id="landingImage"]/@src');
        if ($landing->length > 0) {
            return trim($landing->item(0)->nodeValue);
        }

        return null;
    }

    /**
     * @param array<string, mixed> $jsonLd
     */
    private function extractDescription(\DOMXPath $xpath, array $jsonLd): ?string
    {
        if (! empty($jsonLd['description'])) {
            return mb_substr(html_entity_decode(trim($jsonLd['description']), ENT_QUOTES, 'UTF-8'), 0, 300);
        }

        $og = $xpath->query('//meta[@property="og:description"]/@content');
        if ($og->length > 0) {
            return mb_substr(html_entity_decode(trim($og->item(0)->nodeValue), ENT_QUOTES, 'UTF-8'), 0, 300);
        }

        $meta = $xpath->query('//meta[@name="description"]/@content');
        if ($meta->length > 0) {
            return mb_substr(html_entity_decode(trim($meta->item(0)->nodeValue), ENT_QUOTES, 'UTF-8'), 0, 300);
        }

        return null;
    }
}
