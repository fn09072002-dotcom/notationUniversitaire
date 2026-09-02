<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Service\Calcul\CalculNoteAvecRetardService;

function verifier(string $intitule, float $attendu, float $obtenu): void
{
    if ($attendu !== $obtenu) {
        throw new \RuntimeException("Échec [$intitule] : attendu $attendu, obtenu $obtenu");
    }
    echo "OK - $intitule\n";
}

$service = new CalculNoteAvecRetardService();

$note = $service->calculer(15.0, new \DateTimeImmutable('2026-06-10'), new \DateTimeImmutable('2026-06-12'));
verifier('dépôt à temps', 15.0, $note);

$note = $service->calculer(15.0, new \DateTimeImmutable('2026-06-15'), new \DateTimeImmutable('2026-06-12'));
verifier('dépôt en retard', 13.0, $note);

$note = $service->calculer(1.0, new \DateTimeImmutable('2026-06-15'), new \DateTimeImmutable('2026-06-12'));
verifier('note plancher à 0', 0.0, $note);

echo "Tous les tests sont passés.\n";
