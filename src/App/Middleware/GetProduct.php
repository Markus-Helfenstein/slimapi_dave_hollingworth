<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Repositories\ProductRepository;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;

class GetProduct
{
    public function __construct(private ProductRepository $repository) {}

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $id = $route->getArgument('id');
        $data = $this->repository->getById((int) $id);

        if ($data === false) {
            throw new HttpNotFoundException($request, message: 'product not found');
        }

        $request = $request->withAttribute('product', $data);
        return $handler->handle($request);
    }
}
