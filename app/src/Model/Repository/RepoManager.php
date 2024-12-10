<?php

namespace App\Model\Repository;

use Symplefony\Database;
use Symplefony\Model\RepositoryManagerTrait;

class RepoManager
{
    use RepositoryManagerTrait;
    
    private function __construct()
    {
        $pdo = Database::getPDO();
    }
}
