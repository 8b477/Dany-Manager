<?php
declare(strict_types=1);

namespace App\Dtos\UserDto\Commands;
use App\Dtos\UserDto\BaseUserAuthDto;

final class UserLoginDto extends BaseUserAuthDto
{
    public readonly string $email;
    public readonly string $password;
    public readonly bool $rememberMe;

    private function __construct(string $email, string $password, bool $rememberMe) {
        $this->email = $email;
        $this->password = $password;
        $this->rememberMe = $rememberMe;
    }


    public static function fromRequest(array $post): static{
    
        $email    = trim($post['email'] ?? '');
        $password = $post['password'] ?? '';
        $rememberMe = isset($post['remember_me']);

        $dto = new self($email, $password, $rememberMe);

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

        return $dto;
    }

}