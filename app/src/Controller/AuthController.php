<?php

namespace App\Controller;

use Symplefony\Controller;

class AuthController extends Controller
{
    /**
     * Vérifie si l'utilisateur est propriétaire
     * pas de paramètre
     * @return bool
     */
    public static function isOwner(): bool
    {
        // TODO: La vrai vérification de propriétaire
        // à faire avec les variables de session
        return false;
    }

    /**
     * Vérifie si l'utilisateur est connecté
     * pas de paramètre
     * @return bool
     */
    public static function isLogged(): bool
    {
        // TODO: Le vrai contrôle de session
        return false;
    }
}
