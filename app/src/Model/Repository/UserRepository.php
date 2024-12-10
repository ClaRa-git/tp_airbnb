<?php

namespace App\Model\Repository;

use Symplefony\Model\Repository;

use App\Model\Entity\User;

class UserRepository extends Repository
{
    /**
     * Retourne le nom de la table en base de données
     * @return string
     */
    protected function getTableName(): string { return 'users'; }

    /**
     * Crée un nouvel utilisateur en base de données
     * @param User $user
     * @return User|null
     */
    public function create(User $user): ?User
    {
        $query = sprintf(
            'INSERT INTO `%s` 
                (`firstname`,`lastname`,`password`,`email`) 
                VALUES (:firstname,:lastname,:password,:email)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null; }

        $success = $sth->execute([
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'password' => $user->getPassword(),
            'email' => $user->getEmail()
        ]);

        if(!$success) { return null; }

        $user->setId($this->pdo->lastInsertId());

        return $user;
    }

    /**
     * Récupère tous les utilisateurs
     * @return array
     */
    public function getAll(): array
    {
        return $this->readAll(User::class);
    }

    /**
     * Récupère un utilisateur par son id
     * @param int $id
     * @return User|null
     */
    public function getById(int $id): ?User
    {
        return $this->readById(User::class, $id);
    }

    /**
     * Met à jour un utilisateur en base de données
     * @param User $user
     * @return User|null
     */
    public function update(User $user): ?User
    {
        $query = sprintf(
            'UPDATE `%s` 
                SET `firstname`=:firstname,`lastname`=:lastname,`email`=:email,`password`=:password
                WHERE `id`=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null; }

        $success = $sth->execute([
            'id' => $user->getId(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword()
        ]);

        if(!$success) { return null; }

        return $user;
    }
}
