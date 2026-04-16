<?php
declare(strict_types=1);

namespace App\Configs;

use PDO;
use App\Configs\AppConfig;

class Database
{
    //?PDO signifie potentiellement null.
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $config = AppConfig::database();

            $connectionString = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['database'],
                $config['charset']
            );

            self::$instance = new PDO(
                                    $connectionString,
                                    $config["username"],
                                    $config["password"],
                                    [
                                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                                    ]
            );
        }
        return self::$instance;
    }
}
