<?php
declare(strict_types=1);

namespace App\Dtos\CardDto\Queries;

use App\Dtos\CategoryCardDto\Queries\CategoryViewDto;

class CardViewDto
{
    public readonly int $id;
    public readonly string $name;
    public readonly string $description;
    public readonly int $position;
    public readonly int $userId;
    public readonly ?CategoryViewDto $category;

    private function __construct(
        int $id,
        string $name,
        string $description,
        int $position,
        int $userId,
        ?CategoryViewDto $category
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->position = $position;
        $this->userId = $userId;
        $this->category = $category;
    }


    public static function fromRow(array $row): self
    {
        $category = $row['category_id'] !== null
            ? CategoryViewDto::fromRow($row)
            : null;

        return new self(
            (int) $row['id'],
            $row['name'],
            $row['description'],
            (int) $row['position'],
            (int) $row['userId'],
            $category
        );
    }
}
