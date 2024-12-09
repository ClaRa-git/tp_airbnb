<?php

namespace Symplefony\Model;

use Exception;

trait RepositoryManagerTrait
{
    private static ?self $rm_instance = null;

    public static function getRM(): self
    {
        if( is_null( self::$rm_instance ) ) {
            self::$rm_instance = new self();
        }

        return self::$rm_instance;
    }

    public function __wakeup() {
        throw new Exception( "Non c'est interdit !" );
    }
    private function __construct() { }
    private function __clone() { }
}