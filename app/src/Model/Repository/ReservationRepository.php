<?php

namespace App\Model\Repository;

use App\Model\Entity\Reservation;
use Symplefony\Model\Repository;

class ReservationRepository extends Repository
{
    /**
     * Retourne le nom de la table en base de données
     * pas de paramètre
     * @return string
     */
    protected function getTableName(): string { return 'reservations'; }

    /**
     * Retourne  le nom de la table en base de données
     * pas de paramètre
     * @return string
     */
    private function getMappingRental(): string { return 'rentals'; }

    /**
     * Créer une réservation
     * @param Reservation $reservation
     * @return Reservation|null
     */
    public function create(Reservation $reservation): ?Reservation
    {
        $query = sprintf(
            'INSERT INTO %s
            (dateStart, dateEnd, user_id, rental_id) 
            VALUES (:dateStart, :dateEnd, :user_id, :rental_id)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null; }

        $success = $sth->execute([
            'dateStart' => $reservation->getDateStart(),
            'dateEnd' => $reservation->getDateEnd(),
            'user_id' => $reservation->getUserId(),
            'rental_id' => $reservation->getRentalId()
        ]);

        if(!$success) { return null; }

        $reservation->setId($this->pdo->lastInsertId());

        return $reservation;
    }

    /**
     * Récupère toutes les réservations pour un utilisateur par son id
     * @param int $id
     * @return array
     */
    public function getAllForUser(int $id): array
    {
        $query = sprintf(
            'SELECT  * FROM %s WHERE user_id=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return []; }

        $success = $sth->execute([
            'id' => $id
        ]);

        if(!$success) { return []; }

        $reservations = [];
        while ($reservation = $sth->fetch()) {
            $reservations[] = new Reservation($reservation);
        }

        return $reservations;
    }

    /**
     * Récupère une réservation par son id
     * @param int $id
     * @return Reservation|null
     */
    public function getById(int $id): ?Reservation { return $this->readById(Reservation::class, $id); }

    /**
     * Récupère toutes les réservations
     * pas de paramètre
     * @return array
     */
    public function getAll(): array { return $this->readAll(Reservation::class); }

    /**
     * Supprime une réservation par son id
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

        $success = $sth->execute([
            'id' => $id
        ]);

        return $success;
    }

    /**
     * Récupère toutes les réservations concernant un propriétaire
     * @param int $owner_id
     * @return array
     */
    public function getAllForOwner(int $owner_id): array
    {
        $query = sprintf(
            'SELECT res.* FROM %1$s res
            JOIN %2$s ren ON res.rental_id = ren.id
            WHERE ren.owner_id=:owner_id',
            $this->getTableName(),
            $this->getMappingRental()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return []; }

        $success = $sth->execute([
            'owner_id' => $owner_id
        ]);

        if(!$success) { return []; }

        $reservations = [];
        while ($reservation = $sth->fetch()) {
            $reservations[] = new Reservation($reservation);
        }

        return $reservations;
    }
}
