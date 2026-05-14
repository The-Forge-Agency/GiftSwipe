<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UrlScraperService
{
    /**
     * @return array{title: ?string, price: ?float, image_url: ?string, description: ?string}
     */
    public function scrape(string $url): array
    {
        $response = Http::timeout(5)
            ->withUserAgent('Mozilla/5.0 (compatible; GiftSwipe/1.0)')
            ->get($url);

        if (! $response->successful()) {
            return ['title' => null, 'price' => null, 'image_url' => null, 'description' => null];
        }

        $html = $response->body();

        libxml_use_internal_errors(true);
        $doc = new \DOMDocument;
        $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
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
        if (! empty($jsonLd['name'])) {
            return trim($jsonLd['name']);
        }

        $og = $xpath->query('//meta[@property="og:title"]/@content');
        if ($og->length > 0) {
            return trim($og->item(0)->nodeValue);
        }

        $title = $xpath->query('//title');
        if ($title->length > 0) {
            return trim($title->item(0)->textContent);
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

        return null;
    }

    /**
     * @param array<string, mixed> $jsonLd
     */
    private function extractDescription(\DOMXPath $xpath, array $jsonLd): ?string
    {
        if (! empty($jsonLd['description'])) {
            return mb_substr(trim($jsonLd['description']), 0, 300);
        }

        $og = $xpath->query('//meta[@property="og:description"]/@content');
        if ($og->length > 0) {
            return mb_substr(trim($og->item(0)->nodeValue), 0, 300);
        }

        $meta = $xpath->query('//meta[@name="description"]/@content');
        if ($meta->length > 0) {
            return mb_substr(trim($meta->item(0)->nodeValue), 0, 300);
        }

        return null;
    }
}
