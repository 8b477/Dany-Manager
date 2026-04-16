<?php
declare(strict_types=1);

namespace App\Dtos\CategoryCardDto\Queries;

class CategoryViewDto
{
    public readonly int $id;
    public readonly string $name;
    public readonly int $position;

    private function __construct(int $id, string $name, int $position)
    {
        $this->id = $id;
        $this->name = $name;
        $this->position = $position;
    }

    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['category_id'],
            $row['category_name'],
            (int) $row['category_position']
        );
    }
}
