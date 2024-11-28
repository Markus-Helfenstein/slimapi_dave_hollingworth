<?php

declare(strict_types=1);

namespace App;

use PDO;

class Database
{
    public function getConnection(): PDO
    {
        $pdo = new PDO($_ENV['DB_DSN'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        return $pdo;
    }
}
