<?php

namespace App\Model\Repository;

use Symplefony\Model\Repository;

use App\Model\Entity\Rental;

class RentalRepository extends Repository
{
    /**
     * Retourne le nom de la table en base de données
     * pas de paramètre
     * @return string
     */
    protected function getTableName(): string { return 'rentals'; }

    /**
     * Créer une annonce
     * @param Rental $rental
     * @return Rental|null
     */
    public function create( Rental $rental ): ?Rental
    {
        $query = sprintf(
            'INSERT INTO `%s`
            (`title`, `price`, `surface`, `description`, `beddings`, `typeLogement_id`, `address_id`, `owner_id`) 
            VALUES (:title, :price, :surface, :description, :beddings, :typeLogement_id, :address_id, :owner_id)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        if ( !$sth ) { return null; }

        $success = $sth->execute([
            'title' => $rental->getTitle(),
            'price' => $rental->getPrice(),
            'surface' => $rental->getSurface(),
            'description' => $rental->getDescription(),
            'beddings' => $rental->getBeddings(),
            'typeLogement_id' => $rental->getTypeLogementId(),
            'address_id' => $rental->getAddressId(),
            'owner_id' => $rental->getOwnerId()
        ]);

        if ( !$success ) { return null; }

        $rental->setId( $this->pdo->lastInsertId() );

        return $rental;
    }

    /**
     * Récupère toutes les annonces
     * pas de paramètre
     * @return array
     */
    public function getAll(): array
    {
        return $this->readAll( Rental::class );
    }

    /**
     * Récupère une annonce par son id
     * @param int $id
     * @return Rental|null
     */
    public function getById( int $id ): ?Rental
    {
        return $this->readById( Rental::class, $id );
    }

    /**
     * Récupère toutes les locations d'un propriétaire par son id
     * @param int $id
     * @return array
     */
    public function getAllById( int $id ): array
    {
        $query = sprintf(
            'SELECT * FROM %s WHERE owner_id=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        if ( !$sth ) { return []; }

        $success = $sth->execute( [
            'id' => $id
        ] );

        if ( !$success ) { return []; }

        $rentals = [];

        while( $rental = $sth->fetch() )
        {
            $rentals[] = new Rental( $rental );
        }

        return $rentals;
    }

    /**
     * Mise à jour d'une annonce
     * @param Rental $rental
     * @return Rental|null
     */
    public function update( Rental $rental ): ?Rental
    {
        $query = sprintf(
            'UPDATE `%s` SET
            title=:title, price=:price, surface=:surface, description=:description, beddings=:beddings, typeLogement_id=:typeLogement_id, address_id=:address_id, owner_id=:owner_id
            WHERE id=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        if ( !$sth ) { return null; }

        $success = $sth->execute([
            'id' => $rental->getId(),
            'title' => $rental->getTitle(),
            'price' => $rental->getPrice(),
            'surface' => $rental->getSurface(),
            'description' => $rental->getDescription(),
            'beddings' => $rental->getBeddings(),
            'typeLogement_id' => $rental->getTypeLogementId(),
            'address_id' => $rental->getAddressId(),
            'owner_id' => $rental->getOwnerId()
        ]);

        if ( !$success ) { return null; }

        return $rental;
    }

    /**
     * Supprime une annonce par son id
     * @param int $id
     * @return bool
     */
    public function deleteOne( int $id ): bool 
    {
        // On supprime d'abord toutes les liaisons avec les locations
        $success = RepoManager::getRM()->getEquipmentRepo()->detachAllForRental( $id );
        
        // Si cela a fonctionné, on invoque la méthode deleteOne parente
        if ( $success ) { return parent::deleteOne( $id ); }

        return $success;
    }
}
