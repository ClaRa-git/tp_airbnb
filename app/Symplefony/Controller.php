<?php

namespace Symplefony;

use App\App;
use Laminas\Diactoros\Response\RedirectResponse;

abstract class Controller
{
    protected function redirect( string $uri, int $status = 302, array $headers = [] ): void
    {
        $response = new RedirectResponse( $uri, $status, $headers );
        App::getApp()->getRouter()->getPublisher()->publish( $response );
        die();
    }
}