<?php

namespace App;

use Symplefony\AbstractSession;

/**
 * Classe de gestion de la session du projet.
 * Contient les constantes des noms des clés utilisées.
 */
final class Session extends AbstractSession
{
    /**
     * Utilisateur connecté
     */
    public const USER = 'user';
}
