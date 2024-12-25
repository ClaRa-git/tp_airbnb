<?php

namespace App\Controller;

use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;

use App\Model\Entity\Reservation;
use App\Model\Entity\User;
use App\Model\Repository\RepoManager;
use App\Session;
use App\Tools\Functions;

class ReservationController extends Controller
{
    /**
     * Affiche le formulaire d'ajout d'une réservation
     * pas de paramètre
     * @return void
     */
    public function displayAddReservation(int $id): void
    {
        $view = new View('reservation:user:create', auth_controller: AuthController::class);

        $rental = RepoManager::getRM()->getRentalRepo()->getById($id);
        $rental->setTypeLogement(RepoManager::getRM()->getTypeLogementRepo()->getById($rental->getTypeLogementId()));
        $rental->setAddress(RepoManager::getRM()->getAddressRepo()->getById($rental->getAddressId()));
        $user = RepoManager::getRM()->getUserRepo()->getById($rental->getOwnerId());
        $user->setPassword('');
        $rental->setOwner($user);
        $rental->getEquipments();

        $data = [
            'title' => 'Ajouter une réservation - PasChezMoi.com',
            'rental' => $rental
        ];

        $view->render($data);
    }

    /**
     * Traite le formulaire d'ajout d'une réservation
     * @param ServerRequest $request
     * @return void
     */
    public function processAddReservation(ServerRequest $request, int $id): void
    {
        $reservation_data = $request->getParsedBody();

        // On vérifie que l'on reçoit bien les données
        if (
            !isset($reservation_data['dateStart']) ||
            !isset($reservation_data['dateEnd'])
        ) {
            $this->redirect('/reservations/add/{id}?error=Erreur lors de la création des champs');
        }

        // On sécurise les données
        $dateStart = htmlspecialchars($reservation_data['dateStart']);
        $dateEnd = htmlspecialchars($reservation_data['dateEnd']);
        $rental_id = htmlspecialchars($id);

        // On vérifie si les données sont vides
        if (
            empty($dateStart) ||
            empty($dateEnd) ||
            empty($rental_id)
        ) {
            $route = '/reservations/add/' . $id . '?error=Veuillez remplir tous les champs';
            $this->redirect($route);
        }

        // On vérifie que la date de début est supérieure à la date actuelle
        if (!Functions::dateIsSuperior($dateStart)) {
            $route = '/reservations/add/' . $id . '?error=La date de début doit être supérieure à la date actuelle';
            $this->redirect($route);
        }

        // On vérifie que la date de fin est supérieure à la date de début
        if (!Functions::dateEndIsSuperior($dateStart, $dateEnd)) {
            $route = '/reservations/add/' . $id . '?error=La date de fin doit être supérieure à la date de début';
            $this->redirect($route);
        }

        // On vérifie si la location existe
        $rental = RepoManager::getRM()->getRentalRepo()->getById($id);

        if (is_null($rental)) {
            $route = '/reservations/add/' . $id . '?error=La location n\'existe pas';
            $this->redirect($route);
        }

        $reservation = new Reservation([
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
            'user_id' => Session::get(Session::USER)->getId(),
            'rental_id' => $rental_id
        ]);

        $reservation_created = RepoManager::getRM()->getReservationRepo()->create($reservation);

        if (is_null($reservation_created)) {
            $route = '/reservations/add/' . $id . '?error=Une erreur est survenue lors de la création de la réservation';
            $this->redirect($route);
        }

        $reservation_created->setUser(RepoManager::getRM()->getUserRepo()->getById($reservation_created->getUserId()));
        $reservation_created->setRental(RepoManager::getRM()->getRentalRepo()->getById($reservation_created->getRentalId()));
        $reservation_created->getRental()->setTypeLogement(RepoManager::getRM()->getTypeLogementRepo()->getById($reservation_created->getRental()->getTypeLogementId()));
        $reservation_created->getRental()->setAddress(RepoManager::getRM()->getAddressRepo()->getById($reservation_created->getRental()->getAddressId()));
        $reservation_created->getRental()->setOwner(RepoManager::getRM()->getUserRepo()->getById($reservation_created->getRental()->getOwnerId()));
        $reservation_created->getRental()->getOwner()->setPassword('');
        $reservation_created->getRental()->getEquipments();

        $this->redirect('/reservations/list');
    }

    /**
     * Affiche une réservation
     * @param int $id
     * @return void
     */
    public function show(int $id): void
    {
        $view = new View('reservation:user:detail', auth_controller: AuthController::class);

        $reservation = RepoManager::getRM()->getReservationRepo()->getById($id);

        if (is_null($reservation)) {
            View::renderError(404);
            return;
        }

        $reservation->setDateStart($reservation->getDateStart());
        $reservation->setDateEnd($reservation->getDateEnd());

        $reservation->setUser(RepoManager::getRM()->getUserRepo()->getById($reservation->getUserId()));
        $reservation->setRental(RepoManager::getRM()->getRentalRepo()->getById($reservation->getRentalId()));
        $reservation->getRental()->setTypeLogement(RepoManager::getRM()->getTypeLogementRepo()->getById($reservation->getRental()->getTypeLogementId()));
        $reservation->getRental()->setAddress(RepoManager::getRM()->getAddressRepo()->getById($reservation->getRental()->getAddressId()));
        $reservation->getRental()->setOwner(RepoManager::getRM()->getUserRepo()->getById($reservation->getRental()->getOwnerId()));
        $reservation->getRental()->getOwner()->setPassword('');
        $reservation->getRental()->getEquipments();

        $data = [
            'title' => 'Réservation - PasChezMoi.com',
            'reservation' => $reservation,
            'tools' => Functions::class
        ];

        $view->render($data);
    }

    /**
     * Affiche la liste des réservations de l'utilisateur
     * pas de paramètre
     * @return void
     */
    public function displayReservations(): void
    {
        $view = new View('reservation:user:list', auth_controller: AuthController::class);

        // Récupération des réservations
        if (AuthController::isOwner()) {
            $reservations = RepoManager::getRM()->getReservationRepo()->getAllForOwner(Session::get(Session::USER)->getId());
        } else {
            $reservations = RepoManager::getRM()->getReservationRepo()->getAllForUser(Session::get(Session::USER)->getId());
        }

        // Si la location n'existe pas
        if (is_null($reservations)) {
            View::renderError(404);
            return;
        }

        foreach ($reservations as $reservation) {
            $user = RepoManager::getRM()->getUserRepo()->getById($reservation->getUserId());
            $user->setPassword('');
            $reservation->setUser($user);
            $reservation->setRental(RepoManager::getRM()->getRentalRepo()->getById($reservation->getRentalId()));
            $reservation->getRental()->setTypeLogement(RepoManager::getRM()->getTypeLogementRepo()->getById($reservation->getRental()->getTypeLogementId()));
            $reservation->getRental()->setAddress(RepoManager::getRM()->getAddressRepo()->getById($reservation->getRental()->getAddressId()));
            $owner = RepoManager::getRM()->getUserRepo()->getById($reservation->getRental()->getOwnerId());
            $owner->setPassword('');
            $reservation->getRental()->setOwner($owner);
            $reservation->getRental()->getEquipments();
        }

        $data = [
            'title' => 'Mes réservations - PasChezMoi.com',
            'reservations' => $reservations
        ];

        $view->render($data);
    }

    /**
     * Supprime une réservation
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $delete_success = RepoManager::getRM()->getReservationRepo()->deleteOne($id);

        if (!$delete_success) {
            $this->redirect('/reservations/list?error=Une erreur est survenue lors de la suppression de la réservation');
        }

        $this->redirect('/reservations/list');
    }
}
