<?php

require __DIR__ . '/../vendor/autoload.php';

use App\DTO\SoumettreCopieDTO;
use InvalidArgumentException;

$dto = SoumettreCopieDTO::fromArray([
    'note_brute' => '15.5',
    'date_depot' => '2026-06-06',
    'date_limite' => '2026-06-05',
]);
qecho "OK - dateDepot : " . $dto->dateDepot->format('Y-m-d') . PHP_EOL;

try {
    SoumettreCopieDTO::fromArray(['date_depot' => '2026-06-06', 'date_limite' => '2026-06-05']);
} catch (InvalidArgumentException $e) {
    echo "Validation OK : " . $e->getMessage() . PHP_EOL;
}

try {
    SoumettreCopieDTO::fromArray(['note_brute' => 'abc', 'date_depot' => '2026-06-06', 'date_limite' => '2026-06-05']);
} catch (InvalidArgumentException $e) {
    echo "Validation OK : " . $e->getMessage() . PHP_EOL;
}

try {
    SoumettreCopieDTO::fromArray(['note_brute' => '15', 'date_depot' => 'pas-une-date', 'date_limite' => '2026-06-05']);
} catch (InvalidArgumentException $e) {
    echo "Validation OK : " . $e->getMessage() . PHP_EOL;
}
