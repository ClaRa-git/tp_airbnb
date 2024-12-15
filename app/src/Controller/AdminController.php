<?php

namespace App\Controller;

use Symplefony\Controller;
use Symplefony\View;

/**
 * Gestion des fonctionnalités d'administration
 */
class AdminController extends Controller
{
    // Dashboard
    /**
     * Affiche le tableau de bord de l'administration
     * pas de paramètre
     * @return void
     */
    public function dashboard(): void
    {
        $view = new View( 'page:admin:home', auth_controller: AuthController::class );

        $data = [
            'title' => 'Tableau de bord - Admin PasChezMoi.com'
        ];

        $view->render( $data );
    }
}