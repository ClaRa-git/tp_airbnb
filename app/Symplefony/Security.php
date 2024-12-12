<?php

namespace Symplefony;

/**
 * Contient des outils liés à la sécurité
 */
final class Security
{	
	/**
	 * "Le boucher de la chaîne de caractères" : 
     * Hache une chaîne de caractère avec SHA512 en y incluant le "sel" et le "poivre",
     * re-"assaisonne" et re-hache le résultat, et ceci pour un nombre de fois défini (10 par défaut)
	 *
	 * @param  string $str Chaîne de caractères à hacher
	 * @param  string $salt Chaîne de caractères à utiliser comme "sel"
	 * @param  string $pepper Chaîne de caractères à utiliser comme "poivre"
	 * @param  int $loops Nombre d'itérations à effectuer (défaut : 10)
     * 
	 * @return string Le résultat du hachage
	 */
	public static function strButcher( string $str, string $salt, string $pepper, int $loops = 10 ): string
	{
        for( $i = 0; $i < $loops; $i ++ ) {
            $str = hash( 'sha512', $salt . $str . $pepper );
        }

		return $str;
	}
}