<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Entity\CopieExamen;

$copie1 = new CopieExamen('2026-06-01', 15.5, false, '2026-06-05');
echo "Copie 1 - Note finale : " . $copie1->getNoteFinale() . PHP_EOL;

$copie2 = new CopieExamen('2026-06-06', 15.5, true, '2026-06-05');
echo "Copie 2 - Note finale : " . $copie2->getNoteFinale() . PHP_EOL;

$copie3 = new CopieExamen('2026-06-06', 1.0, true, '2026-06-05');
echo "Copie 3 - Note finale : " . $copie3->getNoteFinale() . PHP_EOL;

try {
    new CopieExamen('2026-06-01', 25, false, '2026-06-05');
} catch (\InvalidArgumentException $e) {
    echo "Validation OK : " . $e->getMessage() . PHP_EOL;
}

// setPenaliteAppliquee() ne recalcule plus automatiquement noteFinale :
// c'est désormais au Service (ou à un appel explicite) de le faire.
$copie1->setPenaliteAppliquee(true);
$copie1->calculerNoteFinale();
echo "Copie 1 après pénalité tardive (recalcul explicite) - Note finale : " . $copie1->getNoteFinale() . PHP_EOL;
