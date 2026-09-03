<?php

declare(strict_types=1);

use App\Container\Container;
use App\Controller\CopieExamenController;
use App\Repository\CopieExamenRepositoryInterface;
use App\Repository\Database;
use App\Repository\PdoCopieExamenRepository;
use App\Service\Calcul\CalculNoteAvecRetardService;
use App\Service\Calcul\CalculNoteInterface;
use App\Service\SoumissionCopieService;
use PDO;

$container = new Container();

$container->set(PDO::class, function () {
    return Database::getInstance();
});

$container->set(
    CalculNoteInterface::class,
    function (Container $c) {
        return new CalculNoteAvecRetardService();
    }
);

$container->set(
    CopieExamenRepositoryInterface::class,
    function (Container $c) {
        return new PdoCopieExamenRepository($c->get(PDO::class));
    }
);

$container->set(
    SoumissionCopieService::class,
    function (Container $c) {
        return new SoumissionCopieService(
            $c->get(CalculNoteInterface::class),
            $c->get(CopieExamenRepositoryInterface::class)
        );
    }
);

$container->set(
    CopieExamenController::class,
    function (Container $c) {
        return new CopieExamenController(
            $c->get(SoumissionCopieService::class),
            $c->get(CopieExamenRepositoryInterface::class)
        );
    }
);

return $container;
