<?php

namespace App\Service;
use DateTimeImmutable;
use Exception;
use InvalidArgumentException;

class DateUtils
{
    public static function convertirDate(?string $date, string $nomChamp): DateTimeImmutable
    {
        if ($date === null || $date === '') {
            throw new InvalidArgumentException(sprintf("Le champ '%s' est obligatoire.", $nomChamp));
        }

        try {
            return new DateTimeImmutable($date);
        } catch (Exception $e) {
            throw new InvalidArgumentException(sprintf("Le champ '%s' doit être une date valide.", $nomChamp));
        }
    }
}
