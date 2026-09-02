<?php

namespace App\Service;
use InvalidArgumentException;

final class NoteValidator
{
    private function __construct()
    {
    }

    public static function validate(float|string|null $noteBrute): float
    {
        if ($noteBrute === null || $noteBrute === '' || !is_numeric($noteBrute)) {
            throw new InvalidArgumentException('La note brute doit être une valeur numérique valide.');
        }

        return (float) $noteBrute;
    }
}
