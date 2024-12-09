<?php

namespace App\Model\Repository;

use App\Model\Entity\Rental;
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
