<?php

namespace App\Controller;

use Symplefony\Controller;
use Symplefony\View;


class UserController extends Controller
{
    /**
     * Affichage du formulaire de création de compte
     * pas de paramètre
     * @return void
     */
    public function displaySubscribe(): void
    {
        $view = new View('user:create-account');

        $data = [
            'title' => 'Créer mon compte - Airbnb.com'
        ];

        $view->render($data);
    }

    /**
     * Affichage du formulaire de connexion
     * pas de paramètre
     * @return void
     */
    public function displayLogin(): void
    {
        $view = new View('user:login');

        $data = [
            'title' => 'Se connecter - Airbnb.com'
        ];

        $view->render($data);
    }

    /**
     * Traitement du formulaire de connexion
     * pas de paramètre
     * @return void
     */
    public function processSubscribe(): void
    {
        // TODO: :)
    }
}
