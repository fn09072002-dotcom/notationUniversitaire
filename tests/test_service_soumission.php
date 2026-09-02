<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\DTO\SoumettreCopieDTO;
use App\Repository\Database;
use App\Repository\PdoCopieExamenRepository;
use App\Service\Calcul\CalculNoteAvecRetardService;
use App\Service\SoumissionCopieService;

function verifier(string $intitule, bool $condition): void
{
    if (!$condition) {
        throw new \RuntimeException("Échec : $intitule");
    }
    echo "OK - $intitule\n";
}

$pdo = Database::getInstance();
$repository = new PdoCopieExamenRepository($pdo);
$calcul = new CalculNoteAvecRetardService();
$service = new SoumissionCopieService($calcul, $repository);

$dto = SoumettreCopieDTO::fromArray([
    'note_brute' => '15.5',
    'date_depot' => '2026-06-06',
    'date_limite' => '2026-06-05',
]);

$copie = $service->soumettre($dto);

verifier('la copie a un id', $copie->getId() !== null);
verifier('pénalité appliquée', $copie->isPenaliteAppliquee() === true);
verifier('note finale = 13.5', $copie->getNoteFinale() === 13.5);

$dtoATemps = SoumettreCopieDTO::fromArray([
    'note_brute' => '18',
    'date_depot' => '2026-06-01',
    'date_limite' => '2026-06-05',
]);

$copieATemps = $service->soumettre($dtoATemps);

verifier('pas de pénalité', $copieATemps->isPenaliteAppliquee() === false);
verifier('note finale = 18', $copieATemps->getNoteFinale() === 18.0);

echo "Tous les tests sont passés.\n";
