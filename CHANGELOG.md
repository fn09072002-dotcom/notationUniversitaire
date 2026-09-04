# Journal des versions

## v1.0.0
Version finale : validation des scénarios fonctionnels, corrections des dernières anomalies, documentation complétée.

## v0.13.0
Analyse de la conception : principes SOLID et concepts d'architecture (MVC, DTO, Repository, Strategie, Injection de dependances, Conteneur, Front Controller).

## v0.12.0
Centralisation de la construction des objets via un conteneur d'injection de dependances (Container, config/dependances.php).

## v0.10.0
Routeur HTTP fonctionnel avec nikic/fast-route : 4 routes enregistrees, gestion des methodes HTTP et des routes inexistantes (404).

## v0.9.0
Controleur MVC des copies : reception des requetes HTTP, creation du DTO, delegation au Service.

## v0.8.0
Vues du systeme de notation : formulaire, liste, detail, page d'erreur.

## v0.7.0
Service de soumission des copies : orchestration entre le DTO, la strategie de calcul, l'entite et le Repository.

## v0.6.0
Persistance des copies : CopieExamenRepositoryInterface et PdoCopieExamenRepository, requetes SQL preparees.

## v0.5.0
Strategie de calcul de note avec penalite de retard (CalculNoteInterface, CalculNoteAvecRetardService).

## v0.4.0
DTO de soumission : conversion et validation des donnees brutes du formulaire (SoumettreCopieDTO).

## v0.3.0
Persistance : schema PostgreSQL et connexion PDO en singleton.

## v0.2.0
Modelisation du domaine metier : AbstractDocument et CopieExamen.

## v0.1.0
Structure du projet et point d'entree (public/index.php).

## v0.0.0
Initialisation du depot Git.
