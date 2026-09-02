<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Entity\CopieExamen;
use App\Repository\Database;
use App\Repository\PdoCopieExamenRepository;

function verifier(string $intitule, bool $condition): void
{
    if (!$condition) {
        throw new \RuntimeException("Échec : $intitule");
    }
    echo "OK - $intitule\n";
}

$pdo = Database::getInstance();
$repository = new PdoCopieExamenRepository($pdo);

$copie = new CopieExamen('2026-06-06', 15.5, true, '2026-06-05');
$id = $repository->save($copie);
verifier('save() retourne un id', $id > 0);

$copieRecuperee = $repository->findById($id);
verifier('findById() retrouve la copie', $copieRecuperee !== null);
verifier('note finale correcte', $copieRecuperee->getNoteFinale() === 13.5);

$toutes = $repository->findAll();
verifier('findAll() retourne un tableau non vide', count($toutes) > 0);

$absente = $repository->findById(999999);
verifier('findById() renvoie null si absent', $absente === null);

echo "Tous les tests sont passés.\n";
