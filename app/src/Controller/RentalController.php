<?php

namespace App\Controller;

use App\Model\Entity\Address;
use App\Model\Entity\Rental;
use App\Model\Entity\User;
use App\Model\Repository\RepoManager;
use App\Session;
use App\Tools\Functions;

use Symplefony\Controller;
use Symplefony\View;

use Laminas\Diactoros\ServerRequest;

class RentalController extends Controller
{
    /**
     * Créer une nouvelle location
     * @param ServerRequest $request
     * @return void
     */
    public function create(ServerRequest $request): void
    {
        $rental_data = $request->getParsedBody();

        $rental = new Rental($rental_data);

        $rental_created = RepoManager::getRM()->getRentalRepo()->create($rental);

        if (is_null($rental_created)) {
            $this->redirect('/rentals/add?error=Une erreur est survenue lors de la création de la location');
        }
    }

    /**
     * Affiche le formulaire d'ajout d'une location
     * pas de paramètre
     * @return void
     */
    public function displayAddRental(): void
    {
        $view = new View('rental:user:create', auth_controller: AuthController::class);

        $data = [
            'title' => 'Ajouter une location - Airbnb.com'
        ];

        $view->render($data);
    }

    /**
     * Affiche la liste des locations
     * pas de paramètre
     * @return void
     */
    public function displayRentals(): void
    {
        $view = new View('rental:user:list', auth_controller: AuthController::class);

        if(!AuthController::isAuth() || Session::get(Session::USER)->getTypeAccount() == User::ROLE_USER) {
            $rentals = RepoManager::getRM()->getRentalRepo()->getAll();
        }
        else {
            $rentals = RepoManager::getRM()->getRentalRepo()->getAllById(Session::get(Session::USER)->getId());
            var_dump(Session::get(Session::USER)->getId());
        }

        $data = [
            'title' => 'Locations - Airbnb.com',
            'rentals' => $rentals
        ];

        $view->render($data);
    }

    /**
     * Affiche la liste des locations
     * pas de paramètre
     * @return void
     */
    public function displayRentalsByOwner(int $id): void
    {
        $view = new View('rental:user:list', auth_controller: AuthController::class);

        $data = [
            'title' => 'Mes locations - Airbnb.com',
            'rentals' => RepoManager::getRM()->getRentalRepo()->getAllById($id)
        ];

        $view->render($data);
    }

    /**
     * Traitement du formulaire de création de location
     * pas de paramètre
     * @return void
     */
    public function processAddRental(ServerRequest $request): void
    {
        $rental_data = $request->getParsedBody();

        // On vérifie que l'on recoit bien les données du formulaire
        if(!isset($rental_data['title']) ||
            !isset($rental_data['price']) ||
            !isset($rental_data['surface']) ||
            !isset($rental_data['description']) ||
            !isset($rental_data['beddings']) ||
            !isset($rental_data['typeLogement_id']) ||
            !isset($rental_data['city']) ||
            !isset($rental_data['country'])
        )
        {
            $this->redirect('/rentals/add?error=Erreur lors de la créationdes champs');
        }

        // On sécurise les données
        $title = Functions::secureData($rental_data['title']);
        $price = Functions::secureData($rental_data['price']);
        $surface = Functions::secureData($rental_data['surface']);
        $description = Functions::secureData($rental_data['description']);
        $beddings = Functions::secureData($rental_data['beddings']);
        $typeLogement_id = Functions::secureData($rental_data['typeLogement_id']);
        $city = Functions::secureData($rental_data['city']);
        $country = Functions::secureData($rental_data['country']);

        // On vérifie si les données sont vides
        if(empty($title) ||
            empty($price) ||
            empty($surface) ||
            empty($description) ||
            empty($beddings) ||
            empty($typeLogement_id) ||
            empty($city) ||
            empty($country)
        )
        {
            $this->redirect('/rentals/add?error=Veuillez remplir tous les champs');
        }

        // On vérifie si le type de logement existe
        $typeLogement = RepoManager::getRM()->getTypeLogementRepo()->getAll();
        $inArray = false;
        foreach($typeLogement as $type){
            if($type->getId() == $typeLogement_id) {
                $inArray = true;
                break;
            }
        }
        if(!$inArray){
            $this->redirect('/rentals/add?error=Le type de logement n\'existe pas');
        }

        // On vérifie la cohérence des données
        if($price <= 0 || $surface <= 0 || $beddings <= 0){
            $this->redirect('/rentals/add?error=Les données ne sont pas cohérentes');
        }

        $address = new Address([
            'city' => $city,
            'country' => $rental_data['country']
        ]);

        $address_created = RepoManager::getRM()->getAddressRepo()->create($address);

        if (is_null($address_created)) {
            $this->redirect('/rentals/add?error=Une erreur est survenue lors de la création de l\'adresse');
        }

        $rental = new Rental([
            'title' => $title,
            'price' => $price,
            'surface' => $surface,
            'description' => $description,
            'beddings' => $beddings,
            'typeLogement_id' => $typeLogement_id,
            'address_id' => $address_created->getId(),
            'owner_id' => Session::get(Session::USER)->getId()
        ]);

        $rental->setAddress($address_created);
        $rental->setOwner(Session::get(Session::USER));     

        $rental_created = RepoManager::getRM()->getRentalRepo()->create($rental);

        if (is_null($rental_created)) {
            $this->redirect('/rentals/add?error=Une erreur est survenue lors de la création de la location');
        }

        // Si pas de données post car aucun coché, on crée un tableau vide
        $equipments = $rental_data['equipments'] ?? [];
        $rental_created->addEquipments($equipments);

        $this->redirect('/');
    }

    /**
     * Affiche le détail d'une location grace à son id
     * @param int
     * @return void
     */
    public function show(int $id): void
    {
        $view = new View('rental:user:detail', auth_controller: AuthController::class);

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

    /**
     * Supprime une location grace à son id
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $delete_success = RepoManager::getRM()->getRentalRepo()->deleteOne($id);

        if(!$delete_success) {
            $this->redirect('/rentals?error=Une erreur est survenue lors de la suppression de la location');
        }

        $this->redirect('/');
    }
}
