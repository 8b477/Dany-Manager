<?php
declare(strict_types=1);

namespace App\Entities;

class Card
{
    private ?int $id;
    private string $name;
    private string $description;
    private int $position;
    private int $categoryId;
    private int $userId;

    public function __construct(
        ?int $id,
        string $name,
        string $description,
        int $position,
        int $categoryId,
        int $userId,
        )
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->position = $position;
        $this->categoryId = $categoryId;
        $this->userId = $userId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

}