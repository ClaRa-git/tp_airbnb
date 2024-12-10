<?php

namespace App\Model\Entity; 

use Symplefony\Model\Entity;

class Equipment extends Entity
{
    protected string $label_equipment;
    public function getLabelEquipment(): string { return $this->label_equipment; }
    public function setLabelEquipment(string $label_equipment): self
    {
        $this->label_equipment = $label_equipment;
        return $this;
    }
}