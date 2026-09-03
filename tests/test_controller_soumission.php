<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\CopieExamenController;
use App\Repository\Database;
use App\Repository\PdoCopieExamenRepository;
use App\Service\Calcul\CalculNoteAvecRetardService;
use App\Service\SoumissionCopieService;

$pdo = Database::getInstance();
$repository = new PdoCopieExamenRepository($pdo);
$calcul = new CalculNoteAvecRetardService();
$service = new SoumissionCopieService($calcul, $repository);
$controller = new CopieExamenController($service, $repository);

echo "Avant soumission : redirection attendue ensuite.\n";

$controller->soumettre([
    'note_brute' => '15.5',
    'date_depot' => '2026-06-06',
    'date_limite' => '2026-06-05',
]);

echo "Cette ligne ne devrait jamais s'afficher.\n";
