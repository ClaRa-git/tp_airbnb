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

    // Foreign key / Liaison avec la table type_logement
    protected TypeLogement $type_logement;
    public function getTypeLogementId(): TypeLogement
    {
        if(!isset($this->type_logement_id)) {
            $this->type_logement = RepoManager::getRM()->getTypeLogementRepo()->getTypeForRental($this->id);
        }

        return $this->type_logement;
    }
    public function setTypeLogement(TypeLogement $type_logement): self
    {
        $this->type_logement = $type_logement;
        return $this;
    }

    // Foreign key / Liaison avec la table addresses
    protected Address $address;
    public function getAddress(): Address
    {
        if(!isset($this->address_id)) {
            $this->address = RepoManager::getRM()->getAddressRepo()->getAddressForRental($this->id);
        }

        return $this->address;
    }
    public function setAddressId(Address $value): self
    {
        $this->address = $value;
        return $this;
    }

    // Foreign key / Liaison avec la table users
    protected int $owner;
    public function getOwner(): int
    {
        if(!isset($this->owner_id)) {
            $this->owner = RepoManager::getRM()->getUserRepo()->getUserForRental($this->id);
        }

        return $this->owner;
    }
    public function setOwner(User $value): self
    {
        $this->owner = $value;
        return $this;
    }
}
