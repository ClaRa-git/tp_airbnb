<?php

namespace App\Model\Entity;

use App\Model\Repository\RepoManager;

use Symplefony\Model\Entity;

class Reservation extends Entity
{
    protected string $begin_date;
    public function getDateStart(): string { return $this->begin_date; }
    public function setDateStart(string $value): self
    {
        $this->begin_date = $value;
        return $this;
    }

    protected string $end_date;
    public function getDateEnd(): string { return $this->end_date; }
    public function setDateEnd(string $value): self
    {
        $this->end_date = $value;
        return $this;
    }

    // Foreign key / liaison avec la table users
    protected int $user_id;
    public function getUserId(): int
    {
        return $this->user_id;
    }
    public function setUserId(int $value): self
    {
        $this->user_id = $value;
        return $this;
    }

    // Foreign key / liaison avec la table rentals
    protected int $rental_id;
    public function getRentalId(): int
    {
        return $this->rental_id;
    }
    public function setRentalId(int $value): self
    {
        $this->rental_id = $value;
        return $this;
    }
}
