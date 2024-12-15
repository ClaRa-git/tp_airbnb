<?php

namespace App\Controller;

use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;

use App\Session;
use App\Model\Entity\Address;
use App\Model\Entity\Rental;
use App\Model\Entity\User;
use App\Model\Repository\RepoManager;
use App\Tools\Functions;

class RentalController extends Controller
{
    /**
     * Affiche le formulaire d'ajout d'une location
     * pas de paramètre
     * @return void
     */
    public function displayAddRental(): void
    {
        $view = new View( 'rental:user:create', auth_controller: AuthController::class );

        $types = RepoManager::getRM()->getTypeLogementRepo()->getAll();
        $equipments = RepoManager::getRM()->getEquipmentRepo()->getAll();

        $data = [
            'title' => 'Ajouter une location - PasChezMoi.com',
            'types' => $types,
            'equipments' => $equipments
        ];

        $view->render( $data );
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
        if ( !isset( $rental_data[ 'title' ] ) ||
            !isset( $rental_data[ 'price' ] ) ||
            !isset( $rental_data[ 'surface' ] ) ||
            !isset( $rental_data[ 'description' ] ) ||
            !isset( $rental_data[ 'beddings' ] ) ||
            !isset( $rental_data['typeLogement_id' ] ) ||
            !isset( $rental_data[ 'city' ] ) ||
            !isset( $rental_data[ 'country' ] )
        )
        {
            $this->redirect( '/rentals/add?error=Erreur lors de la création des champs' );
        }

        // On sécurise les données
        $title = Functions::secureData( $rental_data[ 'title' ] );
        $price = Functions::secureData( $rental_data[ 'price' ] );
        $surface = Functions::secureData( $rental_data[ 'surface' ] );
        $description = Functions::secureData( $rental_data[ 'description' ] );
        $beddings = Functions::secureData( $rental_data[ 'beddings' ] );
        $typeLogement_id = Functions::secureData( $rental_data[ 'typeLogement_id' ] );
        $city = strtoupper(Functions::secureData( $rental_data[ 'city' ] ) );
        $country = strtoupper(Functions::secureData( $rental_data[ 'country' ] ) );

        // On vérifie si les données sont vides
        if (
            empty( $title ) ||
            empty( $price ) ||
            empty( $surface ) ||
            empty( $description ) ||
            empty( $beddings ) ||
            empty( $typeLogement_id ) ||
            empty( $city ) ||
            empty( $country )
        )
        {
            $this->redirect( '/rentals/add?error=Veuillez remplir tous les champs' );
        }

        // On vérifie si le type de logement existe
        $typeLogementArray = RepoManager::getRM()->getTypeLogementRepo()->getAll();
        $inArray = false;
        foreach ( $typeLogementArray as $type ) 
        {
            if ( $type->getId() == $typeLogement_id )
            {
                $inArray = true;
                break;
            }
        }

        if ( !$inArray )
        {
            $this->redirect( '/rentals/add?error=Le type de logement n\'existe pas' );
        }

        // On vérifie la cohérence des données
        if ( $price <= 0 || $surface <= 0 || $beddings <= 0 )
        {
            $this->redirect( '/rentals/add?error=Les données ne sont pas cohérentes' );
        }

        $address = new Address( [
            'city' => $city,
            'country' => $country
        ] );

        $address_created = RepoManager::getRM()->getAddressRepo()->create( $address );

        if ( is_null( $address_created ) )
        {
            $this->redirect( '/rentals/add?error=Une erreur est survenue lors de la création de l\'adresse' );
        }

        $rental = new Rental( [
            'title' => $title,
            'price' => $price,
            'surface' => $surface,
            'description' => $description,
            'beddings' => $beddings,
            'typeLogement_id' => $typeLogement_id,
            'address_id' => $address_created->getId(),
            'owner_id' => Session::get( Session::USER )->getId()
        ] );

        $rental_created = RepoManager::getRM()->getRentalRepo()->create( $rental );

        // Si pas de données post car aucun coché, on crée un tableau vide
        $equipments = $rental_data[ 'equipments' ] ?? [];
        $rental_created->addEquipments( $equipments );

        if ( is_null( $rental_created ) )
        {
            $this->redirect( '/rentals/add?error=Une erreur est survenue lors de la création de la location' );
        }

        $this->redirect( '/' );
    }

    /**
     * Affiche la liste des locations
     * pas de paramètre
     * @return void
     */
    public function displayRentals(): void
    {
        $view = new View( 'rental:user:list', auth_controller: AuthController::class );

        // Si l'utilisateur n'est pas connecté ou si c'est un utilisateur, on affiche toutes les locations
        if ( !AuthController::isAuth() || Session::get(Session::USER)->getTypeAccount() == User::ROLE_USER )
        {
            $rentals = RepoManager::getRM()->getRentalRepo()->getAll();

            foreach ( $rentals as $rental )
            {
                $rental->setOwner( RepoManager::getRM()->getUserRepo()->getById( $rental->getOwnerId() ) );
                $rental->getOwner()->setPassword( '' );
                $rental->setAddress( RepoManager::getRM()->getAddressRepo()->getById( $rental->getAddressId() ) );
                $rental->setTypeLogement( RepoManager::getRM()->getTypeLogementRepo()->getById( $rental->getTypeLogementId() ) );
                $rental->getEquipments();
            }
        }
        // Sinon on affiche les locations proposées par l'utilisateur connecté
        else
        {
            $rentals = RepoManager::getRM()->getRentalRepo()->getAllById( Session::get( Session::USER )->getId() );
            
            foreach( $rentals as $rental )
            {
                $rental->setOwner( RepoManager::getRM()->getUserRepo()->getById($rental->getOwnerId() ) );
                $rental->getOwner()->setPassword( '' );
                $rental->setAddress( RepoManager::getRM()->getAddressRepo()->getById( $rental->getAddressId() ) );
                $rental->setTypeLogement( RepoManager::getRM()->getTypeLogementRepo()->getById( $rental->getTypeLogementId() ) );
                $rental->getEquipments();
            }
        }

        $data = [
            'title' => 'Locations - PasChezMoi.com',
            'rentals' => $rentals
        ];

        $view->render( $data );
    }

    /**
     * Affiche la liste des locations d'un propriétaire
     * pas de paramètre
     * @return void
     */
    public function displayRentalsByOwner( int $id ): void
    {
        $view = new View( 'rental:user:list', auth_controller: AuthController::class );

        $data = [
            'title' => 'Mes locations - PasChezMoi.com',
            'rentals' => RepoManager::getRM()->getRentalRepo()->getAllById( $id )
        ];

        $view->render( $data );
    }

    /**
     * Affiche le détail d'une location grace à son id
     * @param int
     * @return void
     */
    public function show( int $id ): void
    {
        $view = new View( 'rental:user:detail', auth_controller: AuthController::class );

        // Récupération de la location
        $rental = RepoManager::getRM()->getRentalRepo()->getById( $id );

        // Si la location n'existe pas (l'id est incorrect)
        if ( is_null( $rental ) )
        {
            View::renderError( 404 );
            return;
        }

        $data = [
            'title' => $rental->getTitle() . ' - PasChezMoi.com',
            'rental' => $rental
        ];

        $view->render( $data );
    }

    /**
     * Supprime une location grace à son id
     * @param int $id
     * @return void
     */
    public function delete( int $id ): void
    {
        $delete_success = RepoManager::getRM()->getRentalRepo()->deleteOne( $id );

        if (!$delete_success )
        {
            $this->redirect( '/rentals?error=Une erreur est survenue lors de la suppression de la location' );
        }

        $this->redirect( '/' );
    }
}