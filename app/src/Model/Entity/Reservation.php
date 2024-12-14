<?php

namespace App\Model\Entity;

use App\Model\Repository\RepoManager;

use Symplefony\Model\Entity;

class Reservation extends Entity
{
    protected string $dateStart;
    public function getDateStart(): string { return $this->dateStart; }
    public function setDateStart(string $value): self
    {
        $this->dateStart = $value;
        return $this;
    }

    protected string $dateEnd;
    public function getDateEnd(): string { return $this->dateEnd; }
    public function setDateEnd(string $value): self
    {
        $this->dateEnd = $value;
        return $this;
    }

    protected int $user_id;
    public function getUserId(): int { return $this->user_id; }
    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }

    // Foreign key / liaison avec la table users
    protected User $user;
    public function getUser(): User
    {
        if (!isset($this->user)) { $this->user = RepoManager::getRM()->getUserRepo()->getById($this->user_id); }
        return $this->user;
    }
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    protected int $rental_id;
    public function getRentalId(): int { return $this->rental_id; }
    public function setRentalId(int $rental_id): self
    {
        $this->rental_id = $rental_id;
        return $this;
    }

    // Foreign key / liaison avec la table rentals
    protected Rental $rental;
    public function getRental(): Rental
    {
        if (!isset($this->rental)) { $this->rental = RepoManager::getRM()->getRentalRepo()->getById($this->rental_id); }
        return $this->rental;
    }
    public function setRental(Rental $rental): self
    {
        $this->rental = $rental;
        return $this;
    }
}
