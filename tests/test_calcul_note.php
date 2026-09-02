<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Service\Calcul\CalculNoteAvecRetardService;

$service = new CalculNoteAvecRetardService();

$note = $service->calculer(15.0, new DateTimeImmutable('2026-06-10'), new DateTimeImmutable('2026-06-12'));
assert($note === 15.0, "Échec : dépôt à temps devrait donner 15.0, obtenu $note");

$note = $service->calculer(15.0, new DateTimeImmutable('2026-06-15'), new DateTimeImmutable('2026-06-12'));
assert($note === 13.0, "Échec : dépôt en retard devrait donner 13.0, obtenu $note");

$note = $service->calculer(1.0, new DateTimeImmutable('2026-06-15'), new DateTimeImmutable('2026-06-12'));
assert($note === 0.0, "Échec : note plancher attendue à 0.0, obtenu $note");

echo "Tous les tests sont passés.\n";
