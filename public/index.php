<?php

declare(strict_types=1);

use App\Middleware\AddJsonResponseHeader;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Slim\Handlers\Strategies\RequestResponseArgs;
use App\Controllers\ProductIndex;
use App\Controllers\Products;
use App\Middleware\GetProduct;

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

// Add this before the error middleware, so it can handle exceptions from body parsing!
$app->addBodyParsingMiddleware();

$error_middleware = $app->addErrorMiddleware(displayErrorDetails: true, logErrors: true, logErrorDetails: true);
$error_handler = $error_middleware->getDefaultErrorHandler();
$error_handler->forceContentType('application/json');

$app->add(new AddJsonResponseHeader());

$app->get('/api/products', ProductIndex::class);

$app->get('/api/products/{id:\d+}', Products::class . ':show')
    ->add(GetProduct::class);

$app->post('/api/products', Products::class . ':create');

$app->patch('/api/products/{id:\d+}', Products::class . ':update')
    ->add(GetProduct::class); // Retrieved $product is unused, but middleware may return 404 

$app->delete('/api/products/{id:\d+}', Products::class . ':delete')
    ->add(GetProduct::class); // Retrieved $product is unused, but middleware may return 404 

$app->run();