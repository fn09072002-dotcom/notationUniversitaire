<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\SoumettreCopieDTO;
use App\Entity\CopieExamen;
use App\Repository\CopieExamenRepositoryInterface;
use App\Service\Calcul\CalculNoteInterface;

final class SoumissionCopieService
{
    public function __construct(
        private readonly CalculNoteInterface $calculNote,
        private readonly CopieExamenRepositoryInterface $repository
    ) {
    }

    public function soumettre(SoumettreCopieDTO $dto): CopieExamen
    {
        $noteFinale = $this->calculNote->calculer(
            $dto->noteBrute,
            $dto->dateDepot,
            $dto->dateLimite
        );

        $penaliteAppliquee = $noteFinale < $dto->noteBrute;

        $copie = new CopieExamen(
            $dto->dateDepot->format('Y-m-d'),
            $dto->noteBrute,
            $penaliteAppliquee,
            $dto->dateLimite->format('Y-m-d')
        );

        $copie->setNoteFinale($noteFinale);

        $id = $this->repository->save($copie);

        return $this->repository->findById($id);
    }
}
