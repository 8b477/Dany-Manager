<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Entities\User;
use App\Configs\Database;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function add(User $user): ?User
    {
        $stmt = $this->db->prepare('INSERT INTO user (email, username, password) VALUES (:email, :username, :password)');
        $result = $stmt->execute([
            ':email' => $user->getEmail(),
            ':username' => $user->getUsername(),
            ':password' => $user->getPassword(),
        ]);
        
        if($result) {
            $userId = (int)$this->db->lastInsertId();
            return new User(
                $userId,
                $user->getEmail(),
                $user->getUsername(),
                ""
            );
        }else{
            return null;
        }
    }

    public function existsByEmail(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM user WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() !== false;
    }

    public function getByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM user WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();
        if (!$data) {
            return null;
        }
        return new User($data->id, $data->email, $data->username, $data->password);
    }

    public function getById(int $id): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM user WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        
        if (!$data) {
            return null;
        }
        return new User($data->id, $data->email, $data->username, $data->password);
    }

}