<?php

namespace App\Controller;

use App\Model\Repository\RepoManager;
use Symplefony\Controller;
use Symplefony\View;

class ReservationController extends Controller
{
    /**
     * Affiche la liste des réservations de l'utilisateur
     * pas de paramètre
     * @return void
     */
    public function show(): void
    {
        // Si l'utilisateur n'est pas connecté ou est en mode propriétaire on redirige vers la page d'accueil
        if (!AuthController::isLogged() || AuthController::isOwner()) {
            $this->redirect('/');
        }

        $view = new View('reservation:user:list');

        // Récupération des réservations
        // TODO: à gérer avec la session
        $reservations = RepoManager::getRM()->getReservationRepo()->getAllForUser(1); // $_SESSION['user_id']

        // Si la location n'existe pas
        if (is_null($reservations)) {
            View::renderError(404);
            return;
        }

        $data = [
            'title' => 'Mes réservations - Airbnb.com',
            'reservations' => $reservations
        ];

        $view->render($data);
    }
}
