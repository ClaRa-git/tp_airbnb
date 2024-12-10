<?php

namespace App\Model\Entity;

use App\Model\Repository\RepoManager;

use Symplefony\Model\Entity;

class Rental extends Entity
{
    protected string $title;
    public function getTitle(): string { return $this->title; }
    public function setTitle(int $value): self
    {
        $this->title = $value;
        return $this;
    }

    protected string $price;
    public function getPrice(): string { return $this->price; }
    public function setPrice(int $value): self
    {
        $this->price = $value;
        return $this;
    }

    protected string $surface;
    public function getSurface(): string { return $this->surface; }
    public function setSurface(int $value): self
    {
        $this->surface = $value;
        return $this;
    }

    protected string $description;
    public function getDescription(): string { return $this->description; }
    public function setDescription(int $value): self
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
    protected int $type_logement;
    public function getTypeLogementId(): int
    {
        if(!isset($this->type_logement)) {
            $this->type_logement = RepoManager::getRM()->getTypeLogementRepo()->getById($this->id);
        }

        return $this->type_logement;
    }
    public function setTypeLogement(int $id): void
    {
        $this->type_logement = $id;
    }

    // Foreign key / Liaison avec la table addresses
    protected int $address_id;
    public function getAddressId(): int
    {
        if(!isset($this->address_id)) {
            $this->address_id = RepoManager::getRM()->getAddressRepo()->getById($this->id);
        }

        return $this->address_id;
    }
    public function setAddressId(int $value): self
    {
        $this->address_id = $value;
        return $this;
    }

    // Foreign key / Liaison avec la table users
    protected int $owner_id;
    public function getOwnerId(): int
    {
        if(!isset($this->owner_id)) {
            $this->owner_id = RepoManager::getRM()->getUserRepo()->getById($this->id);
        }
        
        return $this->owner_id;
    }
    public function setOwnerId(int $value): self
    {
        $this->owner_id = $value;
        return $this;
    }
}
