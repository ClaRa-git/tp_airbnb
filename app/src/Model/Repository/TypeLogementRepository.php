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
        return 'types_logement';
    }

    /**
     * Récupère un type de logement par son id
     * @param int $id
     * @return TypeLogement|null
     */
    public function getById(int $id): ?TypeLogement
    {
        return $this->readById(TypeLogement::class, $id);
    }

    /**
     * Récupère tous les types de logement
     * pas de paramètre
     * @return array
     */
    public function getAll(): array
    {
        return $this->readAll(TypeLogement::class);
    }

}
