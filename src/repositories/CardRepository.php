<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Card;
use App\Configs\Database;

class CardRepository
{
    public function getByIdUser(int $idUser): array
    {
        $sql = "SELECT 
                    c.id, c.name, c.description, c.position, c.categoryId, c.userId,
                    cat.id        AS category_id,
                    cat.name      AS category_name,
                    cat.position  AS category_position
                FROM card c
                LEFT JOIN category cat ON c.categoryId = cat.id
                WHERE c.userId = :userId
                ORDER BY c.position ASC";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([':userId' => $idUser]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM card WHERE id = :id";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getNextPosition(int $categoryId): int
    {
        // COALESCE dans le cas ou pas de card et que j'ai un null en retour
        $sql = "SELECT COALESCE(MAX(position), 0) + 1 FROM card WHERE categoryId = :categoryId";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([':categoryId' => $categoryId]);
        return (int) $stmt->fetchColumn();
    }

    public function add(Card $card): bool
    {
        $sql = "INSERT INTO card (name, description, position, categoryId, userId) VALUES (:name, :description, :position, :categoryId, :userId)";
        $stmt = Database::getConnection()->prepare($sql);

        return $stmt->execute([
            ':name' => $card->getName(),
            ':description' => $card->getDescription(),
            ':position' => $card->getPosition(),
            ':categoryId' => $card->getCategoryId(),
            ':userId' => $card->getUserId()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM card WHERE id = :id";
        $stmt = Database::getConnection()->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function update(Card $card): bool
    {
        $sql = "UPDATE card SET name = :name, description = :description, position = :position, categoryId = :categoryId WHERE id = :id";
        $stmt = Database::getConnection()->prepare($sql);
        return $stmt->execute([
            ':name' => $card->getName(),
            ':description' => $card->getDescription(),
            ':position' => $card->getPosition(),
            ':categoryId' => $card->getCategoryId(),
            ':id' => $card->getId()
        ]);
    }

    public function move(int $id, int $categoryId, int $position): bool
    {
        $sql = "UPDATE card SET categoryId = :categoryId, position = :position WHERE id = :id";
        $stmt = Database::getConnection()->prepare($sql);
        return $stmt->execute([
            ':categoryId' => $categoryId,
            ':position'   => $position,
            ':id'         => $id,
        ]);
    }

}