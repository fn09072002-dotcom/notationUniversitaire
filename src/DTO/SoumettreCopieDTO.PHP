<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeImmutable;
use InvalidArgumentException;

final class SoumettreCopieDTO
{
    public readonly float $noteBrute;
    public readonly DateTimeImmutable $dateDepot;
    public readonly DateTimeImmutable $dateLimite;

    private function __construct(
        float $noteBrute,
        DateTimeImmutable $dateDepot,
        DateTimeImmutable $dateLimite
    ) {
        $this->noteBrute  = $noteBrute;
        $this->dateDepot  = $dateDepot;
        $this->dateLimite = $dateLimite;
    }

    public static function depuisFormulaire(array $donnees): self
    {
        $erreurs = [];

        $noteBrute = $donnees['noteBrute'] ?? null;
        if ($noteBrute === null || $noteBrute === '') {
            $erreurs[] = "noteBrute : valeur manquante.";
        } elseif (!is_numeric($noteBrute)) {
            $erreurs[] = "noteBrute : doit être numérique.";
        }

        $dateDepot = self::convertirDate($donnees['dateDepot'] ?? null, 'dateDepot', $erreurs);

        $dateLimite = self::convertirDate($donnees['dateLimite'] ?? null, 'dateLimite', $erreurs);

        if (!empty($erreurs)) {
            throw new InvalidArgumentException(implode(' | ', $erreurs));
        }

        return new self((float) $noteBrute, $dateDepot, $dateLimite);
    }

    private static function convertirDate(?string $valeur, string $nomChamp, array &$erreurs): ?DateTimeImmutable
    {
        if ($valeur === null || $valeur === '') {
            $erreurs[] = "$nomChamp : valeur manquante.";
            return null;
        }

        $date = DateTimeImmutable::createFromFormat('Y-m-d', $valeur);
        if ($date === false) {
            $erreurs[] = "$nomChamp : format invalide (attendu AAAA-MM-JJ).";
            return null;
        }

        return $date;
    }
}