<?php

namespace App\Tools;

abstract Class Functions
{
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