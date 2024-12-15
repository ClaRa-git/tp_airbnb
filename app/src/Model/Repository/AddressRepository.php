<?php

namespace App\Model\Repository;

use Symplefony\Model\Repository;

use App\Model\Entity\Address;

class AddressRepository extends Repository
{
    /**
     * Retourne le nom de la table en base de données
     * pas de paramètre
     * @return string
     */
    protected function getTableName(): string { return 'addresses'; }

    /**
     * Crée une nouvelle adresse
     * @param Address $address
     * @return Address|null
     */
    public function create( Address $address ): ?Address
    {
        $query = sprintf(
            'INSERT INTO `%s` 
                (`city`,`country`) 
                VALUES (:city,:country)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        if ( !$sth ) { return null; }

        $success = $sth->execute( [
            'city' => $address->getCity(),
            'country' => $address->getCountry()
        ] );

        if ( !$success ) { return null; }

        $address->setId( $this->pdo->lastInsertId() );

        return $address;
    }

    /**
     * Retourne toutes les adresses
     * pas de paramètre
     * @return array
     */
    public function getAll(): array { return $this->readAll( Address::class ); }

    /**
     * Retourne une adresse par son id
     * @param int $id
     * @return Address|null
     */
    public function getById( int $id ): ?Address { return $this->readById( Address::class, $id ); }

    /**
     * Met à jour une adresse en base de données
     * @param Address $address
     * @return Address|null
     */
    public function update( Address $address ): ?Address
    {
        $query = sprintf(
            'UPDATE `%s` 
                SET 
                    `city`=:city,
                    `country`=:country
                WHERE `id`=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null; }

        $success = $sth->execute( [
            'id' => $address->getId(),
            'city' => $address->getCity(),
            'country' => $address->getCountry()
        ] );

        if ( !$success ) { return null; }

        return $address;
    }

    /**
     * Supprime une adresse en base de données
     * Ne supprimera l'adresse que si elle n'est pas liée à une location
     * @param int $id
     * @return bool
     */
    public function deleteOne( int $id ): bool
    {
        $query = sprintf(
            'DELETE FROM `%s` WHERE `id`=:id',
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
