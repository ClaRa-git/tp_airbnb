<?php

namespace App\Model\Repository;

use App\Model\Entity\TypeLogement;
use Symplefony\Model\Repository;

class TypeLogementRepository extends Repository
{
    /**
     * Retourne le nom de la table en base de données
     * pas de paramètre
     * @return string
     */
    protected function getTableName(): string
    {
        return 'type_logement';
    }

    public function getById(int $id): ?TypeLogement
    {
        return $this->readById(TypeLogement::class, $id);
    }

    public function getAll(): array
    {
        return $this->readAll(TypeLogement::class);
    }
}