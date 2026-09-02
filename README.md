# Système de notation universitaire

## Partie 0 — Initialiser le dépôt

### 1. Pourquoi le dossier /vendor ne doit-il pas être versionné ?

Le dossier `vendor/` contient les dépendances tierces installées par Composer.
Ce sont des fichiers générés à partir de `composer.json` et `composer.lock`,
pas du code écrit par l'équipe. Les versionner alourdirait inutilement le
dépôt puisque n'importe qui peut régénérer ce dossier à l'identique avec
`composer install`.

### 2. Quelle différence existe entre un commit et un tag ?

Un commit est un instantané du projet à un instant donné, formant
l'historique chronologique du développement. Un tag est une étiquette
nommée pointant sur un commit précis, généralement pour marquer une
version stable et publiable. Contrairement à une branche, un tag ne
bouge pas une fois posé.

### 3. Pourquoi la branche main doit-elle rester stable ?

`main` est la version de référence du projet : celle qui doit fonctionner
et servir de base à toute nouvelle branche ou tout déploiement. Le travail
en cours se fait sur des branches séparées (`partie/...`), et ne rejoint
`main` qu'une fois terminé, testé et validé.

## Partie 1 — Préparer l'application

### 1. Pourquoi placer index.php dans un dossier public ?

Parce que c'est le seul dossier que le serveur web doit exposer comme racine
accessible depuis le navigateur. Tout ce qui est en dehors — code métier,
configuration, accès aux données — reste physiquement hors d'atteinte d'une
requête HTTP directe, même si quelqu'un devine un nom de fichier.

### 2. Pourquoi toutes les requêtes devraient-elles passer par ce fichier ?

C'est le principe du Front Controller : `index.php` centralise le routage,
la sécurité et la gestion des erreurs en un seul endroit. Sans ça, chaque
fichier PHP exposé devrait réimplémenter ses propres vérifications (session,
droits d'accès, connexion à la base), ce qui multiplie les risques d'oubli
et les failles.

### 3. Quels éléments ne devraient jamais se trouver dans le dossier public ?

- Les fichiers de configuration (`config/config.php`) — identifiants de connexion.
- Les classes métier (`src/Entity`, `src/Controller`, `src/Service`).
- Les classes d'accès aux données (`src/Repository`).
- Le dossier `vendor/` des dépendances Composer.
- Les fichiers internes (`composer.json`, `composer.lock`, `.git/`).

### 4. Comment avez-vous réparti les responsabilités entre vos dossiers ?

- `public/` — point d'entrée unique et résolution des requêtes HTTP.
- `src/Entity/` — objets métier (CopieExamen, AbstractDocument).
- `src/Controller/` — réception des requêtes et orchestration.
- `src/Service/` — traitements applicatifs et logique métier.
- `src/Repository/` — accès aux données, isolé pour centraliser les requêtes SQL.
- `src/Container/` — composants transverses partagés (connexion PDO en Singleton).
- `src/Router/` — résolution des URL.
- `config/` — configuration de l'application, non versionnée.
- `templates/` — affichage, séparé du Controller.

## Partie 2 — Représenter les documents universitaires

### 1. Quelle relation avez-vous établie entre les deux classes ?

Une relation d'héritage : `CopieExamen extends AbstractDocument`. C'est une
relation "est-un" — une copie d'examen est un document, avec en plus ses
propres caractéristiques (note, pénalité).

### 2. Pourquoi ne peut-on pas créer directement un AbstractDocument ?

Parce que la classe est déclarée `abstract` — PHP interdit l'instanciation
directe d'une classe abstraite. `AbstractDocument` ne représente aucun
document concret ; elle n'a de sens que comme base commune à hériter.

### 3. Pourquoi l'identifiant peut-il être absent avant la sauvegarde ?

Parce que l'id est généré par la base de données (auto-incrément), pas par
l'application. Un objet peut donc exister en mémoire sans avoir d'id tant
qu'il n'a pas encore été enregistré, d'où `?int $id = null`.

### 4. Quel principe de conception est favorisé par la protection des propriétés ?

L'encapsulation : l'état interne de l'objet n'est jamais modifié directement
de l'extérieur, seulement via des méthodes contrôlées qui valident avant
d'assigner. Ça garantit qu'un objet est toujours dans un état valide.

## Partie 3 — Préparer la persistance

### 1. Quelle classe doit être responsable de la connexion ?

La classe `Connexion` (dans `src/Container/`). Elle seule crée et détient
l'objet PDO ; aucune autre classe ne doit ouvrir sa propre connexion —
elles passent toutes par `Connexion::getInstance()`.

### 2. Faut-il créer une nouvelle connexion pour chaque requête SQL ?

Non. Le pattern Singleton garantit qu'une seule connexion est ouverte pour
toute la durée de vie du script, réutilisée par tous les appels suivants.

### 3. Où placer les identifiants de connexion ?

Dans `config/config.php`, séparé du code source et exclu du versionnement
Git (`.gitignore`).

### 4. Pourquoi utiliser PDO ?

- Requêtes préparées pour se protéger des injections SQL.
- Abstraction du SGBD.
- Gestion des erreurs par exceptions.

## Documentation — Création de la base de données

### 1. Créer l'utilisateur et la base PostgreSQL

```bash
sudo -u postgres psql
```
```sql
CREATE USER pape WITH PASSWORD '1234';
CREATE DATABASE notation_universitaire;
GRANT ALL PRIVILEGES ON DATABASE notation_universitaire TO pape;
\q
```

### 2. Exécuter le schéma

```bash
psql -U pape -d notation_universitaire -h localhost -f database/schema.sql
```

### 3. Accorder les droits sur les tables

Le `GRANT ALL PRIVILEGES ON DATABASE` ne donne pas automatiquement les
droits sur les tables déjà créées. Il faut les accorder explicitement :

```bash
sudo -u postgres psql -d notation_universitaire
```
```sql
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO pape;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO pape;
\q
```

### 4. Configurer les identifiants de connexion

Créer `config/config.php` (non versionné, exclu par `.gitignore`) :

```php
<?php

return [
    'db_host'     => 'localhost',
    'db_port'     => '5432',
    'db_name'     => 'notation_universitaire',
    'db_user'     => 'pape',
    'db_password' => '1234',
];
```

### 5. Vérifier la connexion

```bash
php tests/test_connexion.php
```

Doit afficher le contenu de la table `copie_examen` sans erreur.
