<?php

namespace Symplefony;

/**
 * Classe de base pour la gestion de la session
 */
abstract class AbstractSession
{    
    /**
     * Enregistre des données dans la session à la clé indiquée
     *
     * @param  string $key Clé de session à utiliser
     * @param  mixed $value données à enregistrer
     * 
     * @return void
     */
    public static function set( string $key, mixed $value ): void
    {
        $_SESSION[ $key ] = $value;
    }
    
    /**
     * Récupère les données enregistrées dans la session à la clé indiquée
     *
     * @param  mixed $key Clé de session à utiliser
     * 
     * @return mixed Données | null si clé inexistante
     */
    public static function get( string $key ): mixed
    {
        if( !isset( $_SESSION[ $key ] ) ) {
            return null;
        }

        return $_SESSION[ $key ];
    }
    
    /**
     * Supprime la clé de session indiquée
     *
     * @param  mixed $key
     * 
     * @return void
     */
    public static function remove( string $key ): void
    {
        if( ! self::get( $key ) ) {
            return;
        }

        unset( $_SESSION[ $key ] );
    }
}