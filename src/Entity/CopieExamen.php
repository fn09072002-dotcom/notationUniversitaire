<?php

namespace App\Entity;

use InvalidArgumentException;

class CopieExamen extends AbstractDocument
{
    private float $noteBrute;
    private float $noteFinale;
    private bool $penaliteAppliquee;
    private string $dateLimite;

    public function __construct(
        string $dateDepot,
        float $noteBrute,
        bool $penaliteAppliquee,
        string $dateLimite,
        ?int $id = null
    ) {
        parent::__construct($dateDepot, $id);

        $this->verifierNote($noteBrute);
        $this->noteBrute = $noteBrute;
        $this->penaliteAppliquee = $penaliteAppliquee;
        $this->dateLimite = $dateLimite;

        $this->calculerNoteFinale();
    }

    public function calculerNoteFinale(): void
    {
        $this->noteFinale = $this->penaliteAppliquee
            ? max(0, $this->noteBrute - 2)
            : $this->noteBrute;
    }

    public function getNoteBrute(): float
    {
        return $this->noteBrute;
    }

    public function setNoteBrute(float $noteBrute): void
    {
        $this->verifierNote($noteBrute);
        $this->noteBrute = $noteBrute;
        $this->calculerNoteFinale();
    }

    public function getNoteFinale(): float
    {
        return $this->noteFinale;
    }

    /**
     * Impose la note finale depuis une source externe (ex: une stratégie de
     * calcul du Service). Utilisé quand le calcul de pénalité ne doit pas
     * être celui fixe de calculerNoteFinale(), mais celui d'une stratégie
     * interchangeable (voir SoumissionCopieService).
     */
    public function setNoteFinale(float $noteFinale): void
    {
        $this->noteFinale = max(0, $noteFinale);
    }

    public function isPenaliteAppliquee(): bool
    {
        return $this->penaliteAppliquee;
    }

    public function setPenaliteAppliquee(bool $penaliteAppliquee): void
    {
        $this->penaliteAppliquee = $penaliteAppliquee;
    }

    public function getDateLimite(): string
    {
        return $this->dateLimite;
    }

    private function verifierNote(float $note): void
    {
        if ($note < 0 || $note > 20) {
            throw new InvalidArgumentException("La note doit être comprise entre 0 et 20.");
        }
    }
}
