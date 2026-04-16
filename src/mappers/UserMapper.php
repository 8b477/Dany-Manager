<?php
declare(strict_types=1);

namespace App\Mappers;
use App\Entities\User as UserEntity;
use App\Dtos\UserDto\Queries\UserQuerieDto;
use App\Dtos\UserDto\Commands\userRegisterDto;

class UserMapper
{
// Exposition
    public static function toDto(UserEntity $user): UserQuerieDto
    {
        return new UserQuerieDto(
            $user->getId(),
            $user->getUsername(),
            $user->getEmail()
        );
    }
// Insertion
    public static function toEntity(userRegisterDto $userDto): UserEntity
    {
        return new UserEntity(
            null, // DB responsible for ID generation
            $userDto->email,
            $userDto->username,
            password_hash($userDto->password, PASSWORD_DEFAULT)
        );
    }
}