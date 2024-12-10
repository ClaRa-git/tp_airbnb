<?php

namespace App\Model\Entity;

use App\Model\Repository\RepoManager;

use Symplefony\Model\Entity;

class Rental extends Entity
{
    protected string $title;
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $value): self
    {
        $this->title = $value;
        return $this;
    }

    protected float $price;
    public function getPrice(): float { return $this->price; }
    public function setPrice(float $value): self
    {
        $this->price = $value;
        return $this;
    }

    protected int $surface;
    public function getSurface(): int { return $this->surface; }
    public function setSurface(int $value): self
    {
        $this->surface = $value;
        return $this;
    }

    protected string $description;
    public function getDescription(): string { return $this->description; }
    public function setDescription(string $value): self
    {
        $this->description = $value;
        return $this;
    }

    protected int $beddings;
    public function getBeddings(): int { return $this->beddings; }
    public function setBeddings(int $value): self
    {
        $this->beddings = $value;
        return $this;
    }

    protected int $type_logement_id;
    public function getTypeLogementId(): int { return $this->type_logement_id; }
    public function setTypeLogementId(int $value): self
    {
        $this->type_logement_id = $value;
        return $this;
    }

    // Foreign key / Liaison avec la table type_logement
    protected TypeLogement $type_logement;
    public function getTypeLogement(): TypeLogement
    {
        if(!isset($this->type_logement_id)) {
            $this->type_logement = RepoManager::getRM()->getTypeLogementRepo()->getById($this->type_logement_id);
        }

        return $this->type_logement;
    }
    public function setTypeLogement(TypeLogement $type_logement): self
    {
        $this->type_logement = $type_logement;
        return $this;
    }

    protected int $address_id;
    public function getAddressId(): int { return $this->address_id; }
    public function setAddressId(int $value): self
    {
        $this->address_id = $value;
        return $this;
    }

    // Foreign key / Liaison avec la table addresses
    protected Address $address;
    public function getAddress(): Address
    {
        if(!isset($this->address_id)) {
            $this->address = RepoManager::getRM()->getAddressRepo()->getById($this->address_id);
        }

        return $this->address;
    }
    public function setAddress(Address $value): self
    {
        $this->address = $value;
        return $this;
    }

    protected int $owner_id;
    public function getOwnerId(): int { return $this->owner_id; }
    public function setOwnerId(int $value): self
    {
        $this->owner_id = $value;
        return $this;
    }

    // Foreign key / Liaison avec la table users
    protected User $owner;
    public function getOwner(): User
    {
        if(!isset($this->owner_id)) {
            $this->owner = RepoManager::getRM()->getUserRepo()->getById($this->owner_id);
        }

        return $this->owner;
    }
    public function setOwner(User $value): self
    {
        $this->owner = $value;
        return $this;
    }
}
