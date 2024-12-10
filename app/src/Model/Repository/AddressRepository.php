<?php

namespace App\Model\Repository;

use App\Model\Entity\Address;
use Symplefony\Model\Repository;

class AddressRepository extends Repository
{
    /**
     * Retourne le nom de la table en base de données
     * pas de paramètre
     * @return string
     */
    protected function getTableName(): string { return 'addresses'; }

    /**
     * Retourne le nom de la table Rental
     * pas de paramètre
     * @return string
     */
    private function getRentalName(): string { return 'rentals'; }

    /**
     * Crée une nouvelle adresse en base de données
     * @param Address $address
     * @return Address|null
     */
    public function create(Address $address): ?Address
    {
        $query = sprintf(
            'INSERT INTO `%s` 
                (`number`,`street`,`city`,`country`,`complement`) 
                VALUES (:number,:street,:city,:country,:complement)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null; }

        $success = $sth->execute([
            'number' => $address->getNumber(),
            'street' => $address->getStreet(),
            'city' => $address->getCity(),
            'country' => $address->getCountry(),
            'complement' => $address->getComplement()
        ]);

        if(!$success) { return null; }

        $address->setId($this->pdo->lastInsertId());

        return $address;
    }

    /**
     * Retourne toutes les adresses
     * pas de paramètre
     * @return array
     */
    public function getAll(): array { return $this->readAll(Address::class); }

    /**
     * Retourne une adresse par son id
     * @param int $id
     * @return Address|null
     */
    public function getById(int $id): ?Address { return $this->readById(Address::class, $id); }

    /**
     * Met à jour une adresse en base de données
     * @param Address $address
     * @return Address|null
     */
    public function update(Address $address): ?Address
    {
        $query = sprintf(
            'UPDATE `%s` 
                SET 
                    `number`=:number,
                    `street`=:street,
                    `city`=:city,
                    `country`=:country,
                    `complement`=:complement
                WHERE `id`=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null; }

        $success = $sth->execute([
            'id' => $address->getId(),
            'number' => $address->getNumber(),
            'street' => $address->getStreet(),
            'city' => $address->getCity(),
            'country' => $address->getCountry(),
            'complement' => $address->getComplement()
        ]);

        if(!$success) { return null; }

        return $address;
    }

    /**
     * Récupère l'adresse d'une location
     * @param int $id
     * @return Address|null
     */
    public function getAddressForRental(int $id): ?Address
    {
        $query = sprintf(
            'SELECT a.* FROM `%1$s` as a
            JOIN `%2$s` as r ON a.id = r.address_id
            WHERE r.id = :id',
            $this->getTableName(),
            $this->getRentalName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null; }

        $success = $sth->execute(['id' => $id]);

        if(!$success) { return null; }

        $address_data = $sth->fetch();

        if(!$address_data) { return null; }

        return new Address($address_data);
    }

    /**
     * Supprime une adresse en base de données
     * Ne supprimera l'adresse que si elle n'est pas liée à une location
     * @param int $id
     * @return bool
     */
    public function deleteOne(int $id): bool
    {
        $query = sprintf(
            'DELETE FROM `%s` WHERE `id`=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return false; }

        $success = $sth->execute(['id' => $id]);

        return $success;
    }
}
