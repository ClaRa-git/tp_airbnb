<?php

namespace App\Model\Entity;

use Symplefony\Model\Entity;

class Address extends Entity
{
    protected string $city;
    public function getCity(): string { return $this->city; }
    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    protected string $country;
    public function getCountry(): string { return $this->country; }
    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }
}
