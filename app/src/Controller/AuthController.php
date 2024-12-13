<?php

namespace App\Controller;

use App\Session;
use App\Model\Entity\User;
use App\Model\Repository\RepoManager;

use Symplefony\Controller;

use Laminas\Diactoros\ServerRequest;
use Symplefony\View;

/**
 * Gestion des fonctionnalités d'authentification
 */
class AuthController extends Controller
{
    /**
     * L'utilisateur est connecté
     * pas de paramètre
     * @return bool
     */
    public static function isAuth(): bool
    {
        return !is_null( Session::get( Session::USER ) );
    }
    
    /**
     * L'utilisateur connecté est un administrateur
     * pas de paramètre
     * @return bool
     */
    public static function isAdmin(): bool
    {
        if( !self::isAuth() ) {
            return false;
        }

        $user = Session::get( Session::USER );

        return $user->getTypeAccount() === User::ROLE_ADMIN;
    }

    /**
     * L'utilisateur connecté est un propriétaire
     * pas de paramètre
     * @return bool
     */
    public static function isOwner(): bool
    {
        if( !self::isAuth() ) {
            return false;
        }

        $user = Session::get( Session::USER );
        
        return $user->getTypeAccount() === User::ROLE_OWNER;
    }

    public static function getUser(): ?User
    {
        if( !self::isAuth() ) {
            return null;
        }

        return Session::get( Session::USER );
    }


    // --- Actions de routes ---

    // - Visiteurs seulement -

    /**
     * Affichage du formulaire de création de compte
     * pas de paramètre
     * @return void
     */
    public function signUp(): void
    {
        $view = new View('auth:sign-up', auth_controller: self::class);

        $data = [
            'title' => 'Créer mon compte - Airbnb.com'
        ];

        $view->render($data);
    }

    /**
     * Traitement du formulaire de création de compte
     * @param ServerRequest $request
     * @return void
     */
    public function processSignUp(ServerRequest $request): void
    {
        $user_data = $request->getParsedBody();

        // On vérifie que l'on recoit bien les données du formulaire
        if (
            !isset($user_data['firstName']) ||
            !isset($user_data['lastName']) ||
            !isset($user_data['email']) ||
            !isset($user_data['password']) ||
            !isset($user_data['typeAccount'])
        ) {
            $this->redirect('/sign-up?error=Erreurlors de la création des champs');
        }

        // On sécurise les données
        $firstName = $this->secureData($user_data['firstName']);
        $lastName = $this->secureData($user_data['lastName']);
        $email = strtolower($this->secureData($user_data['email']));
        $password = $this->secureData($user_data['password']);
        $typeAccount = intval($this->secureData($user_data['typeAccount']));

        $pass_hash = password_hash($password, PASSWORD_BCRYPT);

        // On vérifie si les données sont vides
        if (
            empty($firstName) ||
            empty($lastName) ||
            empty($email) ||
            empty($password) ||
            empty($typeAccount)
        ) {
            $this->redirect('/sign-up?error=Veuillez remplir tous les champs');
        }

        // On vérifie le format de l'email
        if (!$this->validEmail($email)) {
            $this->redirect('/sign-up?error=Le format de l\'email est incorrect');
        }

        // On vérifie le format du mot de passe
        if (!$this->validPassword($password)) {
            $this->redirect('/sign-up?error=Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre');
        }

        // On vérifie si l'email n'est pas déjà utilisé pour le type de compte choisi
        $users = RepoManager::getRM()->getUserRepo()->getAllByEmail($email);
        foreach ($users as $user) {
            if ($user->getEmail() == $email && $user->getTypeAccount() == $typeAccount) {
                $this->redirect('/sign-up?error=Cet email est déjà utilisé');
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
            $this->redirect('/sign-up?error=Une erreur est survenue lors de la création de votre compte');
        } else {
            // On enregistre l'utilisateur correspondant dans la session
            Session::set(Session::USER, $user);

            // On redirige vers une page en fonction du rôle de l'utilisateur
            $redirect_url = match ($user->getTypeAccount()) {
                User::ROLE_USER => '/',
                User::ROLE_OWNER => '/',
                User::ROLE_ADMIN => '/admin' // TODO: Sécurité: page qui redemande le mot de passe par exemple
            };

            $this->redirect($redirect_url);
        }
    }

    /**
     * Affichage du formulaire de connexion
     * pas de paramètre
     * @return void
     */
    public function signIn(): void
    {
        $view = new View('auth:sign-in', auth_controller: self::class);

        $data = [
            'title' => 'Se connecter - Airbnb.com'
        ];

        $view->render($data);
    }

    /**
     * Traitement du formulaire de connexion
     * @param ServerRequest $request
     * @return void
     */
    public function checkCredentials(ServerRequest $request): void
    {
        $user_data = $request->getParsedBody();

        // On vérifie que l'on recoit bien les données du formulaire
        if (
            !isset($user_data['email']) &&
            !isset($user_data['password']) &&
            !isset($user_data['typeAccount'])
        ) {
            $this->redirect('/sign-in?error=Erreur lors de la création des champs');
        }

        // On sécurise les données
        $email = strtolower($this->secureData($user_data['email']));
        $password = $this->secureData($user_data['password']);
        $typeAccount = $this->secureData($user_data['typeAccount']);

        // On vérifie si les données sont vides
        if (
            empty($email) ||
            empty($password) ||
            empty($typeAccount)
        ) {
            $this->redirect('/sign-in?error=Veuillez remplir tous les champs');
        }

        // On vérifie le format de l'email
        if (!$this->validEmail($email)) {
            $this->redirect('/sign-in?error=Le format de l\'email est incorrect');
        }

        // On récupère les utilisateurs et on vérifie si l'utilisateur existe pour le type de compte choisi
        $verifiedUser = RepoManager::getRM()->getUserRepo()->getAllByEmailAndType($email, $typeAccount);

        // On vérifie si l'utilisateur existe
        if (is_null($verifiedUser)) {
            $this->redirect('/sign-in?error=Cet email n\'existe pas');
        }

        // On vérifie le mot de passe
        if (!password_verify($password, $verifiedUser->getPassword())) {
            $this->redirect('/sign-in?error=Le mot de passe est incorrect');
        }

        // On connecte l'utilisateur
        $verifiedUser->setPassword("");
        Session::set(Session::USER, $verifiedUser);

        // On redirige vers une page en fonction du rôle de l'utilisateur
        $redirect_url = match ($verifiedUser->getTypeAccount()) {
            User::ROLE_USER => '/',
            User::ROLE_OWNER => '/',
            User::ROLE_ADMIN => '/admin' // TODO: Sécurité: page qui redemande le mot de passe par exemple
        };

        $this->redirect($redirect_url);
    }

    
    // -- Utilisateurs connectés (tous rôles) --  

    /**
     * Déconnexion de l'utilisateur
     * pas de paramètre
     * @return void
     */
    public function signOut(): void
    {
        Session::remove(Session::USER);

        $this->redirect('/');
    }

    // -- Fonctions de sécurisation
    /**
     * Méthode qui vérifie le format de l'email
     * @param string $email
     * @return bool
     */
    public static function validEmail($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Méthode qui vérifie que le mdp contient au moins 8 caractères, une majuscule, une minuscule et un chiffre
     * @param string $password
     * @return bool
     */
    public static function validPassword($password): bool
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $password);
    }

    /**
     * Méthode qui sécurise les données
     * @param string $data
     * @return string
     */
    public static function secureData($data): string
    {
        return htmlspecialchars(stripslashes(trim($data))); // htmlspecialchars() convertit les caractères spéciaux en entités HTML, stripslashes() supprime les antislashs et trim() supprime les espaces inutiles en début et fin de chaîne
    }
} 