# GiftSwipe

> App #02/52 — [The Forge Agency](https://the-forge.agency) sprint challenge

**GiftSwipe** est une web app pour organiser des cadeaux de groupe par vote (swipe) ou créer sa wishlist perso. Zéro inscription, accès uniquement par lien partagé.

## Le concept

Offrir un cadeau de groupe, c'est toujours le même bazar : 12 liens dans un chat, 4 avis différents, 0 décision. GiftSwipe résout ça en 3 étapes.

### Mode organisateur

- Crée un événement en 10 secondes (prénom + date d'anniversaire)
- Ajoute des idées cadeaux (avec auto-remplissage par URL : titre, prix, image, description)
- Partage le lien — tout le monde swipe les idées
- Le cadeau préféré sort des votes
- Cagnotte : chaque participant indique combien il met

### Mode wishlist

- Crée ta liste de souhaits perso
- Ajoute tes envies (colle une URL → les infos se remplissent toutes seules)
- 2 liens générés : un public (lecture seule) à partager, un privé (édition) à garder
- Tes proches peuvent créer un événement GiftSwipe directement depuis ta wishlist
- Retrouve tes espaces grâce au cookie "Mes espaces"

### Fonctionnalités

- Scraping intelligent (OG tags, JSON-LD) avec cascade de User-Agents (Facebook, Twitter, Slack, Google, Chrome)
- Nettoyage d'URL Amazon (supprime le tracking, garde le `/dp/ASIN`)
- Upload d'image depuis la galerie/caméra (pas seulement par URL)
- Images produit sur les cartes swipe et les résultats
- Mini-chat par événement (identité fixée par cookie)
- Cagnotte détaillée (qui met combien) + calcul prix/personne
- "Mes espaces" : retrouve tes wishlists et événements via cookie

### Scraping — architecture et limites

Le scraping utilise une stratégie en cascade pour contourner les protections anti-bot des sites e-commerce :

1. **Serveur Laravel** — 5 User-Agents en cascade (Facebook, Twitter, Slack, Google, Chrome). Fonctionne en local/dev. En production, les IP datacenter (Forge) se font bloquer (403 Cloudflare).

2. **Cloudflare Worker** (fallback) — Un worker gratuit (`giftswipe-scraper.vassilidevnet.workers.dev`) qui fetch la page depuis les IPs Cloudflare et parse OG tags + JSON-LD. Appelé directement depuis le navigateur du client quand le serveur échoue. 100K req/jour gratuits.

3. **Upload manuel** — Si tout échoue, l'utilisateur peut importer une image depuis sa galerie/caméra et remplir les infos à la main.

**Amazon résiste** : ils bloquent tous les bots, y compris Facebook, Google et les Workers Cloudflare. Les seules options seraient l'API Product Advertising d'Amazon (compte affilié requis) ou un service payant (ScraperAPI, ScrapingBee).

Les proxies CORS gratuits testés et abandonnés : corsproxy.io (payant hors localhost), allorigins (timeout), microlink (payant pour sites anti-bot), jsonlink (403).

## Stack technique

- **Backend** : Laravel 13 (PHP 8.5)
- **Frontend** : Blade + Tailwind CSS 4 + Alpine.js
- **Base de données** : SQLite
- **Scraping** : Cloudflare Worker + Http facade (OG, JSON-LD, 5 User-Agents)
- **Tests** : Pest v4 (35 tests)

## Build in public — Comment ce projet a été créé

Ce projet a été entièrement construit avec **Claude Code** (Claude Opus 4) en pair programming IA continu. Voici le process :

### Prompt initial

> "À l'aide du readme, analyse le brief, toutes les assets, fais-moi un plan ultra détaillé avec des tickets sous forme de .md [...] tu dois faire une application 100% fonctionnelle en Laravel, aucune feature n'est complexe, tout doit rester simple et efficace."

### Les étapes

1. **V1 — Flow de base**
   - Landing page + création d'événement
   - Ajout d'idées cadeaux
   - Système de swipe (Tinder-like avec Alpine.js)
   - Page résultats avec classement des votes
   - Système de participants par session

2. **V2 — Wishlist + Scraping + Messages + Cagnotte**
   - Images produit sur les gift ideas
   - Service de scraping URL (OG tags, JSON-LD, Amazon-specific)
   - Wishlist complète avec double slug (public/privé)
   - Fil de messages par événement (cookie-based)
   - Cagnotte engagements (budget par participant)
   - Landing page double usage
   - Création d'événement depuis une wishlist
   - "Mes espaces" avec cookie persistant

### Allers-retours IA notables

| Problème | Déclencheur | Solution |
|---|---|---|
| `composer.json` introuvable en deploy | Forge cherche composer.json à la racine du repo | `.git` déplacé dans `GiftSwipeApp/` pour que le repo = l'app Laravel |
| `realpath()` retourne `false` en prod | Le dossier `storage/framework/views` n'existe pas au deploy | Fallback `?: storage_path(...)` dans `config/view.php` |
| URLs Amazon > 500 chars | "The url field must not be greater than 500 characters" | Nettoyage d'URL (strip tracking) + limite à 2048 + migration colonnes |
| Scraping vide en prod (403) | IP datacenter Forge bloquée par Cloudflare/WAF | Cascade: serveur (5 UAs) → Cloudflare Worker (IPs non-datacenter) → upload manuel |
| Proxies CORS tous KO | corsproxy.io payant, allorigins timeout, microlink/jsonlink 403 | Cloudflare Worker gratuit déployé comme proxy scraping dédié |
| Amazon résiste à tout | Bloque même les bots Facebook/Google et les Workers CF | Accepté comme limite — l'utilisateur remplit à la main ou importe une image |
| Dates en anglais ("May") | "pk pas en francais ?" | `config/app.php` locale `'fr'` |
| Pas d'image custom possible | "on doit pouvoir mettre une image custom" | Champ `image_url` visible dans le form + preview |
| Migration FK échoue | `wishlist_items` créée avant `wishlists` (même timestamp) | Timestamp de migration décalé |
| Wishlist → event sans infos orga | "il faut demander le prénom de l'organisateur" | Form avec `organizer_name` + `birthday_date` sur la vue publique |
| User perd ses espaces | "si l'user retourne sur le site lui mettre un endroit" | Cookie `giftswipe_owner_token` + page "Mes espaces" |

### Prompts utilisateur clés

```
"on doit pouvoir mettre une image custom, une description aussi sur les cadeaux,
voir l'image automatiquement loaded ou l'importer nous meme tu vois ? :D"
```

```
"quand on clique depuis le lien d'une whishtlist il faut demander
la date de l'event choisi et le prénom de l'organisateur"
```

```
"il faut aussi deposer un cookie tout le temps et si l'user retourne
sur le site lui mettre un endroit pour retourner dans ses whistlist
(ou il a le droit de modifier) ou les organisation en cours"
```

## Installation

```bash
git clone git@github.com:The-Forge-Agency/GiftSwipe.git
cd GiftSwipe
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
composer dev
```

L'app tourne sur `http://localhost:8000`.

## Architecture

```
app/
├── Http/Controllers/
│   ├── Api/
│   │   └── ScrapeController.php    # POST /api/scrape-url (throttle:10,1)
│   ├── EventController.php         # CRUD événements, résultats, cagnotte
│   ├── MessageController.php       # Mini-chat par événement
│   ├── SwipeController.php         # Join + swipe (like/dislike)
│   └── WishlistController.php      # CRUD wishlist + items + mes espaces
├── Models/
│   ├── Event.php                   # slug auto-généré, owner_token
│   ├── GiftIdea.php                # nom, url, prix, image, description
│   ├── Message.php                 # author_token cookie-based
│   ├── Participant.php             # nom + budget_max (cagnotte)
│   ├── Swipe.php                   # liked (bool) par participant
│   ├── Wishlist.php                # double slug (8 public + 12 privé)
│   └── WishlistItem.php            # items de la wishlist
└── Services/
    └── UrlScraperService.php       # Scraping OG/JSON-LD + nettoyage URL
```

### Pages

| Route | Page |
|---|---|
| `/` | Landing — 2 CTAs (organiser / wishlist) |
| `/create` | Créer un événement |
| `/mes-espaces` | Retrouver ses wishlists et événements |
| `/wishlist/create` | Créer sa wishlist |
| `/wishlist/{slug}` | Wishlist publique (lecture seule) |
| `/wishlist/edit/{privateSlug}` | Wishlist privée (édition) |
| `/{slug}` | Page événement (idées + discussion) |
| `/{slug}/swipe` | Swipe les idées cadeaux |
| `/{slug}/results` | Résultats + cagnotte |

### Cookies

| Cookie | Durée | Usage |
|---|---|---|
| `giftswipe_owner_token` | 1 an | Identifie le créateur (wishlists + events) |
| `giftswipe_author_token` | 1 an | Identifie l'auteur des messages |

## Tests

```bash
php artisan test --compact
```

## Design System

- **Fond** : `#FFF8F0` (cream)
- **Fond alt** : `#FFF0EB`
- **Texte** : `#1A1A2E` (ink)
- **Accent** : `#FF6B8A` (pink)
- **Swipe oui** : `#4ADE80` (green)
- **Swipe non** : `#F87171` (red)
- **Fonts** : Fredoka (titres) + Inter (body)
- **Radius** : `rounded-2xl` partout

## Licence

MIT
