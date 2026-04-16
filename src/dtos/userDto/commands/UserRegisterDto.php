<?php
declare(strict_types=1);

namespace App\Dtos\UserDto\Commands;

use App\Dtos\UserDto\BaseUserAuthDto;

final class UserRegisterDto extends BaseUserAuthDto
{
    public readonly string $username;
    public readonly string $email;
    public readonly string $password;
    public readonly bool $rememberMe;

    private function __construct(string $username, string $email, string $password, bool $rememberMe)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->rememberMe = $rememberMe;
    }

    public static function fromRequest(array $post): static
    {
        $username = trim($post['username'] ?? '');
        $email = trim($post['email'] ?? '');
        $password = $post['password'] ?? '';
        $confirm = $post['confirm_password'] ?? '';
        $rememberMe = isset($post['remember_me']);

        $dto = new self($username, $email, $password, $rememberMe);

        if ($username === '') {
            $dto->addError("Le nom d'utilisateur est requis.");
        } elseif (mb_strlen($username) > 128) {
            $dto->addError("Le nom d'utilisateur ne peut pas dépasser 128 caractères.");
        }

        if ($email === '') {
            $dto->addError("L'email est requis.");
        } elseif (mb_strlen($email) > 128) {
            $dto->addError("L'email ne peut pas dépasser 128 caractères.");
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $dto->addError("L'email n'est pas valide.");
        }

        if ($password === '') {
            $dto->addError("Le mot de passe est requis.");
        } elseif (mb_strlen($password) < 5) {
            $dto->addError("Le mot de passe doit contenir au moins 5 caractères.");
        }

        if ($password !== $confirm) {
            $dto->addError("Les mots de passe ne correspondent pas.");
        }

        return $dto;
    }
}