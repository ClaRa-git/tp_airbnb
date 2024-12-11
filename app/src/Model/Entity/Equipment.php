<?php

namespace App\Model\Entity; 

use Symplefony\Model\Entity;

class Equipment extends Entity
{
    protected string $labelEquipment;
    public function getLabelEquipment(): string { return $this->labelEquipment; }
    public function setLabelEquipment(string $labelEquipment): self
    {
        $this->labelEquipment = $labelEquipment;
        return $this;
    }
}