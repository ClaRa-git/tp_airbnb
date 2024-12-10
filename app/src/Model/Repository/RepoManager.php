<?php

namespace App\Model\Repository;

use Symplefony\Database;
use Symplefony\Model\RepositoryManagerTrait;

class RepoManager
{
    use RepositoryManagerTrait;

    private UserRepository $user_repository;
    public function getUserRepo(): UserRepository { return $this->user_repository; }

    private RentalRepository $rental_repository;
    public function getRentalRepo(): RentalRepository { return $this->rental_repository; }

    private TypeLogementRepository $type_logement_repository;
    public function getTypeLogementRepo(): TypeLogementRepository { return $this->type_logement_repository; }

    private AddressRepository $address_repository;
    public function getAddressRepo(): AddressRepository { return $this->address_repository; }

    private ReservationRepository $reservation_repository;
    public function getReservationRepo(): ReservationRepository { return $this->reservation_repository; }

    private function __construct()
    {
        $pdo = Database::getPDO();

        $this->user_repository = new UserRepository($pdo);
        $this->rental_repository = new RentalRepository($pdo);
        $this->type_logement_repository = new TypeLogementRepository($pdo);
        $this->address_repository = new AddressRepository($pdo);
        $this->reservation_repository = new ReservationRepository($pdo);
    }
}
