<?php

declare(strict_types=1);

namespace App\Service\Calcul;

interface CalculNoteInterface
{
    public function calculer(
        float $noteBrute,
        \DateTimeImmutable $dateDepot,
        \DateTimeImmutable $dateLimite
    ): float;
}
