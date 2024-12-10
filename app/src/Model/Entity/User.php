<?php

namespace App\Model\Entity;

use Symplefony\Model\Entity;
class User extends Entity
{
    protected string $firstname;
    public function getFirstname(): string { return $this->firstname; }
    public function setFirstname(int $value): self
    {
        $this->firstname = $value;
        return $this;
    }

    protected string $lastname;
    public function getLastname(): string { return $this->lastname; }
    public function setLastname(int $value): self
    {
        $this->lastname = $value;
        return $this;
    }

    protected string $password;
    public function getPassword(): string { return $this->password; }
    public function setPassword(int $value): self
    {
        $this->id = $value;
        return $this;
    }

    protected string $email;
    public function getEmail(): string { return $this->email; }
    public function setEmail(int $value): self
    {
        $this->email = $value;
        return $this;
    }
}
