<?php

namespace App\Services;

use PDO;

class Database
{
    private PDO $pdo;
    private $statement;

    public function __construct()
    {
        $dsn = "mysql:host=" . env('DB_HOST') . ";dbname=" . env('DB_NAME') . ";charset=utf8mb4";

        $this->pdo = new PDO($dsn, env('DB_USER'), env('DB_PASS'), [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }


    public function prepare(string $sql, ?array $values = null)
    {
        $this->statement = $this->pdo->prepare($sql);

        $this->statement->execute($values);

        return $this;
    }

    public function fetchAll() {}

    public function find()
    {
        return $this->statement->fetch();
    }
}
