<?php

namespace App\Middleware;

use Closure;

use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;

use Symplefony\IMiddleware;

use App\Controller\AuthController;

class VisitorMiddleware implements IMiddleware
{
    public function handle( ServerRequestInterface $request, Closure $next ): mixed
    {
        if( !AuthController::isAuth() ) {
            return $next( $request );
        }

        return new RedirectResponse( '/' );
    }
}