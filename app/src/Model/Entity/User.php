<?php

namespace App\Model\Entity;

use Symplefony\Model\Entity;
class User extends Entity
{ 
    public const ROLE_ADMIN = 3;
    public const ROLE_OWNER = 2;
    public const ROLE_USER = 1;

    protected string $firstName;
    public function getFirstName(): string { return $this->firstName; }
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    protected string $lastName;
    public function getLastName(): string { return $this->lastName; }
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    protected string $password;
    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    protected string $email;
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    protected int $typeAccount;
    public function getTypeAccount(): int { return $this->typeAccount; }
    public function typeAccount(int $typeAccount): self
    {
        $this->typeAccount = $typeAccount;
        return $this;
    }
}
