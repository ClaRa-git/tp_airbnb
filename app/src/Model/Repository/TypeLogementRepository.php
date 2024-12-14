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
        return 'typesLogement';
    }

    /**
     * Crée un type de logement
     * @param TypeLogement $typeLogement
     * @return TypeLogement|null
     */
    public function create(TypeLogement $typeLogement): ?TypeLogement
    {
        $query = sprintf(
            'INSERT INTO `%s` 
                (`name`) 
                VALUES (:name)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if (!$sth) {
            return null;
        }

        $success = $sth->execute([
            'labelTypeLogement' => $typeLogement->getName()
        ]);

        if (!$success) {
            return null;
        }

        $typeLogement->setId($this->pdo->lastInsertId());

        return $typeLogement;
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
