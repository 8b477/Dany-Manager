<?php
declare(strict_types=1);

namespace App\Dtos\UserDto;

abstract class BaseUserAuthDto
{
    protected array $errors = [];

    protected function addError(string $message): void
    {
        $this->errors[] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    abstract public static function fromRequest(array $post): static;
}