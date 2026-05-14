# 01 — Fondation & Design System
**Statut** : FAIT (2026-05-14)

## Objectif
Configurer Tailwind CSS 4 avec les design tokens GiftSwipe (couleurs, fonts, radius), installer Alpine.js, copier les assets dans `public/images/`, creer le layout Blade principal.

## Fichiers

| Action | Fichier |
|---|---|
| Modifier | `resources/css/app.css` |
| Modifier | `resources/js/app.js` |
| Modifier | `vite.config.js` |
| Modifier | `package.json` (npm install alpinejs) |
| Creer | `resources/views/layouts/app.blade.php` |
| Copier | `assets/SVG/logo-horizontal.svg` → `public/images/logo-horizontal.svg` |
| Copier | `assets/SVG/favicon.svg` → `public/images/favicon.svg` |
| Copier | `assets/PNG/favicon.png` → `public/images/favicon.png` |
| Supprimer | `resources/views/welcome.blade.php` |

## Taches

1. `npm install alpinejs` dans GiftSwipeApp/
2. Configurer Alpine.js dans `resources/js/app.js`
3. Modifier `vite.config.js` : remplacer Instrument Sans par Fredoka (400,500,600,700) + Inter (400,500,600)
4. Configurer les design tokens dans `resources/css/app.css` (Tailwind v4 inline theme) :
   - Couleurs : bg `#FFF8F0`, bg-alt `#FFF0EB`, ink `#1A1A2E`, ink-alt `#64607D`, accent `#FF6B8A`, accent-hover `#FF5278`, swipe-yes `#4ADE80`, swipe-no `#F87171`, cagnotte `#FBBF24`
   - Fonts : Fredoka (titres, `font-title`) + Inter (body, `font-body`)
5. Creer `resources/views/layouts/app.blade.php` : HTML5 fr, meta viewport, csrf, favicon, Vite, header logo centre, main @yield, footer Sprint Factory
6. Copier les assets SVG/PNG dans `public/images/`
7. Supprimer `welcome.blade.php`

## Criteres d'acceptation

- [ ] Page avec fond cream (#FFF8F0), header logo, footer
- [ ] Fonts Fredoka + Inter chargees
- [ ] Alpine.version dans la console retourne une version
- [ ] Logo accessible a `/images/logo-horizontal.svg`
- [ ] `npm run build` sans erreur
