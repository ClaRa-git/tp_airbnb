<?php
/**
 * Classe de démarrage de l'application
 */

// Déclaration du namespace de ce fichier
namespace App;

use App\Controller\AdminController;
use App\Controller\AuthController;
use Exception;
use Throwable;

use App\Controller\PageController;
use App\Controller\RentalController;
use App\Controller\ReservationController;
use App\Controller\UserController;
use App\Middleware\AdminMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\VisitorMiddleware;
use Symplefony\Security;
use Symplefony\View;

use MiladRahimi\PhpRouter\Router;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;
use MiladRahimi\PhpRouter\Routing\Attributes;

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

    /**
     * Hache une chaîne de caractères en servant du "sel" et du "poivre" définis dans .env
     *
     * @param  string $str Chaîne à hacher
     * 
     * @return string Résultat
     */
    public static function strHash(string $str): string
    {
        return Security::strButcher($str, $_ENV['security_salt'], $_ENV['security_pepper']);
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
        // -- Pages d'admin --
        $adminAttributes = [
            Attributes::PREFIX => '/admin',
            Attributes::MIDDLEWARE => [AdminMiddleware::class]
        ];

        $this->router->group($adminAttributes, function (Router $router) {
            $router->get('', [AdminController::class, 'dashboard']);

            // -- User --
            // Ajout
            $router->get('/users/add', [UserController::class,
                'add'
            ]);
            $router->post('/users', [UserController::class, 'create']);
            // Liste
            $router->get('/users', [UserController::class, 'index']);
            // Détail
            $router->get('/users/{id}', [UserController::class, 'show']);
            $router->post('/users/{id}', [UserController::class, 'update']);
            // Suppression
            $router->get('/users/{id}/delete', [UserController::class, 'delete']);

            // -- Rental -- TODO: Ajouter les routes de l'application  par groupes avec middleware
            // --Equipments -- TODO: Ajouter les routes de l'application  par groupes avec middleware
            // -- TypeLogement -- TODO: Ajouter les routes de l'application  par groupes avec middleware
            // -- Reservation -- TODO: Ajouter les routes de l'application  par groupes avec middleware

        });

        // -- Pages communes --
        $this->router->get('/', [RentalController::class, 'displayRentals']);
        $this->router->get('/mentions-legales', [PageController::class, 'legalMentions']);

        // -- Visiteurs (non-connectés) --
        $visitorAttributes = [
            Attributes::MIDDLEWARE => [VisitorMiddleware::class]
        ];

        $this->router->group($visitorAttributes, function (Router $router) {
            // sign-in
            $router->get('/sign-in', [AuthController::class, 'signIn']);
            $router->post('/sign-in', [AuthController::class, 'checkCredentials']);

            // Page de création de compte
            $this->router->get('/sign-up', [AuthController::class, 'signUp']);
            $this->router->post('/sign-up', [AuthController::class, 'processSignUp']);
        });

        // -- Utilisateurs connectés (tous rôles) --
        $visitorAttributes = [
            Attributes::MIDDLEWARE => [AuthMiddleware::class]
        ];

        $this->router->group($visitorAttributes, function (Router $router) {
            // Logout
            $router->get('/sign-out', [AuthController::class,
                'signOut'
            ]);
        });

        // Page d'affichage des locations d'un utlisateur
        $this->router->get('/rentals/users/{id}', [RentalController::class, 'displayRentalsByOwner']);

        // Page de suppression d'une location
        $this->router->get('/rentals/users/delete/{id}', [RentalController::class, 'delete']);

        // Page d'affichage d'une location
        $this->router->get('/rentals/{id}', [RentalController::class, 'show']);

        // Page de création d'une location
        $this->router->get('/rentals/add', [RentalController::class, 'displayAddRental']);
        $this->router->post('/rentals/add', [RentalController::class, 'processAddRental']);

        // Page de suppression d'une location
        $this->router->get('/rentals/{id}/delete', [RentalController::class, 'delete']);

        // Page de liste des réservations
        $this->router->get('/reservations', [ReservationController::class, 'show']);
        // Page d'ajout de réservation
        $this->router->get('/reservations/add?id={id}', [ReservationController::class, 'displayAddReservation']);
        $this->router->get('/reservations/add', [ReservationController::class, 'displayAddReservation']);

        // Ajout de réservation
        $this->router->post('/reservations/add', [ReservationController::class, 'create']);
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
