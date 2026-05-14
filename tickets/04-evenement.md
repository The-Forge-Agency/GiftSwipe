# 04 — Page evenement (organisateur)
**Statut** : FAIT (2026-05-14)

## Objectif
Implementer la page evenement : infos, lien partageable, liste des idees cadeaux, formulaire d'ajout, lien vers resultats.

## Fichiers

| Action | Fichier |
|---|---|
| Modifier | `app/Http/Controllers/EventController.php` (show, storeGift) |
| Creer | `app/Http/Requests/StoreGiftIdeaRequest.php` |
| Creer | `resources/views/event/show.blade.php` |
| Creer | `resources/views/components/share-link.blade.php` |
| Creer | `resources/views/components/gift-card.blade.php` |

## Taches

1. `EventController@show` : charger event + giftIdeas
2. `EventController@storeGift` : valider, creer GiftIdea, redirect back
3. `StoreGiftIdeaRequest` : name required max:100, url nullable url, price nullable numeric min:0
4. Composant `<x-share-link>` : URL swipe, bouton copier (Alpine clipboard API), feedback "Copie !"
5. Composant `<x-gift-card>` : icone 🎁, nom, lien produit (si url), prix (si prix)
6. Vue : header evenement, share-link, liste idees (ou etat vide), form ajout (nom + url + prix en grille), bouton resultats

## Criteres d'acceptation

- [ ] Page evenement affichee avec toutes les sections
- [ ] Ajout idee cadeau fonctionne
- [ ] Lien partageable copiable
- [ ] Etat vide gere
- [ ] Slug inexistant → 404
