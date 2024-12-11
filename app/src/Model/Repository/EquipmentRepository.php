<?php

namespace App\Model\Repository;

use App\Model\Entity\Equipment;

use Symplefony\Model\Repository;

class EquipmentRepository extends Repository
{
    protected function getTableName(): string { return 'equipments'; }

    /**
     * Crée un nouvel équipement en base de données
     * @param Equipment $equipment
     * @return Equipment|null
     */
    public function create(Equipment $equipment): ?Equipment
    {
        $query = sprintf(
            'INSERT INTO `%s` 
                (`labelEquipment`) 
                VALUES (:labelEquipment)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null; }

        $success = $sth->execute([
            'labelEquipment' => $equipment->getLabelEquipment()
        ]);

        if(!$success) { return null; }

        $equipment->setId($this->pdo->lastInsertId());

        return $equipment;
    }

    /**
     * Retourne un équipement par son id
     * @param int $id
     * @return Equipment|null
     */
    public function getById(int $id): ?Equipment
    {
        return $this->readById(Equipment::class, $id);        
    }

    /**
     * Retourne tous les équipements
     * pas de paramètre
     * @return array
     */
    public function getAll(): array
    {
        return $this->readAll(Equipment::class);
    }

    /**
     * Met à jour un équipement en base de données
     * @param Equipment $equipment
     * @return Equipment|null
     */
    public function update(Equipment $equipment): ?Equipment
    {
        $query = sprintf(
            'UPDATE `%s` 
                SET `labelEquipment` = :labelEquipment
                WHERE `id` = :id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return null; }

        $success = $sth->execute([
            'labelEquipment' => $equipment->getLabelEquipment(),
            'id' => $equipment->getId()
        ]);

        if(!$success) { return null; }

        return $equipment;
    }

    /**
     * Supprime un équipement de la base de données
     * @param int $id
     * @return bool
     */
    public function deleteOne(int $id): bool
    {
        $query = sprintf(
            'DELETE FROM `%s` WHERE `id` = :id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return false; }

        $success = $sth->execute(['id' => $id]);

        return $success;
    }
}
