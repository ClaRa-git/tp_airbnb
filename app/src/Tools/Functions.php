<?php

namespace App\Tools;

abstract class Functions
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

    /**
     * Méthode qui formate les dates
     * @param string $date
     * @return string
     */
    public static function formatDate($date): string
    {
        $date_array = explode(' ', $date);
        $date = explode('-', $date_array[0]);
        $time = explode(':', $date_array[1]);

        return $date[2] . '/' . $date[1] . '/' . $date[0] . ' à ' . $time[0] . 'h' . $time[1];
    }

    /**
     * Méthode qui vérifie que la date est supérieure à la date actuelle
     * @param string $date
     * @return bool
     */
    public static function dateIsSuperior($date): bool
    {
        $today = date("Y-m-d H:i:s");
        $timestamp_today = strtotime($today);
        $timestamp_date = strtotime($date);

        // On compare les deux dates, si la date de l'événement est supérieure à la date actuelle, on retourne true
        return $timestamp_date > $timestamp_today;
    }

    /**
     * Méthode qui vérifie que la date de fin est supérieure à la date de début
     * @param string $dateStart
     * @param string $dateEnd
     * @return bool
     */
    public static function dateEndIsSuperior($dateStart, $dateEnd): bool
    {
        $timestamp_dateStart = strtotime($dateStart);
        $timestamp_dateEnd = strtotime($dateEnd);

        // On compare les deux dates, si la date de fin est supérieure à la date de début, on retourne true
        return $timestamp_dateEnd > $timestamp_dateStart;
    }
}
