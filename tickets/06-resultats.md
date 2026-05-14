# 06 — Resultats
**Statut** : FAIT (2026-05-14)

## Objectif
Implementer la page resultats : top cadeau, barre budget, classement, liste participants.

## Fichiers

| Action | Fichier |
|---|---|
| Modifier | `app/Http/Controllers/EventController.php` (results) |
| Creer | `resources/views/results.blade.php` |
| Creer | `resources/views/components/top-gift.blade.php` |
| Creer | `resources/views/components/budget-bar.blade.php` |
| Creer | `resources/views/components/ranking.blade.php` |
| Creer | `resources/views/components/participants-list.blade.php` |

## Taches

1. `EventController@results` : charger event + giftIdeas + participants + swipes, calculer votes/budget
2. `<x-top-gift>` : trophee, nom, prix, nombre votes
3. `<x-budget-bar>` : barre progression gradient accent→cagnotte, message suffisant/manquant
4. `<x-ranking>` : classement par votes positifs, barres proportionnelles
5. `<x-participants-list>` : badges prenom
6. Vue : etat vide (aucun vote) ou resultats complets

## Criteres d'acceptation

- [ ] Top cadeau mis en evidence
- [ ] Barre budget correcte
- [ ] Classement ordonne
- [ ] Participants listes
- [ ] Etat vide gere
- [ ] Responsive
