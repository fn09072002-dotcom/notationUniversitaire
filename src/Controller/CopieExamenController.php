<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\SoumettreCopieDTO;
use App\Repository\CopieExamenRepositoryInterface;
use App\Service\SoumissionCopieService;
use InvalidArgumentException;

final class CopieExamenController
{
    public function __construct(
        private readonly SoumissionCopieService $service,
        private readonly CopieExamenRepositoryInterface $repository
    ) {
    }

    public function afficherFormulaire(): void
    {
        $erreurs = [];
        require __DIR__ . '/../../templates/copie_formulaire.php';
    }

    public function soumettre(array $donneesPost): void
    {
        try {
            $dto = SoumettreCopieDTO::fromArray($donneesPost);
            $copie = $this->service->soumettre($dto);

            header('Location: /copies/' . $copie->getId());
            exit;
        } catch (InvalidArgumentException $e) {
            $erreurs = [$e->getMessage()];
            require __DIR__ . '/../../templates/error/validation.php';
        }
    }

    public function afficherListe(): void
    {
        $copies = $this->repository->findAll();
        require __DIR__ . '/../../templates/copie_liste.php';
    }

    public function afficherDetail(int $id): void
    {
        $copie = $this->repository->findById($id);

        if ($copie === null) {
            http_response_code(404);
            require __DIR__ . '/../../templates/error/404.php';
            return;
        }

        require __DIR__ . '/../../templates/copie_detail.php';
    }
}
