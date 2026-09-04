# Analyse de la conception

Systeme de notation universitaire

## 1. Principes SOLID

| Principe | Classe ou mecanisme concerne | Justification |
|---|---|---|
| **S - Responsabilite unique** | `CopieExamenController` (traite le HTTP), `SoumissionCopieService` (orchestre), `PdoCopieExamenRepository` (fait le SQL) | Chaque classe a une seule raison de changer : si le SQL evolue, seul le Repository est modifie ; si la regle de penalite change, seul le calculateur l'est |
| **O - Ouvert/ferme** | `CalculNoteInterface` + `CalculNoteAvecRetardService` | Une nouvelle strategie de calcul (penalite proportionnelle) peut etre ajoutee en creant une nouvelle classe, sans modifier le Service qui l'utilise |
| **L - Substitution de Liskov** | `CopieExamen extends AbstractDocument` | Tout code qui manipule un `AbstractDocument` (id, dateDepot) fonctionne aussi avec une `CopieExamen`, sans comportement inattendu |
| **I - Segregation des interfaces** | `CopieExamenRepositoryInterface` (save, findAll, findById uniquement) | Le contrat n'oblige personne a implementer des methodes dont il n'a pas besoin |
| **D - Inversion des dependances** | `SoumissionCopieService` depend de `CalculNoteInterface` et `CopieExamenRepositoryInterface`, jamais de leurs implementations concretes | Le Service ne sait pas s'il parle a PostgreSQL ou a une autre strategie ; le conteneur decide de l'implementation reelle a l'execution |

### Mauvaises conceptions evitees et corrections

- **S viole** : si `CopieExamenController` calculait lui-meme la penalite en plus de gerer le HTTP -> correction : deplacer ce calcul dans `SoumissionCopieService`.
- **O viole** : si `SoumissionCopieService::soumettre()` contenait un `if/else` pour choisir la formule de penalite selon un parametre -> correction : passer par `CalculNoteInterface` injecte, une nouvelle strategie devient une nouvelle classe.
- **L viole** : si `CopieExamen::getDateDepot()` retournait parfois un `DateTime`, parfois une chaine, selon les cas -> correction : toujours retourner `string`.
- **I viole** : si `CopieExamenRepositoryInterface` imposait une methode `envoyerEmailConfirmation()` sans rapport avec la persistance -> correction : sortir cette responsabilite dans une interface separee.
- **D viole** : si `SoumissionCopieService` faisait `new PdoCopieExamenRepository(...)` directement dans son code -> correction : injecter l'interface via le constructeur.

## 2. Etude complementaire

| Notion | Classe(s) concernee(s) | Utilite | Difficulte evitee |
|---|---|---|---|
| **MVC** | `CopieExamen` (Model), `templates/*.php` (Vue), `CopieExamenController` (Controleur) | Separe la donnee, l'affichage et la logique de traitement des requetes | Evite de melanger SQL, calcul et HTML dans un seul fichier |
| **Routeur** | `Dispatcher` (FastRoute), enregistre dans `config/dependances.php` | Fait correspondre une URL et une methode HTTP a une action precise | Evite d'ecrire des `if ($_SERVER['REQUEST_URI'] === ...)` en cascade |
| **Service** | `SoumissionCopieService` | Contient la logique applicative qui orchestre plusieurs objets (DTO, calcul, entite, repository) | Evite de disperser cette orchestration dans le Controleur ou l'Entite |
| **DTO** | `SoumettreCopieDTO` | Transporte des donnees validees et typees depuis le formulaire vers le Service | Evite de transmettre `$_POST` brut (non type, non valide) aux classes metier |
| **Interface** | `CalculNoteInterface`, `CopieExamenRepositoryInterface` | Definit un contrat sans imposer d'implementation | Evite de coupler le code a une seule facon de faire (une seule base de donnees, une seule regle de calcul) |
| **Strategie** | `CalculNoteAvecRetardService` implementant `CalculNoteInterface` | Encapsule un algorithme interchangeable derriere un contrat commun | Evite de modifier le Service a chaque nouvelle regle de calcul de note |
| **Injection de dependances** | Constructeurs de `SoumissionCopieService`, `PdoCopieExamenRepository`, `CopieExamenController` | Les dependances sont fournies de l'exterieur plutot que creees a l'interieur de la classe | Evite les classes qui construisent elles-memes leurs dependances, difficiles a tester isolement |
| **Conteneur d'injection de dependances** | `Container` (`src/Container/Container.php`), configure dans `config/dependances.php` | Centralise la construction et l'assemblage de tous les objets du projet | Evite que `public/index.php` connaisse l'ordre exact de construction de chaque dependance |
| **Repository** | `CopieExamenRepositoryInterface` / `PdoCopieExamenRepository` | Isole les requetes SQL derriere une interface orientee metier (save, findAll, findById) | Evite que le SQL se retrouve disperse dans le Service ou le Controleur |
| **Front Controller** | `public/index.php` | Point d'entree HTTP unique par lequel passent toutes les requetes | Evite d'avoir un fichier PHP executable par URL pour chaque action, chacun dupliquant sa propre logique de securite et de routage |
## 3. Concepts decouverts pendant le developpement

- **Singleton** : garantir une seule connexion PDO partagee pour toute la duree du script, plutot que d'en recreer une a chaque requete.
- **DTO (Data Transfer Object)** : transporter des donnees deja validees et typees entre les couches, sans jamais transmettre `$_POST` brut aux classes metier.
- **Pattern Strategie** : encapsuler une regle de calcul interchangeable (`CalculNoteInterface`) derriere un contrat commun, pour pouvoir la remplacer sans toucher au Service qui l'utilise.
- **Repository** : isoler toutes les requetes SQL derriere une interface orientee metier, pour que le reste de l'application ne sache jamais qu'une base de donnees existe.
- **Injection de dependances** : fournir les dependances d'une classe via son constructeur plutot que de les construire a l'interieur, ce qui rend chaque classe testable isolement.
- **Conteneur d'injection de dependances** : centraliser la construction et l'assemblage de tous les objets du projet, pour que le point d'entree ne connaisse plus l'ordre de construction.
- **Front Controller** : faire passer toutes les requetes HTTP par un unique point d'entree, plutot que d'exposer un fichier PHP par action.
- **Requetes preparees** : se proteger des injections SQL en separant la structure de la requete des valeurs fournies par l'utilisateur.
- **Piege PDO/PostgreSQL sur les booleens** : PHP convertit `false` en chaine vide dans un tableau passe a `execute()`, ce que PostgreSQL rejette comme valeur booleenne ; necessite un typage explicite via `bindValue(..., PDO::PARAM_BOOL)`.
- **Difference entre GRANT sur une base et GRANT sur ses tables** : accorder des privileges sur une base PostgreSQL ne donne pas automatiquement les memes privileges sur les tables deja existantes a l'interieur.
