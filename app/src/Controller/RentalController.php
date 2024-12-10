<?php

namespace App\Controller;

use App\Model\Repository\RepoManager;
use Symplefony\Controller;
use Symplefony\View;

class RentalController extends Controller
{
    /**
     * Affiche la liste des locations
     * pas de paramètre
     * @return void
     */
    public function displayRentals(): void
    {
        $view = new View('rental:user:list');

        $isOwner = AuthController::isOwner();

        $data = [
            'title' => 'Locations - Airbnb.com',
            'isOwner' => $isOwner,
            'rentals' => RepoManager::getRM()->getRentalRepo()->getAll() 
        ];

        $view->render($data);
    }

    /**
     * Affiche le détail d'une location grace à son id
     * @param int
     * @return void
     */
    public function show(int $id): void
    {
        $view = new View('rental:user:detail');

        // Récupération de la location
        $rental = RepoManager::getRM()->getRentalRepo()->getById($id);

        // Si la location n'existe pas (l'id est incorrect)
        if(is_null($rental)) {
            View::renderError(404);
            return;
        }

        $data = [
            'title' => $rental->getTitle() . ' - Airbnb.com',
            'rental' => $rental
        ];

        $view->render($data);
    }
}
