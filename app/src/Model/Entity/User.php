<?php

namespace App\Model\Entity;

use Symplefony\Model\Entity;
class User extends Entity
{ 
    protected string $first_name;
    public function getfirst_name(): string { return $this->first_name; }
    public function setfirst_name(int $value): self
    {
        $this->first_name = $value;
        return $this;
    }

    protected string $last_name;
    public function getlast_name(): string { return $this->last_name; }
    public function setlast_name(int $value): self
    {
        $this->last_name = $value;
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
