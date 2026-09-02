<?php

namespace App\DTO;
use App\Service\DateUtils;
use App\Service\NoteValidator;
use DateTimeImmutable;

readonly class SoumettreCopieDTO
{
    private function __construct(
        public float $noteBrute,
        public DateTimeImmutable $dateDepot,
        public DateTimeImmutable $dateLimite
    ) {
    }

    public static function fromArray(array $data): SoumettreCopieDTO
    {
        $noteBrute = $data['note_brute'] ?? null;
        $dateDepot = $data['date_depot'] ?? null;
        $dateLimite = $data['date_limite'] ?? null;

        $noteValidee = NoteValidator::validate($noteBrute);
        $dateDepotConvertie = DateUtils::convertirDate($dateDepot, 'date de depot');
        $dateLimiteConvertie = DateUtils::convertirDate($dateLimite, 'date limite');

        return new SoumettreCopieDTO($noteValidee, $dateDepotConvertie, $dateLimiteConvertie);
    }
}
