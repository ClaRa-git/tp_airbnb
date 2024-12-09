<?php

namespace Symplefony;

use Closure;
use Psr\Http\Message\ServerRequestInterface;

interface IMiddleware
{
    public function handle( ServerRequestInterface $request, Closure $next ): mixed;
}