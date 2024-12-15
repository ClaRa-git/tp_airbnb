<?php

namespace App\Controller;

use Symplefony\Controller;
use Symplefony\View;

class PageController extends Controller
{
    // Page mentions légales
    public function legalNotice(): void
    {
        $view = new View( 'page:legal-notice', auth_controller: AuthController::class );

        $data = [
            'title' => 'Mentions légales - PasChezMoi.com'
        ];

        $view->render( $data );
    }
}