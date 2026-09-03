<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\CopieExamenController;
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
$controller = new CopieExamenController($service, $repository);

ob_start();
$controller->afficherFormulaire();
$html = ob_get_clean();
verifier('afficherFormulaire() rend un <form>', str_contains($html, '<form'));

ob_start();
$controller->afficherListe();
$html = ob_get_clean();
verifier('afficherListe() rend une page', str_contains($html, 'Copies enregistrées'));


ob_start();
$controller->soumettre(['date_depot' => '2026-06-06', 'date_limite' => '2026-06-05']);
$html = ob_get_clean();
verifier('soumettre() invalide affiche une erreur', str_contains($html, 'numérique valide'));

$copies = $repository->findAll();
$premierId = $copies[0]->getId();
ob_start();
$controller->afficherDetail($premierId);
$html = ob_get_clean();
verifier('afficherDetail() rend le détail', str_contains($html, 'Détail de la copie'));

ob_start();
$controller->afficherDetail(999999);
$html = ob_get_clean();
verifier('afficherDetail() inexistant affiche la page 404', str_contains($html, '404'));

echo "\nTerminé.\n";
