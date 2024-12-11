<?php

namespace App\Model\Entity;

use Symplefony\Model\Entity;
class TypeLogement extends Entity
{
    protected string $labelTypeLogement;
    public function getName(): string { return $this->labelTypeLogement; }
    public function setName(int $labelTypeLogement): self 
    {
        $this->labelTypeLogement = $labelTypeLogement;
        return $this;
    }
}
