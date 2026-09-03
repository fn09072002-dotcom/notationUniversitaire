<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

if (PHP_SAPI === 'cli-server') {
    $cheminDemande = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $fichierReel = __DIR__ . $cheminDemande;

    if ($cheminDemande !== '/' && is_file($fichierReel)) {
        return false;
    }
}
use App\Controller\CopieExamenController;
use App\Repository\Database;
use App\Repository\PdoCopieExamenRepository;
use App\Service\Calcul\CalculNoteAvecRetardService;
use App\Service\SoumissionCopieService;
use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

$pdo = Database::getInstance();
$repository = new PdoCopieExamenRepository($pdo);
$calcul = new CalculNoteAvecRetardService();
$service = new SoumissionCopieService($calcul, $repository);
$controller = new CopieExamenController($service, $repository);

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/copies', 'afficherListe');
    $r->addRoute('GET', '/copies/create', 'afficherFormulaire');
    $r->addRoute('POST', '/copies', 'soumettre');
    $r->addRoute('GET', '/copies/{id:\d+}', 'afficherDetail');
});

$methode = $_SERVER['REQUEST_METHOD'];
$uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$routeInfo = $dispatcher->dispatch($methode, $uri);

switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        require __DIR__ . '/../templates/error/404.php';
        break;

    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo "Méthode non autorisée.";
        break;

    case \FastRoute\Dispatcher::FOUND:
        $action = $routeInfo[1];
        $parametres = $routeInfo[2];

        match ($action) {
            'afficherListe' => $controller->afficherListe(),
            'afficherFormulaire' => $controller->afficherFormulaire(),
            'soumettre' => $controller->soumettre($_POST),
            'afficherDetail' => $controller->afficherDetail((int) $parametres['id']),
        };
        break;
}
