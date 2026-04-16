<?php
declare(strict_types=1);

namespace App\Dtos\CategoryCardDto\Command;

class CategoryDto
{
    public readonly ?int $id;
    public readonly string $name;
    public readonly int $position;


    public array $errors = [];

    private function __construct(?int $id, string $name, int $position)
    {
        $this->id = $id;
        $this->name = $name;
        $this->position = $position;
    }

    /**
     * Factory depuis $_POST — hydrate et valide en une seule passe.
     */
    public static function fromRequest(array $post): static{

        $name    = trim($post['name'] ?? '');
        $position = (int)($post['position'] ?? 0);
        $dto = new self(null, $name, $position);

        $errors = [];
        // ── Contraintes miroir de la DB ──

        if ($name === '') {
            $errors['name'] = "Le nom est requis.";
        } elseif (mb_strlen($name) > 128) {
            $errors['name'] = "Le nom ne peut pas dépasser 128 caractères.";
        }

        // position : INT NOT NULL
        if ($position < 0) {
            $errors['position'] = "La position doit être un entier positif.";
        }

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