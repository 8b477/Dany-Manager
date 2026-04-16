<?php
declare(strict_types=1);

namespace App\Entities;
use DateTimeImmutable;

class UserSession
{
    private ?int $id;
    private string $token;
    private DateTimeImmutable $expiration;
    private int $userId;

    public function __construct(?int $id, string $token, DateTimeImmutable $expiration, int $userId)
    {
        $this->id = $id;
        $this->token = $token;
        $this->expiration = $expiration;
        $this->userId = $userId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpiration(): DateTimeImmutable
    {
        return $this->expiration;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}