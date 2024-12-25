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
    protected function getTableName(): string
    {
        return 'users';
    }

    /**
     * Crée un nouvel utilisateur en base de données
     * @param User $user
     * @return User|null
     */
    public function create(User $user): ?User
    {
        $query = sprintf(
            'INSERT INTO `%s` 
                (`firstName`,`lastName`,`password`,`email`)	 
                VALUES (:firstName,:lastName,:password,:email)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if (!$sth) {
            return null;
        }

        $success = $sth->execute([
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'password' => $user->getPassword(),
            'email' => $user->getEmail()
        ]);

        if (!$success) {
            return null;
        }

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
     * Récupère un user par son email et son type de compte
     * @param string $email
     * @param int $typeAccount
     * @return User|null
     */
    public function getByEmail(string $email): ?User
    {
        $query = sprintf(
            'SELECT * FROM `%s` WHERE `email`=:email',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if (!$sth) {
            return null;
        }

        $success = $sth->execute([
            'email' => $email
        ]);

        if (!$success) {
            return null;
        }

        $user_data = $sth->fetch();

        if (!$user_data) {
            return null;
        }

        $user = new User($user_data);

        return $user;
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
                SET `firstName`=:firstName,`lastName`=:lastName,`email`=:email,`password`=:password
                WHERE `id`=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if (!$sth) {
            return null;
        }

        $success = $sth->execute([
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword()
        ]);

        if (!$success) {
            return null;
        }

        return $user;
    }

    /**
     * Supprime un user en base de données
     * @param int $id
     * @return bool
     */
    public function deleteOne(int $id): bool
    {
        $query = sprintf(
            'DELETE FROM `%s` WHERE `id`=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if (!$sth) {
            return false;
        }

        $success = $sth->execute([
            'id' => $id
        ]);

        return $success;
    }

    /**
     * Récupère un user par son email et son type de compte
     * @param string $email
     * @param int $typeAccount
     * @return User|null
     */
    public function checkAuth(string $email, string $password): ?User
    {
        $query = sprintf(
            'SELECT * FROM `%s` WHERE `email`=:email AND `password`=:password',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if (!$sth) {
            return null;
        }

        $success = $sth->execute([
            'email' => $email,
            'password' => $password
        ]);

        if (!$success) {
            return null;
        }

        $user_data = $sth->fetch();

        if (!$user_data) {
            return null;
        }

        $user = new User($user_data);

        return $user;
    }
}
