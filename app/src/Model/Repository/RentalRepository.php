<?php

namespace App\Model\Repository;

use App\Model\Entity\Address;
use App\Model\Entity\Rental;
use App\Model\Entity\TypeLogement;
use App\Model\Entity\User;

use Symplefony\Model\Repository;

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
    public function create(Rental $rental): ?Rental
    {
        $query = sprintf(
            'INSERT INTO %s
            (title, price, surface, description, beddings, typeLogement_id, address_id, owner_id) 
            VALUES (:title, :price, :surface, :description, :beddings, :typeLogement_id, :address_id, :owner_id)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null; }

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

        if(!$success) { return null; }

        $rental->setId($this->pdo->lastInsertId());

        return $rental;
    }

    /**
     * Récupère le type de logement par son id
     * @param int $id
     * @return TypeLogement|null
     */
    public function getTypeLogement(int $id): ?TypeLogement { return RepoManager::getRM()->getTypeLogementRepo()->getById($id); }

    /**
     * Récupère l'adresse par son id
     * @param int $id
     * @return Address|null
     */
    public function getAddress(int $id): ?Address { return RepoManager::getRM()->getAddressRepo()->getById($id); }

    /**
     * Récupère l'user par son id
     * @param int $id
     * @return User|null
     */
    public function getOwner(int $id): ?User { return RepoManager::getRM()->getUserRepo()->getById($id); }

    /**
     * Récupère toutes les locations d'un user par son id
     * @param int $id
     * @return array
     */
    public function getAllById(int $id): array
    {
        $query = sprintf(
            'SELECT * FROM %s WHERE owner_id=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return []; }

        $success = $sth->execute([
            'id' => $id
        ]);

        if(!$success) { return []; }

        $rentals = [];

        while($rental = $sth->fetch()) { $rentals[] = new Rental($rental); }

        return $rentals;
    }

    /**
     * Récupère toutes les annonces
     * pas de paramètre
     * @return array
     */
    public function getAll(): array { return $this->readAll(Rental::class); }

    /**
     * Récupère une annonce par son id
     * @param int $id
     * @return Rental|null
     */
    public function getById(int $id): ?Rental { return $this->readById(Rental::class, $id); }

    /**
     * Supprime une annonce par son id
     * @param int $id
     * @return bool
     */
    public function deleteOne(int $id): bool 
    {
        $query = sprintf(
            'DELETE FROM %s WHERE id=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return false; }

        return $sth->execute([
            'id' => $id
        ]);
    }
}
