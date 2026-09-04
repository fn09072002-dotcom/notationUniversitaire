# Preuves des tests fonctionnels

Verifications realisees en conditions HTTP reelles (php -S + curl), suite a la Partie 14.

## Resultats

| Scenario | Statut HTTP | Resultat observe |
|---|---|---|
| 1 | 302 -> 200 | Note finale = note brute, penalite = Non |
| 2 | 302 -> 200 | Note finale = note brute - 2, penalite = Oui |
| 3 | 302 -> 200 | Note finale = 0 (plancher respecte), penalite = Oui |
| 4 | 200 (pas de redirection) | Aucune insertion, message : "La note doit etre comprise entre 0 et 20." |
| 5 | 404 | Page d'erreur affichee |
| 6 | 200 | La copie apparait dans /copies et son detail sur /copies/{id} correspond aux donnees en base |

## Verification sur base vide

Commande utilisee :

```
TRUNCATE TABLE copie_examen RESTART IDENTITY;
```

Premiere soumission apres troncature : id genere = 1, fonctionnement identique.

Aucune anomalie detectee.