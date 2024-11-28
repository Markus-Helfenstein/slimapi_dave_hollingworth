<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ProductRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ProductIndex
{
    public function __construct(private ProductRepository $repository) {}
    
    public function __invoke(Request $request, Response $response): Response
    {
        // is no longer required when src folder has been included in autoload (see composer.json)
        // however, changes to autoload require you to run the command "composer dump-autoload"
        //require dirname(__DIR__) . '/src/App/Database.php';

        $data = $this->repository->getAll();

        $body = json_encode($data);

        $response->getBody()->write($body);

        return $response;
    }
}