<?php

namespace App\Model\Entity;

use Symplefony\Model\Entity;

use App\Model\Repository\RepoManager;

class Rental extends Entity
{
    protected string $title;
    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(string $value): self
    {
        $this->title = $value;
        return $this;
    }

    protected float $price;
    public function getPrice(): float
    {
        return $this->price;
    }
    public function setPrice(float $value): self
    {
        $this->price = $value;
        return $this;
    }

    protected int $surface;
    public function getSurface(): int
    {
        return $this->surface;
    }
    public function setSurface(int $value): self
    {
        $this->surface = $value;
        return $this;
    }

    protected string $description;
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $value): self
    {
        $this->description = $value;
        return $this;
    }

    protected int $beddings;
    public function getBeddings(): int
    {
        return $this->beddings;
    }
    public function setBeddings(int $value): self
    {
        $this->beddings = $value;
        return $this;
    }

    protected int $typeLogement_id;
    public function getTypeLogementId(): int
    {
        return $this->typeLogement_id;
    }
    public function setTypeLogementId(int $typeLogement_id): self
    {
        $this->typeLogement_id = $typeLogement_id;
        return $this;
    }

    // Foreign key / Liaison avec la table type_logement
    protected TypeLogement $typeLogement;
    public function getTypeLogement(): TypeLogement
    {
        if (!isset($this->typeLogement)) {
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
    public function getAddressId(): int
    {
        return $this->address_id;
    }
    public function setAddressId(int $value): self
    {
        $this->address_id = $value;
        return $this;
    }

    // Foreign key / Liaison avec la table addresses
    protected Address $address;
    public function getAddress(): Address
    {
        if (!isset($this->address)) {
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
    public function getOwnerId(): int
    {
        return $this->owner_id;
    }
    public function setOwnerId(int $value): self
    {
        $this->owner_id = $value;
        return $this;
    }

    // Foreign key / Liaison avec la table users
    protected User $owner;
    public function getOwner(): User
    {
        if (!isset($this->owner)) {
            $this->owner = RepoManager::getRM()->getUserRepo()->getById($this->owner_id);
        }

        return $this->owner;
    }
    public function setOwner(User $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    // Liaison avec la table rentals_equipments
    protected array $equipments;
    public function getEquipments(): array
    {
        if (!isset($this->equipments)) {
            $this->equipments = RepoManager::getRM()->getEquipmentRepo()->getAllForRental($this->id);
        }

        return $this->equipments;
    }
    public function addEquipments(array $equipments_ids): self
    {
        $equip_repo = RepoManager::getRM()->getEquipmentRepo();

        // 1- On supprime les équipements déjà liés à la location
        $equip_repo->detachAllForRental($this->id);

        if (empty($equipments_ids)) {
            return $this;
        }

        // 2- On lie seulement les équipements passés en paramètre
        $equip_repo->attachForRental($equipments_ids, $this->id);

        return $this;
    }

    protected string $image;
    public function getImage(): string
    {
        return $this->image;
    }
    public function setImage(string $value): self
    {
        $this->image = $value;
        return $this;
    }
}
