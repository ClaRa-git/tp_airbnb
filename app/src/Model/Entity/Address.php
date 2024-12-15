<?php

namespace App\Model\Entity;

use Symplefony\Model\Entity;

class Address extends Entity
{
    protected string $city;
    public function getCity(): string { return $this->city; }
    public function setCity( string $value ): self
    {
        $this->city = $value;
        return $this;
    }

    protected string $country;
    public function getCountry(): string { return $this->country; }
    public function setCountry( string $value ): self
    {
        $this->country = $value;
        return $this;
    }
}
