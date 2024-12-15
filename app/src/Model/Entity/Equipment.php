<?php

namespace App\Model\Entity; 

use Symplefony\Model\Entity;

class Equipment extends Entity
{
    protected string $labelEquipment;
    public function getName(): string { return $this->labelEquipment; }
    public function setName( string $value ): self
    {
        $this->labelEquipment = $value;
        return $this;
    }
}