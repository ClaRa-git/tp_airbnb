<?php

namespace App\Controller;

use App\Model\Entity\User;
use App\Model\Repository\RepoManager;
use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;


class UserController extends Controller
{
    public function create(ServerRequest $request): void
    {
        $user_data = $request->getParsedBody();

        $user = new User($user_data);

        $user_created = RepoManager::getRM()->getUserRepo()->create($user);

        if (is_null($user_created)) {
            // TODO: gérer une erreur
            $this->redirect('/user/inscription');
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
     * Traitement du formulaire de connexion
     * pas de paramètre
     * @return void
     */
    public function processSubscribe(): void
    {
        // On vérifie que l'on recoit bien les données du formulaire
        if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['password'])) {

            // On sécurise les données
            $first_name = $this->secureData($_POST['first_name']);
            $last_name = $this->secureData($_POST['last_name']);
            $email = strtolower($this->secureData($_POST['email']));
            $password = $this->secureData($_POST['password']);

            $pass_hash = password_hash($password, PASSWORD_BCRYPT);

            // On vérifie si les données sont vides
            if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
                $error = 'Veuillez remplir tous les champs';                
                $this->redirect('/inscription?error=Veuillez remplir tous les champs');

            // On vérifie le format de l'email
            } else if (!$this->validEmail($email)) {
                $this->redirect('/inscription?error=Le format de l\'email est incorrect');

            // On vérifie le format du mot de passe
            } else if (!$this->validPassword($password)) {
                $this->redirect('/inscription?error=Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre');
            } else {
                // On vérifie si l'email n'est pas déjà utilisé
                $user = RepoManager::getRM()->getUserRepo()->getByEmail($email);
                
                if($user != null) {
                    $this->redirect('/inscription?error=Cet email est déjà utilisé');
                }
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
                $this->redirect('/inscription?error=Une erreur est survenue lors de la création de votre compte');
            } else {
                $this->redirect('/login');
            }
        }
    }

    /**
     * Méthode qui vérifie le format de l'email
     * @param string $email
     * @return bool
     */
    public function validEmail($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Méthode qui vérifie que le mdp contient au moins 8 caractères, une majuscule, une minuscule et un chiffre
     * @param string $password
     * @return bool
     */
    public function validPassword($password): bool
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $password);
    }

    /**
     * Méthode qui sécurise les données
     * @param string $data
     * @return string
     */
    public function secureData($data): string
    {
        return htmlspecialchars(stripslashes(trim($data))); // htmlspecialchars() convertit les caractères spéciaux en entités HTML, stripslashes() supprime les antislashs et trim() supprime les espaces inutiles en début et fin de chaîne
    }

}
