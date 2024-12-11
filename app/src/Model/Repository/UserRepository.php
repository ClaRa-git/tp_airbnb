<?php

namespace App\Model\Repository;

use Symplefony\Model\Repository;

use App\Model\Entity\User;

class UserRepository extends Repository
{
    /**
     * Retourne le nom de la table en base de données
     * pas de paramètre
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
                (`firstName`,`lastName`,`password`,`email`, `typeAccount`)	 
                VALUES (:firstName,:lastName,:password,:email,:typeAccount)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null; }

        $success = $sth->execute([
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
            'typeAccount' => $user->getTypeAccount()
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
     * Récupère un user par son id
     * @param int $id
     * @return User|null
     */
    public function getById(int $id): ?User
    {
        return $this->readById(User::class, $id);
    }

    /**
     * Récupère un user par son email
     * @param string $email
     * @return array
     */
    public function getAllByEmail(string $email): array
    {
        $query = sprintf(
            'SELECT * FROM `%s` WHERE `email`=:email',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return []; }

        $success = $sth->execute(['email' => $email]);

        if(!$success) { return []; }

        $users = [];
        foreach($sth as $row) {
            $users[] = new User($row);
        }
        
        return $users;
    }

    /**
     * Met à jour un user en base de données
     * @param User $user
     * @return User|null
     */
    public function update(User $user): ?User
    {
        $query = sprintf(
            'UPDATE `%s` 
                SET `firstName`=:firstName,`lastName`=:lastName,`email`=:email,`password`=:password,`typeAccount`=:typeAccount
                WHERE `id`=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null; }

        $success = $sth->execute([
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'typeAccount' => $user->getTypeAccount()
        ]);

        if(!$success) { return null; }

        return $user;
    }
}
