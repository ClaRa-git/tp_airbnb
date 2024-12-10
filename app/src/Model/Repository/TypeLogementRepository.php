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

    /**
     * Retourne le nom de la table Rental
     * pas de paramètre
     * @return string
     */
    private function getRentalName(): string
    {
        return 'rental';
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

    /**
     * Récupère le type de logement d'une annonce
     * @param int $id
     * @return TypeLogement
     */
    public function getTypeForRental(int $id): ?TypeLogement
    {
        $query = sprintf(
            'SELECT tl.* FROM `%1$s` as tl
            JOIN `%2$s` as r ON tl.id = r.type_logement_id
            WHERE r.id = :id',
            $this->getTableName(),
            $this->getRentalName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null;}

        $success = $sth->execute([
            'id' => $id
        ]);

        if(!$success) { return null;}

        $type_logement_data = $sth->fetch();

        return new TypeLogement($type_logement_data);
    }
}
