<?php

namespace App\Model\Repository;

use Symplefony\Model\Repository;

use App\Model\Entity\TypeLogement;

class TypeLogementRepository extends Repository
{
    /**
     * Retourne le nom de la table en base de données
     * pas de paramètre
     * @return string
     */
    protected function getTableName(): string { return 'typesLogement'; }

    /**
     * Crée un type de logement
     * @param TypeLogement $typeLogement
     * @return TypeLogement|null
     */
    public function create( TypeLogement $typeLogement ): ?TypeLogement
    {
        $query = sprintf(
            'INSERT INTO `%s` 
                (`name`) 
                VALUES (:name)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        if ( !$sth ) {
            return null;
        }

        $success = $sth->execute( [
            'labelTypeLogement' => $typeLogement->getName()
        ] );

        if ( !$success ) {
            return null;
        }

        $typeLogement->setId( $this->pdo->lastInsertId() );

        return $typeLogement;
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
     * Récupère un type de logement par son id
     * @param int $id
     * @return TypeLogement|null
     */
    public function getById( int $id ): ?TypeLogement
    {
        return $this->readById( TypeLogement::class, $id );
    }

    /**
     * Met à jour un type de logement
     * @param TypeLogement $typeLogement
     * @return TypeLogement|null
     */
    public function update( TypeLogement $typeLogement ): ?TypeLogement
    {
        $query = sprintf(
            'UPDATE `%s` 
                SET `name` = :name
                WHERE `id` = :id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        if ( !$sth ) {
            return null;
        }

        $success = $sth->execute( [
            'id' => $typeLogement->getId(),
            'name' => $typeLogement->getName()
        ] );

        if ( !$success ) {
            return null;
        }

        return $typeLogement;
    }

    /**
     * Supprime un type de logement
     * @param int $id
     * @return bool
     */
    public function deleteOne(int $id): bool
    {
        $query = sprintf(
            'DELETE FROM `%s` WHERE `id` = :id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        if ( !$sth ) { return false; }

        $success = $sth->execute( [
            'id' => $id
        ] );

        return $success;
    }
}
