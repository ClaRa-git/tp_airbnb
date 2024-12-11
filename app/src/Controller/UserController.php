<?php

namespace App\Controller;

use App\Model\Entity\User;
use App\Model\Repository\RepoManager;
use App\Tools\Functions;
use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;


class UserController extends Controller
{
    /**
     * Créer un nouvel utilisateur
     * @param ServerRequest $request
     * @return void
     */
    public function create(ServerRequest $request): void
    {
        $user_data = $request->getParsedBody();

        $user = new User($user_data);

        $user_created = RepoManager::getRM()->getUserRepo()->create($user);

        if (is_null($user_created)) {
            $this->redirect('/user/registration?error=Une erreur est survenue lors de la création de votre compte');
        }

        $this->redirect('/user/login');
    }

    /**
     * Affichage du formulaire de création de compte
     * pas de paramètre
     * @return void
     */
    public function displaySubscribe(): void
    {
        $view = new View('user:create-account');

        $data = [
            'title' => 'Créer mon compte - Airbnb.com'
        ];

        $view->render($data);
    }

    /**
     * Affichage du formulaire de connexion
     * pas de paramètre
     * @return void
     */
    public function displayLogin(): void
    {
        $view = new View('user:login');

        $data = [
            'title' => 'Se connecter - Airbnb.com'
        ];

        $view->render($data);
    }

    /**
     * Traitement du formulaire d'registration
     * pas de paramètre
     * @return void
     */
    public function processSubscribe(): void
    {
        // On vérifie que l'on recoit bien les données du formulaire
        if (isset($_POST['first_name']) &&
            isset($_POST['last_name']) &&
            isset($_POST['email']) &&
            isset($_POST['password']) &&
            isset($_POST['type_compte'])
        ){

            // On sécurise les données
            $first_name = Functions::secureData($_POST['first_name']);
            $last_name = Functions::secureData($_POST['last_name']);
            $email = strtolower(Functions::secureData($_POST['email']));
            $password = Functions::secureData($_POST['password']);

            $pass_hash = password_hash($password, PASSWORD_BCRYPT);

            // On vérifie si les données sont vides
            if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
                $this->redirect('/registration?error=Veuillez remplir tous les champs');
            }

            // On vérifie le format de l'email
            if (!Functions::validEmail($email)) {
                $this->redirect('/registration?error=Le format de l\'email est incorrect');
            }

            // On vérifie le format du mot de passe
            if (!Functions::validPassword($password)) {
                $this->redirect('/registration?error=Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre');
            }

            // On vérifie si l'email n'est pas déjà utilisé
            $user = RepoManager::getRM()->getUserRepo()->getByEmail($email);
            
            if($user != null) {
                $this->redirect('/registration?error=Cet email est déjà utilisé');
            }

            // On crée un nouvel utilisateur
            $user = new User([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => $pass_hash
            ]);

            $user_created = RepoManager::getRM()->getUserRepo()->create($user);

            if (is_null($user_created)) {
                $this->redirect('/registration?error=Une erreur est survenue lors de la création de votre compte');
            } else {
                $this->redirect('/login');
            }
        }
    }

    /**
     * Traitement du formulaire de connexion
     * pas de paramètre
     * @return void
     */
    public function processLogin(): void
    {
        // On vérifie que l'on recoit bien les données du formulaire
        if (isset($_POST['email']) &&
        isset($_POST['password'])
        ){

            // On sécurise les données
            $email = strtolower(Functions::secureData($_POST['email']));
            $password = Functions::secureData($_POST['password']);

            // On vérifie si les données sont vides
            if (empty($email) || empty($password)) {
                $this->redirect('/login?error=Veuillez remplir tous les champs');
            }

            // On vérifie le format de l'email
            if (!Functions::validEmail($email)) {
                $this->redirect('/login?error=Le format de l\'email est incorrect');
            }

            // On récupère l'utilisateur
            $user = RepoManager::getRM()->getUserRepo()->getByEmail($email);

            // On vérifie si l'utilisateur existe
            if ($user == null) {
                $this->redirect('/login?error=Cet email n\'existe pas');
            }

            // On vérifie le mot de passe
            if (!password_verify($password, $user->getPassword())) {
                $this->redirect('/login?error=Le mot de passe est incorrect');
            }

            // On connecte l'utilisateur
            $user->setPassword("");
            $_SESSION['user'] = $user;

            $this->redirect('/');
        }
    }

    /**
     * Déconnexion de l'utilisateur
     * pas de paramètre
     * @return void
     */
    public function processLogout(): void
    {
        session_destroy();

        $this->redirect('/');
    }
}
