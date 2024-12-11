<?php

namespace App\Model\Entity;

use App\Model\Repository\RepoManager;

use Symplefony\Model\Entity;

class Rental extends Entity
{
    protected string $title;
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    protected float $price;
    public function getPrice(): float { return $this->price; }
    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    protected int $surface;
    public function getSurface(): int { return $this->surface; }
    public function setSurface(int $surface): self
    {
        $this->surface = $surface;
        return $this;
    }

    protected string $description;
    public function getDescription(): string { return $this->description; }
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    protected int $beddings;
    public function getBeddings(): int { return $this->beddings; }
    public function setBeddings(int $beddings): self
    {
        $this->beddings = $beddings;
        return $this;
    }

    protected int $typeLogement_id;
    public function getTypeLogementId(): int { return $this->typeLogement_id; }
    public function setTypeLogementId(int $typeLogement_id): self
    {
        $this->typeLogement_id = $typeLogement_id;
        return $this;
    }

    // Foreign key / Liaison avec la table type_logement
    protected TypeLogement $typeLogement;
    public function getTypeLogement(): TypeLogement
    {
        if(!isset($this->typeLogement)) {
            $this->typeLogement = RepoManager::getRM()->getTypeLogementRepo()->getById($this->typeLogement_id);
        }

        return $this->typeLogement;
    }
    public function setTypeLogement(TypeLogement $typeLogement): self
    {
        $this->typeLogement = $typeLogement;
        return $this;
    }

    protected int $address_id;
    public function getAddressId(): int { return $this->address_id; }
    public function setAddressId(int $address_id): self
    {
        $this->address_id = $address_id;
        return $this;
    }

    // Foreign key / Liaison avec la table addresses
    protected Address $address;
    public function getAddress(): Address
    {
        if(!isset($this->address)) {
            $this->address = RepoManager::getRM()->getAddressRepo()->getById($this->address_id);
        }

        return $this->address;
    }
    public function setAddress(Address $address): self
    {
        $this->address = $address;
        return $this;
    }

    protected int $owner_id;
    public function getOwnerId(): int { return $this->owner_id; }
    public function setOwnerId(int $owner_id): self
    {
        $this->owner_id = $owner_id;
        return $this;
    }

    // Foreign key / Liaison avec la table users
    protected User $owner;
    public function getOwner(): User
    {
        if(!isset($this->owner)) {
            $this->owner = RepoManager::getRM()->getUserRepo()->getById($this->owner_id);
        }

        return $this->owner;
    }
    public function setOwner(User $owner): self
    {
        $this->owner = $owner;
        return $this;
    }
}
