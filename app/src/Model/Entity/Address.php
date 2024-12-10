<?php

namespace App\Model\Entity;

use Symplefony\Model\Entity;

class Address extends Entity
{
    protected int $number;
    public function getNumber(): int { return $this->number; }
    public function setNumber(int $value): self
    {
        $this->number = $value;
        return $this;
    }

    protected string $street;
    public function getStreet(): string { return $this->street; }
    public function setStreet(int $value): self
    {
        $this->street = $value;
        return $this;
    }

    protected string $city;
    public function getCity(): string { return $this->city; }
    public function setCity(int $value): self
    {
        $this->city = $value;
        return $this;
    }

    protected string $country;
    public function getCountry(): string { return $this->country; }
    public function setCountry(int $value): self
    {
        $this->country = $value;
        return $this;
    }

    protected string $complement;
    public function getComplement(): string { return $this->complement; }
    public function setComplement(int $value): self
    {
        $this->complement = $value;
        return $this;
    }
}
