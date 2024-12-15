<?php

namespace App\Model\Entity;

use Symplefony\Model\Entity;
class TypeLogement extends Entity
{
    protected string $labelTypeLogement;
    public function getName(): string { return $this->labelTypeLogement; }
    public function setName( int $value ): self 
    {
        $this->labelTypeLogement = $value;
        return $this;
    }
}
