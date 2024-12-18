<?php

namespace App\Model\Entity;

use App\Model\Repository\RepoManager;
use App\Tools\Functions;
use DateTime;
use Symplefony\ConversionTools;
use Symplefony\Model\Entity;

class Reservation extends Entity
{
    protected DateTime $dateStart;
    public function getDateStart(): DateTime
    {
        return $this->dateStart;
    }
    /**
     * Récupérer la date en tant que string
     * pas de paramètre
     * @return string
     */
    public function getStringDateStart(): string
    {
        $date = ConversionTools::dateToSQLFormat($this->dateStart);
        return $date;
    }

    public function setDateStart(DateTime $value): self
    {
        $this->dateStart = $value;
        return $this;
    }

    protected DateTime $dateEnd;
    public function getDateEnd(): DateTime
    {
        return $this->dateEnd;
    }
    /**
     * Récupérer la date en tant que string
     * pas de paramètre
     * @return string
     */
    public function getStringDateEnd(): string
    {
        $date = ConversionTools::dateToSQLFormat($this->dateEnd);
        return $date;
    }
    public function setDateEnd(DateTime $value): self
    {
        $this->dateEnd = $value;
        return $this;
    }

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

    // Foreign key / liaison avec la table users
    protected User $user;
    public function getUser(): User
    {
        if (!isset($this->user)) {
            $this->user = RepoManager::getRM()->getUserRepo()->getById($this->user_id);
        }
        return $this->user;
    }
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

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

    // Foreign key / liaison avec la table rentals
    protected Rental $rental;
    public function getRental(): Rental
    {
        if (!isset($this->rental)) {
            $this->rental = RepoManager::getRM()->getRentalRepo()->getById($this->rental_id);
        }
        return $this->rental;
    }
    public function setRental(Rental $rental): self
    {
        $this->rental = $rental;
        return $this;
    }
}
