<?php

namespace Symplefony\Model;

use PDO;

abstract class Repository
{
    protected PDO $pdo;

    abstract protected function getTableName(): string;
    abstract public function getAll(): array;
    abstract public function getById( int $id ): mixed;

    public function __construct( PDO $pdo )
    {
        $this->pdo = $pdo;
    }
    
    /* cRud: Read tous les items */
    protected function readAll( string $class_name ): array
    {
        $query = sprintf(
            'SELECT * FROM `%s`',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return [];
        }

        $success = $sth->execute();

        // Si echec
        if( ! $success ) {
            return [];
        }

        // Récupération des résultats
        $users = [];

        while( $user_data = $sth->fetch() ) {
            $user = new $class_name( $user_data );
            $users[] = $user;
        }

        return $users;
    }

    /* cRud: Read un item par son id */
    protected function readById( string $class_name, int $id ): ?Entity
    {
        $query = sprintf(
            'SELECT * FROM `%s` WHERE id=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return null;
        }

        $success = $sth->execute([ 'id' => $id ]);

        // Si echec
        if( ! $success ) {
            return null;
        }

        // Récupération du premier résultat
        $object_data = $sth->fetch();

        // Si aucun user ne correspond
        if( ! $object_data ) {
            return null;
        }

        return new $class_name( $object_data );
    }

    /* cruD: Delete un item par son id */
    public function deleteOne( int $id ): bool
    {
        $query = sprintf(
            'DELETE FROM `%s` WHERE id=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return false;
        }

        $success = $sth->execute([ 'id' => $id ]);

        return $success;
    }
}