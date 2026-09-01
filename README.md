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
