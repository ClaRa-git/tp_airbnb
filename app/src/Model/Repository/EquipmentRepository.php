<?php

namespace App\Model\Repository;

use App\Model\Entity\Equipment;

use Symplefony\Model\Repository;

class EquipmentRepository extends Repository
{
    protected function getTableName(): string { return 'equipments'; }
    private function getMappingRental(): string { return 'rentals_equipments'; }

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
            'labelEquipment' => $equipment->getName()
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
     * Retourne tous les équipements d'une location
     * @param int $id
     * @return array
     */
    public function getAllForRental(int $id): array
    {
        $query = sprintf(
            'SELECT * FROM `%1$s` as `e`
                JOIN `%2$s` as `re` ON `e`.`id` = `re`.`equipment_id` 
                WHERE `re`.`rental_id` = :id',
            $this->getTableName(),
            $this->getMappingRental()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return []; }

        $success = $sth->execute(['id' => $id]);

        if(!$success) { return []; }

        $equipments = [];

        while($equipment_data = $sth->fetch()){
            $equipments[] = new Equipment($equipment_data);
        }

        return $equipments;
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
            'labelEquipment' => $equipment->getName(),
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

    /**
     * Supprime toutes les liaisons entre un équipement et une location
     * @param int $id
     * @return bool
     */
    public function detachAllForRental(int $id): bool
    {
        $query = sprintf(
            'DELETE FROM `%s` WHERE `rental_id` = :id',
            $this->getMappingRental()
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return false; }

        $success = $sth->execute(['id' => $id]);

        return $success;
    }

    /**
     * Insère les liaisons entre un équipement et une location
     * @param array $equipments_ids
     * @param int $rental_id
     * @return bool
     */
    public function attachForRental(array $equipments_ids, int $rental_id): bool
    {
        $query_values = [];
        foreach($equipments_ids as $equipment_id) {
            $query_values[] = sprintf('(%s, %s)', $rental_id, $equipment_id);
        }

        $query = sprintf(
            'INSERT INTO `%s` 
                (`rental_id`, `equipment_id`) 
                VALUES %s',
            $this->getMappingRental(),
            implode(', ', $query_values)
        );

        $sth = $this->pdo->prepare($query);

        if(!$sth) { return false; }

        $success = $sth->execute();

        return $success;
    }
}
