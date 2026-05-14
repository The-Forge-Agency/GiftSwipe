# Brief Dev — Giftswipe (App #02/52)

> Ce document contient TOUT ce dont tu as besoin pour comprendre et développer Giftswipe.
> Tu gères le back, le front, le deploy. Stack libre, résultat non négociable.

---

## Le problème qu'on résout

Organiser un cadeau de groupe entre amis, c'est toujours la même galère :

1. **Quelqu'un** (toujours la même personne) crée un groupe Messenger
2. **3-4 personnes** balancent des liens Amazon/Etsy/Fnac dans le chat
3. **Tout le monde** répond "ah oui trop bien" à tout sans vraiment choisir
4. **Personne** ne tranche, personne ne sait qui met combien
5. **La veille de l'anniv**, la personne qui galère depuis le début craque et achète un truc nul en urgence
6. **Résultat** : un mug "Best Friend" et une bougie à 15€

Le vrai problème n'est pas le manque de bonne volonté — c'est que Messenger/WhatsApp ne sont pas faits pour prendre une décision de groupe. Il manque un mécanisme de vote simple et rapide.

---

## Notre solution : Giftswipe

Une app web **gratuite**, accessible par **lien partagé** (zéro compte, zéro inscription), où :

- L'organisateur crée un événement en 30 secondes
- Il ajoute des idées cadeaux avec liens et prix
- Il partage UN lien sur le groupe Messenger/WhatsApp
- Les participants cliquent, entrent leur prénom + budget, et **swipent** sur les idées (comme Tinder)
- Le top cadeau sort automatiquement avec le budget total du groupe

**Temps total** : 2 minutes au lieu d'une semaine de messages.

---

## Cible

- Groupes d'amis **18-35 ans**
- Le persona principal = **l'organisateur/organisatrice** (souvent la même personne dans chaque groupe d'amis)
- Usage mobile dominant (le lien est partagé sur Messenger/WhatsApp, ouvert sur téléphone)

---

## Philosophie produit (même ADN que WhoseTurn)

| Règle | Détail |
|-------|--------|
| **100% gratuit** | Pas de paywall, pas de limite, pas de freemium, pas d'abo |
| **Zéro inscription** | Pas de login, pas d'email, pas de mot de passe. Accès uniquement par lien partagé |
| **Mobile-first** | 90% des users arrivent depuis un lien Messenger sur téléphone |
| **PWA** | Installable sur l'écran d'accueil sans passer par l'app store |
| **Pas de paiement dans l'app** | Pas de Stripe, pas de cagnotte intégrée. Les gens utilisent Lydia/PayPal entre eux |

---

## Features MVP — 6 features, pas une de plus

### Feature 1 : Créer un événement
- L'organisateur arrive sur la landing, clique "Créer un événement"
- Il entre : **prénom de la personne qui fête son anniv** + **date de l'anniv**
- Un lien unique est généré (`giftswipe.app/abc123`)
- L'organisateur est redirigé vers la page de gestion de l'événement

### Feature 2 : Ajouter les participants
- L'organisateur peut ajouter des prénoms manuellement (optionnel)
- OU les participants s'ajoutent eux-mêmes en arrivant via le lien (ils entrent juste leur prénom)

### Feature 3 : Ajouter des idées cadeaux
- L'organisateur (et potentiellement les participants) peut ajouter des idées
- Chaque idée = **nom du cadeau** + **lien URL** (optionnel) + **prix estimé**
- Les idées s'affichent comme des cartes visuelles

### Feature 4 : Chaque participant entre son budget max
- Avant de commencer à swiper, le participant entre son budget max (ex: "25€")
- Ce budget est affiché dans la vue résultat (budget total du groupe = somme des budgets individuels)

### Feature 5 : Swipe Tinder-like
- Chaque participant voit les idées cadeaux une par une en plein écran (format carte)
- **Swipe droite** = j'aime cette idée
- **Swipe gauche** = non merci
- Animation fluide (Framer Motion recommandé)
- La carte affiche : nom du cadeau, prix, lien (cliquable), image si disponible
- Le swipe doit fonctionner **au doigt sur mobile** ET à la souris sur desktop

### Feature 6 : Vue résultat
- Une fois que tous les participants ont swipé (ou après un timer/seuil configurable par l'organisateur)
- Affiche : **top cadeau** (le plus swipé à droite) + **répartition des voix** par idée
- Affiche le **budget total du groupe** (somme des budgets individuels)
- Indique si le budget total couvre le prix du top cadeau
- L'organisateur voit les résultats en **temps réel** (Supabase Realtime)

---

## Hors MVP — ON NE FAIT PAS ÇA CETTE SEMAINE

- Cagnotte intégrée (Lydia/PayPal suffit)
- Notifications/rappels
- Suggestions de cadeaux par IA
- Scraping de sites e-commerce
- Calendrier d'anniversaires récurrents
- Système de comptes/profils persistants

---

## Parcours utilisateur détaillé

### L'organisateur :
```
Landing page
    → Clique "Créer un événement"
    → Entre prénom de la personne + date anniv
    → Redirigé vers la page événement
    → Ajoute des idées cadeaux (nom + lien + prix)
    → Copie le lien partageable
    → L'envoie sur Messenger/WhatsApp
    → Voit les résultats arriver en temps réel
```

### Un participant :
```
Reçoit le lien sur Messenger/WhatsApp
    → Clique le lien
    → Arrive sur la page événement
    → Entre son prénom + son budget max
    → Voit les idées cadeaux une par une
    → Swipe droite (oui) ou gauche (non) sur chaque idée
    → Voit le résultat final (top cadeau + budget total)
```

---

## Architecture technique suggérée

Tu es libre sur la stack, mais voici ce qu'on recommande :

### Stack
- **Next.js** (App Router) — SSR pour la landing, client pour le swipe
- **TypeScript** strict
- **Tailwind CSS v4** + **shadcn/ui** pour les composants
- **Supabase** (PostgreSQL + Realtime)
- **Framer Motion** pour les animations de swipe
- **Vercel** pour le déploiement

### Modèle de données (suggestion)

```
events
├── id (uuid)
├── slug (string unique, pour l'URL)
├── birthday_person_name (string)
├── birthday_date (date)
├── created_at (timestamp)

gift_ideas
├── id (uuid)
├── event_id (fk → events)
├── name (string)
├── url (string, nullable)
├── price (number, nullable)
├── added_by (string — prénom)
├── created_at (timestamp)

participants
├── id (uuid)
├── event_id (fk → events)
├── name (string)
├── budget_max (number)
├── has_finished_swiping (boolean)
├── created_at (timestamp)

swipes
├── id (uuid)
├── participant_id (fk → participants)
├── gift_idea_id (fk → gift_ideas)
├── direction (enum: 'left' | 'right')
├── created_at (timestamp)
```

### Temps réel
- Utiliser **Supabase Realtime** pour que l'organisateur voie les votes arriver en live
- Subscribe sur la table `swipes` filtré par `event_id`

### Lien partagé
- Générer un **slug court** (6-8 caractères alphanumériques) à la création de l'événement
- URL format : `giftswipe.app/{slug}`
- Pas d'auth, pas de session. Le slug EST le token d'accès

---

## Branding & Design Tokens

### Palette (STRICTE — rien d'autre)

| Token | Nom | Hex | Usage |
|-------|-----|-----|-------|
| `--bg` | Cream | `#FFF8F0` | Background principal |
| `--bg-alt` | Blush | `#FFF0EB` | Background cartes, sections secondaires |
| `--ink` | Charcoal | `#1A1A2E` | Texte principal, titres |
| `--ink-alt` | Slate | `#64607D` | Texte secondaire, labels, placeholders |
| `--accent` | Confetti Pink | `#FF6B8A` | Boutons, CTA, liens, logo, swipe droite |

### Couleurs sémantiques

| Usage | Hex |
|-------|-----|
| Swipe droite (oui) | `#4ADE80` (vert) |
| Swipe gauche (non) | `#F87171` (rouge) |
| Succès | `#34D399` |
| Erreur | `#EF4444` |

### Fonts

| Usage | Font | Google Fonts |
|-------|------|-------------|
| Titres, headings, logo | **Fredoka** (500-600) | `family=Fredoka:wght@400;500;600;700` |
| Corps de texte, UI | **Inter** (400-600) | `family=Inter:wght@400;500;600` |

### Règles visuelles strictes

- **Jamais de hex en dur** dans les composants → tout passe par les tokens CSS
- **Jamais de fontFamily en dur** → tout passe par les tokens
- **Coins arrondis généreux** : cards 16-20px, boutons 12px, inputs 8px
- **Ombres légères** : `0 4px 24px rgba(26, 26, 46, 0.08)`
- **Mobile-first** : tout design commence à 375px
- **Animations fluides** : transitions 200-300ms, ease-out

---

## UX — Règles non négociables

1. **Zéro friction à l'entrée** : un participant clique le lien et swipe en moins de 30 secondes
2. **Pas de formulaire long** : prénom + budget = 2 champs max avant de commencer
3. **Le swipe doit être satisfaisant** : animation rotation ±15°, feedback visuel (vert/rouge), momentum naturel
4. **Vue résultat claire** : le top cadeau doit sauter aux yeux en 1 seconde
5. **États vides gérés** : pas d'idée cadeau → message fun ("Ajoute une première idée !"), pas de participant → "Partage le lien pour que tes amis votent"

---

## Ton de l'app

**Fun, complice et un peu taquin** — comme le pote qui organise tout mais en rigolant.

| Contexte | Bon | Mauvais |
|----------|-----|---------|
| Événement créé | "C'est parti ! Partage le lien et laisse la magie opérer" | "Votre événement a été créé avec succès" |
| Aucune idée encore | "Pas d'idée ? C'est le moment de stalker leur Insta" | "Aucune idée cadeau n'a été ajoutée" |
| Swipe terminé | "T'as voté ! Plus qu'à attendre les autres" | "Vos votes ont été enregistrés" |
| Résultat final | "Le peuple a parlé ! Le top cadeau est..." | "Résultat du vote : l'article X a obtenu le plus de votes" |

---

## Git workflow

- **Une feature = une branche** (`dev/feature-swipe`, `dev/feature-results`, etc.)
- Jamais coder directement sur `main`
- Push après chaque commit
- Merge après validation

---

## Checklist avant deploy

- [ ] `npm run build` passe sans erreur
- [ ] `npm run lint` passe
- [ ] Zéro `any` TypeScript
- [ ] Couleurs = tokens uniquement (pas de hex en dur)
- [ ] Fonts = tokens uniquement
- [ ] Responsive vérifié sur mobile (375px min)
- [ ] Parcours complet fonctionne : créer événement → ajouter idées → partager lien → swipe → résultat
- [ ] Swipe fonctionne au doigt sur mobile
- [ ] Supabase Realtime fonctionne (résultats en temps réel)
- [ ] États vides gérés
- [ ] PWA installable
- [ ] Deploy Vercel OK

---

## Assets disponibles

Tous les assets sont dans le dossier `assets/` à côté de ce fichier :

```
vassili/assets/
├── SVG/
│   ├── logo-square.svg          — Logo carré (fond pink)
│   ├── logo-square-dark.svg     — Logo carré (fond dark)
│   ├── logo-horizontal.svg      — Logo horizontal avec texte
│   └── favicon.svg              — Favicon
├── PNG/
│   ├── logo-square.png
│   ├── logo-square-dark.png
│   ├── logo-horizontal.png
│   └── favicon.png
└── charte-graphique.md          — Charte graphique complète
```
