<?php

namespace App\Controller;

use App\Model\Entity\Address;
use App\Model\Entity\Rental;
use App\Model\Repository\RepoManager;
use App\Tools\Functions;
use Laminas\Diactoros\ServerRequest;
use Symplefony\Controller;
use Symplefony\View;

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
            $this->redirect('/rental/add?error=Une erreur est survenue lors de la création de la location');
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
            'forOwner' => false,
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
            'forOwner' => true,
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
            isset($_POST['number-address']) &&
            isset($_POST['street']) &&
            isset($_POST['city']) &&
            isset($_POST['country']) &&
            isset($_POST['complement']) &&
            isset($_POST['type-logement'])
         ){
            $title = Functions::secureData($_POST['title']);
            $price = Functions::secureData($_POST['price']);
            $surface = Functions::secureData($_POST['surface']);
            $description = Functions::secureData($_POST['description']);
            $beddings = Functions::secureData($_POST['beddings']);
            $number_address = Functions::secureData($_POST['number-address']);
            $street = strtoupper(Functions::secureData($_POST['street']));
            $city = strtoupper(Functions::secureData($_POST['city']));
            $country = strtoupper(Functions::secureData($_POST['country']));
            $complement = strtoupper(Functions::secureData($_POST['complement']));
            $type_logement = Functions::secureData($_POST['type-logement']);

            if(empty($title) ||
                empty($price) ||
                empty($surface) ||
                empty($description) ||
                empty($beddings) ||
                empty($number_address) ||
                empty($street) ||
                empty($city) ||
                empty($country) ||
                empty($type_logement)
            ){
                $this->redirect('/rental/add?error=Tous les champs sont obligatoires');
            }

            if(empty($complement)){
                $complement = "";
            }

            if(!is_numeric($price) ||
                !is_numeric($surface) ||
                !is_numeric($beddings) ||
                !is_numeric($number_address)
            ){
                $this->redirect('/rental/add?error=Les champs prix, surface, nombre de couchages et numéro de rue doivent être des chiffres');
            }

            if($price <= 0 ||
                $surface <= 0 ||
                $beddings <= 0 ||
                $number_address <= 0
            ){
                $this->redirect('/rental/add?error=Les champs prix, surface, nombre de couchages et numéro de rue doivent être supérieurs à 0');
            }

            if($type_logement != 1 && $type_logement != 2 && $type_logement != 3){
                $this->redirect('/rental/add?error=Le type de logement est incorrect');
            }

            $address = RepoManager::getRM()->getAddressRepo()->create(new Address([
                'number' => $number_address,
                'street' => $street,
                'city' => $city,
                'country' => $country,
                'complement' => $complement
            ]));

            $rental = RepoManager::getRM()->getRentalRepo()->create(new Rental([
                'title' => $title,
                'price' => $price,
                'surface' => $surface,
                'description' => $description,
                'beddings' => $beddings,
                'type_logement_id' => $type_logement,
                'address_id' => $address->getId(),
                'owner_id' => 1//  $_SESSION['user_id']
            ]));

            if(is_null($rental)){
                $this->redirect('/rental/add?error=Une erreur est survenue lors de la création de la location');
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
