<?php

/**
 * Classe de démarrage de l'application
 */

// Déclaration du namespace de ce fichier
namespace App;

use Exception;
use Throwable;

use App\Controller\PageController;
use App\Controller\RentalController;
use App\Controller\ReservationController;
use App\Controller\UserController;

use Symplefony\View;

use MiladRahimi\PhpRouter\Router;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;

final class App
{
    private static ?self $app_instance = null;

    // Le routeur de l'application
    private Router $router;
    public function getRouter(): Router
    {
        return $this->router;
    }

    // Récupération de l'instance de l'application
    public static function getApp(): self
    {
        // Si l'instance n'existe pas encore on la crée
        if (is_null(self::$app_instance)) {
            self::$app_instance = new self();
        }

        return self::$app_instance;
    }

    // Démarrage de l'application
    public function start(): void
    {
        session_start(); // Démarrage de la session
        $this->registerRoutes();
        $this->startRouter();
    }

    private function __construct()
    {
        // Création du routeur
        $this->router = Router::create();
    }

    // Enregistrement des routes de l'application
    private function registerRoutes(): void
    {
        // -- Formats des paramètres --
        // {id} doit être un nombre
        $this->router->pattern('id', '\d+');

        //TODO: Ajouter les routes de l'application  par groupes avec middleware

        // -- Pages communes --
        $this->router->get('/', [RentalController::class, 'displayRentals']);
        $this->router->get('/mentions-legales', [PageController::class, 'legalMentions']);

        // Page d'affichage des locations d'un utlisateur
        $this->router->get('/rentals/users/{id}', [RentalController::class, 'displayRentalsByOwner']);

        // Page de création de compte
        $this->router->get('/registration', [UserController::class, 'displayRegistration']);
        $this->router->post('/registration', [UserController::class, 'processRegistration']);

        // Page de login
        $this->router->get('/login', [UserController::class, 'displayLogin']);
        $this->router->post('/login', [UserController::class, 'processLogin']);

        // Page de logout
        $this->router->get('/logout', [UserController::class, 'processLogout']);

        // Page d'affichage d'une location
        $this->router->get('/rentals/{id}', [RentalController::class, 'show']);

        // Page de création d'une location
        $this->router->get('/rentals/add', [RentalController::class, 'displayAddRental']);
        $this->router->post('/rentals/add', [RentalController::class, 'processAddRental']);

        // Page de liste des réservations
        $this->router->get('/reservations', [ReservationController::class, 'show']);
        // Page d'ajout de réservation
        $this->router->get('/rentals/{id}/reservations', [ReservationController::class, 'displayAddReservation']);
    }

    // Démarrage du routeur
    private function startRouter(): void
    {
        try {
            $this->router->dispatch();
        }
        // Page 404 avec status HTTP adequat pour les pages non listée dans le routeur
        catch (RouteNotFoundException $e) {
            View::renderError(404);
        }
        // Erreur 500 pour tout autre problème temporaire ou non
        catch (Throwable $e) {
            View::renderError(500);
            var_dump($e);
        }
    }

    private function __clone() {}
    public function __wakeup()
    {
        throw new Exception("Non c'est interdit !");
    }
}
