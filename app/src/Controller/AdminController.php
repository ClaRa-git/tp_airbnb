<?php

namespace App\Controller;

use Symplefony\Controller;
use Symplefony\View;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard(): void
    {
        $view = new View( 'page:admin:home', auth_controller: AuthController::class );

        $data = [
            'title' => 'Tableau de bord - Admin Airbnb.com'
        ];

        $view->render( $data);
    }
}