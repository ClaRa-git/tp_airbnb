<?php

namespace App\Controller;

use App\Model\Entity\User;
use App\Model\Repository\RepoManager;
use App\Tools\Functions;

use Symplefony\Controller;
use Symplefony\View;

use Laminas\Diactoros\ServerRequest;
use Symplefony\AbstractSession;

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
            $this->redirect('/users/registration?error=Une erreur est survenue lors de la création de votre compte');
        }

        $this->redirect('/users/login');
    }

    /**
     * Affichage du formulaire de création de compte
     * pas de paramètre
     * @return void
     */
    public function displayRegistration(): void
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
    public function processRegistration(ServerRequest $request): void
    {
        $user_data = $request->getParsedBody();

        // On vérifie que l'on recoit bien les données du formulaire
        if (!isset($user_data['firstName']) ||
            !isset($user_data['lastName']) ||
            !isset($user_data['email']) ||
            !isset($user_data['password']) ||
            !isset($user_data['typeAccount'])
        ){
            $this->redirect('/registration?error=Erreurlors de la création des champs');
        }

        // On sécurise les données
        $firstName = Functions::secureData($user_data['firstName']);
        $lastName = Functions::secureData($user_data['lastName']);
        $email = strtolower(Functions::secureData($user_data['email']));
        $password = Functions::secureData($user_data['password']);
        $typeAccount = intval(Functions::secureData($user_data['typeAccount']));

        $pass_hash = password_hash($password, PASSWORD_BCRYPT);

        // On vérifie si les données sont vides
        if (empty($firstName) ||
            empty($lastName) ||
            empty($email) ||
            empty($password) ||
            empty($typeAccount)
        ){
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

        // On vérifie si l'email n'est pas déjà utilisé pour le type de compte choisi
        $users = RepoManager::getRM()->getUserRepo()->getAllByEmail($email);
        foreach($users as $user) {
            if ($user->getEmail() == $email && $user->getTypeAccount() == $typeAccount) {
                $this->redirect('/registration?error=Cet email est déjà utilisé');
            }
        }

        // On crée un nouvel utilisateur
        $user = new User([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'password' => $pass_hash,
            'typeAccount' => $typeAccount
        ]);

        $user_created = RepoManager::getRM()->getUserRepo()->create($user);

        if (is_null($user_created)) {
            $this->redirect('/registration?error=Une erreur est survenue lors de la création de votre compte');
        } else {
            $this->redirect('/login');
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
        if (isset($user_data['email']) &&
        isset($user_data['password']) &&
        isset($user_data['typeAccount'])
        ){

            // On sécurise les données
            $email = strtolower(Functions::secureData($user_data['email']));
            $password = Functions::secureData($user_data['password']);
            $typeAccount = Functions::secureData($user_data['typeAccount']);

            // On vérifie si les données sont vides
            if (empty($email) ||
                empty($password) ||
                empty($typeAccount)
            ){
                $this->redirect('/login?error=Veuillez remplir tous les champs');
            }

            // On vérifie le format de l'email
            if (!Functions::validEmail($email)) {
                $this->redirect('/login?error=Le format de l\'email est incorrect');
            }

            // On récupère les utilisateurs et on vérifie si l'utilisateur existe pour le type de compte choisi
            $users = RepoManager::getRM()->getUserRepo()->getAllByEmail($email);
            foreach($users as $user) {
                if ($user->getEmail() == $email && $user->getTypeAccount() == $typeAccount) {
                    $verifiedUser = $user;
                }
            }

            // On vérifie le mot de passe
            if (!password_verify($password, $verifiedUser->getPassword())) {
                $this->redirect('/login?error=Le mot de passe est incorrect');
            }

            // On connecte l'utilisateur
            $verifiedUser->setPassword("");
            AbstractSession::set('user', $verifiedUser);

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
