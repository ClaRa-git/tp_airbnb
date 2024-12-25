<?php

namespace App\Controller;

use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;

use App\App;
use App\Model\Entity\User;
use App\Model\Repository\RepoManager;
use App\Session;
use App\Tools\Functions;

class UserController extends Controller
{
    // --- Actions de routes ---

    // - Visiteurs seulement -

    /**
     * Affichage du formulaire de création de compte
     * pas de paramètre
     * @return void
     */
    public function signUp(): void
    {
        $view = new View('user:sign-up', auth_controller: AuthController::class);
        $userConst = [
            'ROLE_USER' => User::ROLE_USER,
            'ROLE_OWNER' => User::ROLE_OWNER,
            'ROLE_ADMIN' => User::ROLE_ADMIN
        ];

        $data = [
            'title' => 'Créer mon compte - PasChezMoi.com',
            'userConst' => $userConst
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
        $firstName = Functions::secureData($user_data['firstName']);
        $lastName = Functions::secureData($user_data['lastName']);
        $email = strtolower(Functions::secureData($user_data['email']));
        $password = Functions::secureData($user_data['password']);
        $typeAccount = Functions::secureData($user_data['typeAccount']);

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
        if (!Functions::validEmail($email)) {
            $this->redirect('/sign-up?error=Le format de l\'email est incorrect');
        }

        // On vérifie le format du mot de passe
        if (!Functions::validPassword($password)) {
            $this->redirect('/sign-up?error=Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre');
        }

        // Chiffrement du mot de passe
        $pass_hash = App::strHash($password);

        // On vérifie si l'email n'est pas déjà utilisé pour le type de compte choisi
        $user = RepoManager::getRM()->getUserRepo()->getByEmailAndType($email, $typeAccount);

        if (!is_null($user)) {
            $this->redirect('/sign-up?error=Cet email n\'est pas disponible');
        }

        $user = new User([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'password' => $pass_hash,
            'typeAccount' => $typeAccount
        ]);

        $user_created = RepoManager::getRM()->getUserRepo()->create($user);

        // On vérifie si l'utilisateur a bien été créé
        if (is_null($user_created)) {
            $this->redirect('/sign-up?error=Une erreur est survenue lors de la création de votre compte');
        } else {
            $user_created->setPassword("");

            // On enregistre l'utilisateur correspondant dans la session
            Session::set(Session::USER, $user);

            // On redirige vers une page en fonction du rôle de l'utilisateur
            $redirect_url = match ($user->getTypeAccount()) {
                User::ROLE_USER => '/',
                User::ROLE_OWNER => '/'
            };

            $this->redirect($redirect_url);
        }
    }
}
