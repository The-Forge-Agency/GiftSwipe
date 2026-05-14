# 08 — Tests
**Statut** : FAIT (2026-05-14)

## Objectif
Tests Pest couvrant le parcours complet : creation evenement, ajout idees, join, swipe, resultats.

## Fichiers

| Action | Fichier |
|---|---|
| Creer | `tests/Feature/EventCreationTest.php` |
| Creer | `tests/Feature/GiftIdeaTest.php` |
| Creer | `tests/Feature/SwipeTest.php` |
| Creer | `tests/Feature/ResultsTest.php` |

## Taches

1. EventCreationTest : landing loads, create loads, can create event, validations, slug unique
2. GiftIdeaTest : event page loads, can add gift, validations, 404 invalid slug
3. SwipeTest : swipe page loads, can join, can swipe, no double swipe, finished flag
4. ResultsTest : results loads, empty state, top gift correct, budget total correct

## Criteres d'acceptation

- [ ] `php artisan test` → tous passent
- [ ] Parcours complet couvert
- [ ] Validations testees
- [ ] Edge cases testes
