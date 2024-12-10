<?php

namespace App\Model\Entity;

use Symplefony\Model\Entity;
class TypeLogement extends Entity
{
    protected string $label_type_logement;
    public function getName(): string { return $this->label_type_logement; }
    public function setName(int $value): self
    {
        $this->label_type_logement = $value;
        return $this;
    }
}
