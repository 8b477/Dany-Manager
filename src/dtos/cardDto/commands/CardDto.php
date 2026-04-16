<?php
declare(strict_types=1);

namespace App\Dtos\CardDto\Commands;

use App\Dtos\CategoryCardDto\Command\CategoryDto;


class CardDto
{
    public readonly ?int $id;
    public readonly string $name;
    public readonly string $description;
    public int $position;
    public readonly ?int $categoryId;
    public ?CategoryDto $category; // nullable pour éviter de créer un autre DTO pour la création

    public array $errors = [];

    private function __construct(
        ?int $id,
        string $name,
        string $description,
        int $position,
        ?int $categoryId
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->position = $position;
        $this->categoryId = $categoryId;
        $this->category = null;
    }

    /**
     * Factory depuis $_POST — hydrate et valide en une seule passe.
     * Le userId n'est jamais accepté depuis le POST : il vient exclusivement de la session.
     */
    public static function fromRequest(array $post): self{

        $id = isset($post['card_id']) && $post['card_id'] !== '' ? (int)$post['card_id'] : null;
        $name    = trim($post['name'] ?? '');
        $description = trim($post['description'] ?? '');
        $position = (int)($post['position'] ?? 0);
        $categoryId = (int)($post['category_id'] ?? 0);

        $dto = new self($id, $name, $description, $position, $categoryId);

        $errors = [];
        // ── Constraints DB
        if ($name === '') {
            $errors['name'] = ["Le nom est requis."];
        } elseif (mb_strlen($name) > 255) {
            $errors['name'] = ["Le nom ne peut pas dépasser 255 caractères."];
        }

        if ($description === '') {
            $errors['description'] = ["La description est requise."];
        } elseif (mb_strlen($description) < 5) {
            $errors['description'] = ["La description doit contenir au moins 5 caractères."];
        }
        if(empty($categoryId)){
            $errors['category_id'] = ["L'identifiant de catégorie est requis."];
        }
        $dto->errors = $errors;

        return $dto;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}