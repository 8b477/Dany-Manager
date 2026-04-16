<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Configs\Database;
use App\Entities\Category;

class CategoryCardRepository
{

    public function add(Category $category): bool
    {
        $sql = "INSERT INTO category (name, position) VALUES (:name, :position)";
        $stmt = Database::getConnection()->prepare($sql);
        return $stmt->execute([
            ':name' => $category->getName(),
            ':position' => $category->getPosition()
        ]);
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM category";
        $stmt = Database::getConnection()->query($sql);
        return $stmt->fetchAll();
    }
}