<?php

namespace App\Controller;

use App\Model\Entity\User;
use App\Model\Repository\RepoManager;
use App\Tools\Functions;

use Symplefony\Controller;
use Symplefony\View;

use Laminas\Diactoros\ServerRequest;

class UserController extends Controller
{
    /**
     * Créer un nouvel utilisateur
     * @param ServerRequest $request
     * @return void
     */
    public function create(ServerRequest $request): void
    {
        $user_data = $request->getParsedBody();

        $user = new User($user_data);

        $user_created = RepoManager::getRM()->getUserRepo()->create($user);

        if (is_null($user_created)) {
            $this->redirect('/users/sign-in?error=Une erreur est survenue lors de la création de votre compte');
        }

        $this->redirect('/users/sign-in');
    }
}