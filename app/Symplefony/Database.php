<?php

namespace Symplefony;

use Exception;
use PDO;

class Database
{
    private const PDO_OPTIONS = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];
    
    private static ?PDO $pdo_instance = null;

    public static function getPDO(): PDO
    {
        if( is_null( self::$pdo_instance ) ) {
            $dsn = sprintf( 'mysql:host=%s;dbname=%s', $_ENV['db_host'], $_ENV['db_name'] );

            self::$pdo_instance = new PDO( $dsn, $_ENV['db_user'], $_ENV['db_pass'], self::PDO_OPTIONS );
        }
        
        return self::$pdo_instance;
    }
    
    private function __construct() { }
    private function __clone() { }
    public function __wakeup()
    {
        throw new Exception( "Non c'est interdit !" );
    }
}