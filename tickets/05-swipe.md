# 05 — Rejoindre & Swipe
**Statut** : FAIT (2026-05-14)

## Objectif
Implementer le flow participant : formulaire prenom + budget, puis interface swipe Tinder-like avec animations touch/souris.

## Fichiers

| Action | Fichier |
|---|---|
| Modifier | `app/Http/Controllers/SwipeController.php` |
| Creer | `app/Http/Requests/JoinEventRequest.php` |
| Creer | `app/Http/Requests/StoreSwipeRequest.php` |
| Creer | `resources/views/swipe/index.blade.php` |

## Taches

1. `SwipeController@join` : creer Participant, stocker en session, redirect swipe
2. `SwipeController@index` : si pas de participant → form, si fini → results, sinon → swipe deck
3. `SwipeController@store` : creer Swipe (JSON), marquer finished si derniere idee
4. `JoinEventRequest` : name required max:30, budget_max nullable numeric min:0
5. `StoreSwipeRequest` : gift_idea_id required exists, liked required boolean
6. Vue swipe : 2 etats (form participant / swipe deck Alpine.js)
7. Swipe deck Alpine `swipeManager()` :
   - Drag touch + mouse events
   - Rotation ±15° proportionnelle
   - Overlays vert/rouge
   - Boutons controle (❌ / ✓)
   - POST AJAX chaque swipe
   - Redirect resultats apres derniere carte

## Criteres d'acceptation

- [ ] Formulaire participant affiche si pas en session
- [ ] Swipe tactile fonctionne sur mobile
- [ ] Swipe souris fonctionne sur desktop
- [ ] Boutons controle fonctionnent
- [ ] Swipes enregistres en DB
- [ ] Redirect resultats apres derniere carte
- [ ] Pas de double swipe possible
