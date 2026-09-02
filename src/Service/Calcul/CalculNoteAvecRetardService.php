<?php

declare(strict_types=1);

namespace App\Service\Calcul;

final class CalculNoteAvecRetardService implements CalculNoteInterface
{
    private const PENALITE_RETARD = 2.0;
    private const NOTE_MINIMALE = 0.0;

    public function calculer(
        float $noteBrute,
        \DateTimeImmutable $dateDepot,
        \DateTimeImmutable $dateLimite
    ): float {
        $note = $noteBrute;

        if ($this->estEnRetard($dateDepot, $dateLimite)) {
            $note -= self::PENALITE_RETARD;
        }

        return max(self::NOTE_MINIMALE, $note);
    }

    private function estEnRetard(
        \DateTimeImmutable $dateDepot,
        \DateTimeImmutable $dateLimite
    ): bool {
        return $dateDepot > $dateLimite;
    }
}
