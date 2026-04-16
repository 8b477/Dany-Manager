<?php
declare(strict_types=1);

namespace App\Dtos\FormDto;

class FormResult
{
    private array $errors = [];
    private ?string $successMessage = null;
    private array $data = [];

    public function addError(string $message): self
    {
        $this->errors[] = $message;
        return $this;
    }

    public function setSuccess(string $message): self
    {
        $this->successMessage = $message;
        return $this;
    }

    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function isSuccess(): bool
    {
        return empty($this->errors) && $this->successMessage !== null;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getSuccessMessage(): ?string
    {
        return $this->successMessage;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
