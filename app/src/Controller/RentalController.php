<?php

namespace App\Controller;

use App\Model\Entity\Address;
use App\Model\Entity\Rental;
use App\Model\Repository\RepoManager;
use App\Tools\Functions;

use Symplefony\Controller;
use Symplefony\View;

use Laminas\Diactoros\ServerRequest;
use Symplefony\AbstractSession;

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
        $view = new View('rental:user:create');

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
        $view = new View('rental:user:list');

        $data = [
            'title' => 'Locations - Airbnb.com',
            'rentals' => RepoManager::getRM()->getRentalRepo()->getAll() 
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
        $view = new View('rental:user:list');

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
    public function processAddRental(): void
    {
        if(isset($_POST['title']) &&
            isset($_POST['price']) &&
            isset($_POST['surface']) &&
            isset($_POST['description']) &&
            isset($_POST['beddings']) &&
            isset($_POST['city']) &&
            isset($_POST['country']) &&
            isset($_POST['typeLogementId'])
         ){
            $title = Functions::secureData($_POST['title']);
            $price = floatval(Functions::secureData($_POST['price']));
            $surface = intval(Functions::secureData($_POST['surface']));
            $description = Functions::secureData($_POST['description']);
            $beddings = intval(Functions::secureData($_POST['beddings']));
            $city = strtoupper(Functions::secureData($_POST['city']));
            $country = strtoupper(Functions::secureData($_POST['country']));
            $typeLogementId = intval(Functions::secureData($_POST['typeLogementId']));

            if(empty($title) ||
                empty($price) ||
                empty($surface) ||
                empty($description) ||
                empty($beddings) ||
                empty($city) ||
                empty($country) ||
                empty($typeLogementId)
            ){
                $this->redirect('/rentals/add?error=Tous les champs sont obligatoires');
            }

            if($price <= 0 ||
                $surface <= 0 ||
                $beddings <= 0
            ){
                $this->redirect('/rentals/add?error=Les champs prix, surface, nombre de couchages doivent être supérieurs à 0');
            }

            $typeLogementArray = RepoManager::getRM()->getTypeLogementRepo()->getAll();

            $typeLogementIdArray = array_map(function($typeLogement){
                return $typeLogement->getId();
            }, $typeLogementArray);
            if(!in_array($typeLogementId, $typeLogementIdArray)){
                $this->redirect('/rentals/add?error=Le type de logement est incorrect');
            }

            $address = RepoManager::getRM()->getAddressRepo()->create(new Address([
                'city' => $city,
                'country' => $country
            ]));

            $rental = RepoManager::getRM()->getRentalRepo()->create(new Rental([
                'title' => $title,
                'price' => $price,
                'surface' => $surface,
                'description' => $description,
                'beddings' => $beddings,
                'typeLogement_id' => $typeLogementId,
                'address_id' => $address->getId(),
                'owner_id' => AbstractSession::get('user')->getId()
            ]));

            if(is_null($rental)){
                $this->redirect('/rentals/add?error=Une erreur est survenue lors de la création de la location');
            } else {
                $this->redirect('/');
            }

        }
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
