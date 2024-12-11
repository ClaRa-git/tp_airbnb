<?php

namespace App\Controller;

use App\Model\Repository\RepoManager;
use Symplefony\AbstractSession;
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
        $view = new View('reservation:user:list');

        // Récupération des réservations
        // TODO: à gérer avec la session
        $reservations = RepoManager::getRM()->getReservationRepo()->getAllForUser(AbstractSession::get('user')->getId());

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
