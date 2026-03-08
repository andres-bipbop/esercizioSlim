<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../App/bootstrap.php';

$app = AppFactory::create();

$app->addRoutingMiddleware(); // Importa il Router originale di Slim che analizza l'URL richiesto e cerca una rotta corrispondente
$app->setBasePath('/esercizioSlim/frontend');
$app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response) {
    // Restituisce una risposta che reindirizza a /api con codice stato 302 (temporaneo)
    return $response
        ->withHeader('Location', '/esercizioSlim/frontend/homepage')
        ->withStatus(302);
});

$app->get('/homepage', function (Request $request, Response $response) {
    $templatePath = __DIR__ . '/../Templates/homepage.php';

    if (file_exists($templatePath)) {
        $html = file_get_contents($templatePath);
        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html');
    }

    $response->getBody()->write("Error: template not found");
    return $response->withStatus(404);
});

$app->run();



