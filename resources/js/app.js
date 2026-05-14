import Alpine from 'alpinejs'

function parseOgTags(html) {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const meta = (prop) => doc.querySelector(`meta[property="${prop}"]`)?.content
        || doc.querySelector(`meta[name="${prop}"]`)?.content || null;

    let price = null;
    const priceStr = meta('product:price:amount');
    if (priceStr) {
        price = parseFloat(priceStr);
    } else {
        const match = html.match(/(\d+[.,]\d{2})\s*€/);
        if (match) price = parseFloat(match[1].replace(',', '.'));
    }

    // JSON-LD Product
    doc.querySelectorAll('script[type="application/ld+json"]').forEach(script => {
        try {
            const data = JSON.parse(script.textContent);
            const product = data['@type'] === 'Product' ? data
                : data['@graph']?.find(i => i['@type'] === 'Product') || null;
            if (product) {
                if (!price && product.offers?.price) price = parseFloat(product.offers.price);
            }
        } catch {}
    });

    return {
        title: meta('og:title') || doc.querySelector('title')?.textContent?.replace(/\s*[-|][^-|]+$/, '') || null,
        price,
        image_url: meta('og:image') || null,
        description: (meta('og:description') || meta('description') || '').substring(0, 300) || null,
    };
}

window.scrapeWithFallback = async function(url, csrfToken) {
    // 1. Try server-side
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

    // 2. Fallback: CORS proxy from client browser
    const proxies = [
        (u) => `https://corsproxy.io/?${encodeURIComponent(u)}`,
        (u) => `https://api.allorigins.win/raw?url=${encodeURIComponent(u)}`,
    ];

    for (const proxy of proxies) {
        try {
            const res = await fetch(proxy(url), { signal: AbortSignal.timeout(8000) });
            if (!res.ok) continue;
            const html = await res.text();
            if (html.length < 1000) continue;
            const parsed = parseOgTags(html);
            if (parsed.title || parsed.image_url) return { ...parsed, clean_url: url };
        } catch {}
    }

    return { title: null, price: null, image_url: null, description: null, clean_url: url };
};

window.Alpine = Alpine
Alpine.start()
