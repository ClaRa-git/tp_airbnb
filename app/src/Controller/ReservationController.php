<?php

namespace App\Controller;

use App\Model\Entity\Reservation;
use App\Model\Repository\RepoManager;
use App\Session;
use Laminas\Diactoros\ServerRequest;
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
        $view = new View('reservation:user:list', auth_controller: AuthController::class);

        // Récupération des réservations
        if(AuthController::isOwner()) {
            $reservations = RepoManager::getRM()->getReservationRepo()->getAllForOwner(Session::get(Session::USER)->getId());
        } else {
            $reservations = RepoManager::getRM()->getReservationRepo()->getAllForUser(Session::get(Session::USER)->getId());
        }

        // Si la location n'existe pas
        if (is_null($reservations)) {
            View::renderError(404);
            return;
        }

        $data = [
            'title' => 'Mes réservations - PasChezMoi.com',
            'reservations' => $reservations
        ];

        $view->render($data);
    }

    /**
     * Créer une nouvelle réservation
     * @param ServerRequest $request
     * @return void
     */
    public function create(ServerRequest $request): void
    {
        // TODO: Vérifier les données de la requête
        $reservation_data = $request->getParsedBody();

        $reservation = new Reservation($reservation_data);

        $reservation_created = RepoManager::getRM()->getReservationRepo()->create($reservation);

        if (is_null($reservation_created)) {
            $this->redirect('/reservations/add?error=Une erreur est survenue lors de la création de la réservation');
        }

        $this->redirect('/reservations');
    }

    /**
     * Affiche le formulaire d'ajout d'une réservation
     * pas de paramètre
     * @return void
     */
    public function displayAddReservation(ServerRequest $request): void
    {
        $view = new View('reservation:user:create', auth_controller: AuthController::class);

        $data = [
            'title' => 'Ajouter une réservation - PasChezMoi.com'
        ];

        $view->render($data);
    }

    /**
     * Traite le formulaire d'ajout d'une réservation
     * @param ServerRequest $request
     * @return void
     */
    public function processAddReservation(ServerRequest $request): void
    {
        $reservation_data = $request->getParsedBody();

        // On vérifie que l'on reçoit bien les données
        if (!isset($reservation_data['dateStart']) ||
            !isset($reservation_data['dateEnd']) ||
            !isset($reservation_data['rental_id'])
        ) {
            $this->redirect('/reservations/add?error=Erreur lors de la création des champs');
        }

        // On sécurise les données
        $dateStart = htmlspecialchars($reservation_data['dateStart']);
        $dateEnd = htmlspecialchars($reservation_data['dateEnd']);
        $rental_id = htmlspecialchars($reservation_data['rental_id']);

        // On vérifie si les données sont vides

        if (empty($dateStart) ||
            empty($dateEnd) ||
            empty($rental_id)
        ) {
            $this->redirect('/reservations/add?error=Veuillez remplir tous les champs');
        }

        // On vérifie si la location existe
        $rental = RepoManager::getRM()->getRentalRepo()->getById($rental_id);

        if (is_null($rental)) {
            $this->redirect('/reservations/add?error=La location n\'existe pas');
        }

        $reservation = new Reservation([
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
            'user_id' => Session::get(Session::USER)->getId(),
            'rental_id' => $rental_id
        ]);

         $reservation_created = RepoManager::getRM()->getReservationRepo()->create($reservation);

        if (is_null($reservation_created)) {
            $this->redirect('/reservations/add?error=Une erreur est survenue lors de la création de la réservation');
        }

        $this->redirect('/reservations');
    }
}
