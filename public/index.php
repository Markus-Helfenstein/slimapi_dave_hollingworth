<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use DI\ContainerBuilder;
use Slim\Handlers\Strategies\RequestResponseArgs;

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$builder = new ContainerBuilder();
$container = $builder
    ->addDefinitions(APP_ROOT . '/config/definitions.php')
    ->build();
AppFactory::setContainer($container);
$app = AppFactory::create();

$collector = $app->getRouteCollector();
$collector->setDefaultInvocationStrategy(new RequestResponseArgs());

$error_middleware = $app->addErrorMiddleware(displayErrorDetails: true, logErrors: true, logErrorDetails: true);
$error_handler = $error_middleware->getDefaultErrorHandler();
$error_handler->forceContentType('application/json');

$app->get('/api/products', function (Request $request, Response $response)
{
    // is no longer required when src folder has been included in autoload (see composer.json)
    // however, changes to autoload require you to run the command "composer dump-autoload"
    //require dirname(__DIR__) . '/src/App/Database.php';
    
    //$database = new App\Database();
    $repository = $this->get(App\Repositories\ProductRepository::class);

    $data = $repository->getAll();

    $body = json_encode($data);

    $response->getBody()->write($body);

    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/products/{id:\d+}', function (Request $request, Response $response, string $id) 
{
    $repository = $this->get(App\Repositories\ProductRepository::class);
    $data = $repository->getById((int) $id);

    if ($data === false) {
        throw new \Slim\Exception\HttpNotFoundException($request, message: 'product not found');
    }

    $body = json_encode($data);

    $response->getBody()->write($body);

    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();