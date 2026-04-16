<?php
declare(strict_types=1);

namespace App\Dtos\UserDto\Queries;

class UserQuerieDto
{
    public readonly int $id;
    public readonly string $name;
    public readonly string $email;

public function __construct(int $id, string $name, string $email) {
    $this->id = $id;
    $this->name = $name;
    $this->email = $email;
}
}