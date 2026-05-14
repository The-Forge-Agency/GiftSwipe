# Charte Graphique — Giftswipe

> App #02/52 — Sprint Factory
> Swipe, vote, offrez — le cadeau parfait sans la galère.

---

## Logo

Le logo Giftswipe combine un cadeau (gift box) avec des flèches de swipe (droite/gauche), symbolisant le mécanisme Tinder-like de l'app. Un coeur subtil est intégré dans le cadeau pour rappeler l'aspect affectif du geste.

### Versions disponibles

| Fichier | Usage |
|---------|-------|
| `SVG/logo-square.svg` | App icon, favicon, réseaux sociaux (fond Confetti Pink) |
| `SVG/logo-square-dark.svg` | Version dark mode (fond Charcoal) |
| `SVG/logo-horizontal.svg` | Header web, signature email |
| `SVG/favicon.svg` | Favicon navigateur |

### Règles d'utilisation
- Toujours garder un espace minimum de 1/4 de la largeur du logo autour
- Ne jamais déformer, incliner ou modifier les couleurs
- Sur fond clair → version standard (fond pink)
- Sur fond sombre → version dark

---

## Palette de couleurs

### Couleurs principales

| Token | Nom | Hex | RGB | Usage |
|-------|-----|-----|-----|-------|
| `--bg` | Cream | `#FFF8F0` | 255, 248, 240 | Background principal |
| `--bg-alt` | Blush | `#FFF0EB` | 255, 240, 235 | Background secondaire, cartes |
| `--ink` | Charcoal | `#1A1A2E` | 26, 26, 46 | Texte principal, titres |
| `--ink-alt` | Slate | `#64607D` | 100, 96, 125 | Texte secondaire, labels |
| `--accent` | Confetti Pink | `#FF6B8A` | 255, 107, 138 | Accent, CTA, liens, logo |

### Couleurs sémantiques (dérivées)

| Usage | Hex | Note |
|-------|-----|------|
| Swipe droite (oui) | `#4ADE80` | Vert validation |
| Swipe gauche (non) | `#F87171` | Rouge rejet |
| Cagnotte/argent | `#FBBF24` | Jaune doré |
| Succès | `#34D399` | Vert émeraude |
| Erreur | `#EF4444` | Rouge vif |

### CSS Variables

```css
:root {
  --bg: #FFF8F0;
  --bg-alt: #FFF0EB;
  --ink: #1A1A2E;
  --ink-alt: #64607D;
  --accent: #FF6B8A;
  --accent-hover: #FF5278;
  --swipe-yes: #4ADE80;
  --swipe-no: #F87171;
  --cagnotte: #FBBF24;
}
```

### Tailwind Config

```js
colors: {
  bg: '#FFF8F0',
  'bg-alt': '#FFF0EB',
  ink: '#1A1A2E',
  'ink-alt': '#64607D',
  accent: '#FF6B8A',
  'accent-hover': '#FF5278',
  'swipe-yes': '#4ADE80',
  'swipe-no': '#F87171',
  cagnotte: '#FBBF24',
}
```

---

## Typographie

### Fonts

| Usage | Font | Weight | Google Fonts |
|-------|------|--------|-------------|
| Titres, headings, logo | **Fredoka** | 500-600 | `@import url('https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap')` |
| Corps de texte, UI | **Inter** | 400-600 | `@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap')` |

### Échelle typographique

| Élément | Font | Taille | Weight | Line height |
|---------|------|--------|--------|-------------|
| H1 | Fredoka | 36px / 2.25rem | 600 | 1.2 |
| H2 | Fredoka | 28px / 1.75rem | 600 | 1.3 |
| H3 | Fredoka | 22px / 1.375rem | 500 | 1.4 |
| Body | Inter | 16px / 1rem | 400 | 1.5 |
| Body small | Inter | 14px / 0.875rem | 400 | 1.5 |
| Caption | Inter | 12px / 0.75rem | 500 | 1.4 |
| Button | Inter | 16px / 1rem | 600 | 1 |

---

## Composants UI clés

### Swipe Card
- Fond : `--bg-alt` (#FFF0EB)
- Border radius : 20px
- Shadow : `0 4px 24px rgba(26, 26, 46, 0.08)`
- Animations : rotation ±15° au swipe, scale 0.95 → 1.05
- Badges swipe : cercle vert (droite) / cercle rouge (gauche)

### Boutons
- Primary : fond `--accent`, texte blanc, radius 12px, padding 12px 24px
- Secondary : fond blanc, border `--accent`, texte `--accent`, radius 12px
- Ghost : pas de fond, texte `--accent`, underline au hover

### Wishlist Item
- Carte blanche, radius 16px, image à gauche, texte à droite
- Prix en `--accent` bold
- Indicateur de votes (barre de progression en `--accent`)

### Cagnotte
- Barre de progression : fond `--bg-alt`, remplissage gradient `--accent` → `--cagnotte`
- Montant total en Fredoka 600
- Participants : avatars empilés (stack)

---

## Ton de communication

- **Fun, complice et un peu taquin** — comme le pote qui organise tout mais en rigolant
- On tutoie toujours
- On utilise des emojis avec parcimonie (🎁 🎉 💸 pas plus)
- Jamais moralisateur ou corporate
- Les messages d'erreur sont humains : "Oups, ça a pas marché" pas "Erreur 500"

### Exemples

| Contexte | Bon | Mauvais |
|----------|-----|---------|
| Wishlist vide | "Ajoute ce dont tu rêves — tes amis feront le reste 🎁" | "Veuillez ajouter des articles à votre liste de souhaits" |
| Vote terminé | "Le verdict est tombé ! 🎉" | "Le processus de vote est terminé" |
| Cagnotte complète | "La cagnotte est pleine ! Le cadeau est dans la poche 💸" | "Objectif de cagnotte atteint à 100%" |

---

## Réseaux sociaux — Formats

| Plateforme | Format | Dimensions |
|-----------|--------|------------|
| Instagram Post | Carré | 1080 x 1080 |
| Instagram Story | Vertical | 1080 x 1920 |
| Instagram Reel | Vertical | 1080 x 1920 |
| LinkedIn Post | Paysage | 1200 x 627 |
| Twitter/X Header | Paysage | 1500 x 500 |
| OG Image | Paysage | 1200 x 630 |

### Templates visuels
- Fond principal : `--bg` (Cream #FFF8F0)
- Texte : `--ink` (Charcoal #1A1A2E)
- Accents : `--accent` (Confetti Pink #FF6B8A)
- Logo toujours visible en haut à gauche ou centré
- Hashtags en `--ink-alt` : #02/52 #BuildInPublic #52Apps
