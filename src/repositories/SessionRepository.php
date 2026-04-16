<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Configs\Database;
use App\Entities\UserSession;
use DateTimeImmutable;

class SessionRepository
{
    public function setUserSession(UserSession $user): void
    {
        $sql = "INSERT INTO userSession (token, expiration, userId) VALUES (:token, :expiration, :userId)";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([
            ':token' => $user->getToken(),
            ':expiration' => $user->getExpiration()->format('Y-m-d H:i:s'),
            ':userId' => $user->getUserId(),
        ]);
    }

    public function findByToken(string $token): ?UserSession
    {
        $sql = "SELECT id, token, expiration, userId FROM userSession WHERE token = :token LIMIT 1";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([':token' => $token]);

        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        return new UserSession(
            (int) $data->id,
            $data->token,
            new DateTimeImmutable($data->expiration),
            (int) $data->userId
        );
    }

    public function getByUserID(int $userId): ?UserSession
    {
        $sql = "SELECT id, token, expiration, userId FROM userSession WHERE userId = :userId LIMIT 1";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([':userId' => $userId]);

        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        return new UserSession(
            (int) $data->id,
            $data->token,
            new DateTimeImmutable($data->expiration),
            (int) $data->userId
        );
    }

    public function updateSession(UserSession $session): void
    {
        $sql = "UPDATE userSession SET token = :token, expiration = :expiration WHERE id = :id";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([
            ':token' => $session->getToken(),
            ':expiration' => $session->getExpiration()->format('Y-m-d H:i:s'),
            ':id' => $session->getId(),
        ]);
    }
}
