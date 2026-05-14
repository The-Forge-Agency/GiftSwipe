# 03 — Landing page + Routes
**Statut** : FAIT (2026-05-14)

## Objectif
Definir toutes les routes, creer les controllers (stubbes sauf landing), implementer la landing page complete (hero, probleme, comment ca marche, CTA) et la page de creation d'evenement.

## Fichiers

| Action | Fichier |
|---|---|
| Modifier | `routes/web.php` |
| Creer | `app/Http/Controllers/EventController.php` |
| Creer | `app/Http/Controllers/SwipeController.php` |
| Creer | `app/Http/Requests/StoreEventRequest.php` |
| Creer | `resources/views/landing.blade.php` |
| Creer | `resources/views/create.blade.php` |
| Creer | `resources/views/components/button.blade.php` |
| Creer | `resources/views/components/input.blade.php` |

## Taches

1. Routes dans `web.php` :
   - `GET /` → landing
   - `GET /create` → formulaire creation
   - `POST /create` → store event
   - `GET /{event:slug}` → page evenement
   - `POST /{event:slug}/gifts` → ajouter idee
   - `GET /{event:slug}/swipe` → page swipe
   - `POST /{event:slug}/swipe` → enregistrer swipe
   - `POST /{event:slug}/join` → rejoindre
   - `GET /{event:slug}/results` → resultats
2. `EventController` : index (landing), create (form), store (creer + redirect)
3. Stubber les autres methodes avec `abort(501)`
4. `StoreEventRequest` : birthday_person_name required max:50, birthday_date required date after_or_equal:today
5. Composant `<x-button>` : bg-accent text-white rounded-xl py-3 px-6 hover:bg-accent-hover
6. Composant `<x-input>` : bg-white border rounded-xl px-4 py-3 h-11 focus:ring-accent
7. Vue `landing.blade.php` : 4 sections (hero, probleme, comment ca marche en grille 2 cols, CTA)
8. Vue `create.blade.php` : form centre max-w-sm, 2 inputs + bouton

## Criteres d'acceptation

- [ ] Landing affichee avec 4 sections
- [ ] Formulaire creation fonctionne
- [ ] Validation erreurs affichees
- [ ] Responsive mobile/desktop
- [ ] Toutes les routes definies
