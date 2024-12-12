<?php

namespace App\Model\Entity; 

use Symplefony\Model\Entity;

class Equipment extends Entity
{
    protected string $labelEquipment;
    public function getName(): string { return $this->labelEquipment; }
    public function setName(string $labelEquipment): self
    {
        $this->labelEquipment = $labelEquipment;
        return $this;
    }
}