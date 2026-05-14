# 02 — Base de donnees & Modeles
**Statut** : FAIT (2026-05-14)

## Objectif
Creer les 4 tables (events, gift_ideas, participants, swipes), les modeles Eloquent avec relations, factories et seeder demo.

## Fichiers

| Action | Fichier |
|---|---|
| Creer | `database/migrations/xxxx_create_events_table.php` |
| Creer | `database/migrations/xxxx_create_gift_ideas_table.php` |
| Creer | `database/migrations/xxxx_create_participants_table.php` |
| Creer | `database/migrations/xxxx_create_swipes_table.php` |
| Creer | `app/Models/Event.php` |
| Creer | `app/Models/GiftIdea.php` |
| Creer | `app/Models/Participant.php` |
| Creer | `app/Models/Swipe.php` |
| Creer | `database/factories/EventFactory.php` |
| Creer | `database/factories/GiftIdeaFactory.php` |
| Creer | `database/factories/ParticipantFactory.php` |
| Creer | `database/factories/SwipeFactory.php` |
| Modifier | `database/seeders/DatabaseSeeder.php` |

## Taches

1. Migration `events` : `id`, `slug` (string 8, unique), `birthday_person_name` (string), `birthday_date` (date), `timestamps`
2. Migration `gift_ideas` : `id`, `event_id` (FK cascade), `name`, `url` (nullable), `price` (decimal 8,2 nullable), `added_by` (nullable), `timestamps`
3. Migration `participants` : `id`, `event_id` (FK cascade), `name`, `budget_max` (decimal 8,2 nullable), `has_finished_swiping` (bool default false), `timestamps`
4. Migration `swipes` : `id`, `participant_id` (FK cascade), `gift_idea_id` (FK cascade), `liked` (bool), `timestamps`. Unique `[participant_id, gift_idea_id]`
5. Modele `Event` : fillable, cast birthday_date→date, relations giftIdeas/participants, routeKeyName slug, boot auto-generate slug 8 chars
6. Modele `GiftIdea` : fillable, cast price→decimal, relations event/swipes
7. Modele `Participant` : fillable, casts, relations event/swipes
8. Modele `Swipe` : fillable, cast liked→bool, relations participant/giftIdea
9. Factories pour les 4 modeles
10. Seeder : event "Anniv de Lea" + 3 idees + 2 participants

## Criteres d'acceptation

- [ ] `php artisan migrate:fresh --seed` sans erreur
- [ ] Event demo avec 3 idees et 2 participants en tinker
- [ ] FK cascade fonctionne
- [ ] `slug` unique et 8 caracteres
