import Alpine from 'alpinejs'

const SCRAPE_WORKER = 'https://giftswipe-scraper.vassilidevnet.workers.dev';

window.scrapeWithFallback = async function(url, csrfToken) {
    // 1. Try server-side (works when server IP isn't blocked)
    try {
        const res = await fetch('/api/scrape-url', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ url }),
        });
        const data = await res.json();
        if (data.title || data.image_url) return data;
        if (data.clean_url) url = data.clean_url;
    } catch {}

    // 2. Fallback: Cloudflare Worker (different IP, bypasses blocks)
    try {
        const res = await fetch(SCRAPE_WORKER, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ url }),
            signal: AbortSignal.timeout(12000),
        });
        if (res.ok) {
            const data = await res.json();
            if (data.title || data.image_url) return { ...data, clean_url: url };
        }
    } catch {}

    return { title: null, price: null, image_url: null, description: null, clean_url: url };
};

window.Alpine = Alpine
Alpine.start()
