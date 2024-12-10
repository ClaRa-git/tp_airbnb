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
     * Retourne le nom de la table rental
     * pas de paramètre
     * @return string
     */
    private function getRentalName(): string { return 'rental'; }

    /**
     * Crée un nouvel utilisateur en base de données
     * @param User $user
     * @return User|null
     */
    public function create(User $user): ?User
    {
        $query = sprintf(
            'INSERT INTO `%s` 
                (`first_name`,`last_name`,`password`,`email`) 
                VALUES (:first_name,:last_name,:password,:email)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null; }

        $success = $sth->execute([
            'first_name' => $user->getfirst_name(),
            'last_name' => $user->getlast_name(),
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
     * @return User|null
     */
    public function getByEmail(string $email): ?User
    {
        $query = sprintf(
            'SELECT * FROM `%s` WHERE `email`=:email',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null; }

        $success = $sth->execute(['email' => $email]);

        if(!$success) { return null; }

        if($sth->rowCount() === 0) { return null; }

        $user = new User($sth->fetch());
        
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
                SET `first_name`=:first_name,`last_name`=:last_name,`email`=:email,`password`=:password
                WHERE `id`=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null; }

        $success = $sth->execute([
            'id' => $user->getId(),
            'first_name' => $user->getfirst_name(),
            'last_name' => $user->getlast_name(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword()
        ]);

        if(!$success) { return null; }

        return $user;
    }

    /**
     * Récupère un user pour une location
     * @param int $id
     * @return User|null
     */
    public function getUserForRental(int $id): ?User
    {
        $query = sprintf(
            'SELECT u.* FROM `%1$s` as u
            JOIN `%2$s` as r ON u.id = r.user_id
            WHERE r.id = :id',
            $this->getTableName(),
            $this->getRentalName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null; }

        $success = $sth->execute(['id' => $id]);

        if(!$success) { return null; }

        $user_data = $sth->fetch();

        if(!$user_data) { return null; }

        return new User($user_data);
    }
}
