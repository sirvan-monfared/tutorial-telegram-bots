<?php

namespace App\Core;

use AllowDynamicProperties;
use PDO;

#[AllowDynamicProperties]
class Database
{
    public $pdo;
    private $statement;

    public function __construct()
    {
        $dsn = "mysql:host=". env('DB_HOST') .";dbname=". env('DB_NAME') .";charset=utf8mb4";

        $this->pdo = new PDO($dsn, env('DB_USER'), env('DB_PASS'), [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function prepare($sql, $params = [], $class = null)
    {
        $this->statement = $this->pdo->prepare($sql);
        
        if ($class) {
            $this->statement->setFetchMode(PDO::FETCH_CLASS, $class);
        }
        
        $this->statement->execute($params);

        return $this;
    }

    public function all($class = null) 
    {
        if ($class) {
            $this->statement->setFetchMode(PDO::FETCH_CLASS, $class);
        }
        return $this->statement->fetchAll();
    }

    public function find()
    {
        $result = $this->statement->fetch();
        if (! $result) {
            return null;
        }

        return $result;
    }

    public function findOrFail() 
    {
        $item = $this->find();

        if (! $item) {
            abort();
        }

        return $item;
    }

    public function lastInsertedId(): bool|string
    {
        return (int) $this->pdo->lastInsertId();
    }

    public function countFromSQL($sql, $values, $class = null): int
    {
        $count_sql = str_replace("SELECT * FROM", "SELECT COUNT(`id`) AS count FROM", $sql);

        return $this->prepare($count_sql, $values, $class)->find()->count;
    }

    public function paginate($sql, $values = [], $class = null, $per_page = 10): object
    {
        $paginator = new Paginator($per_page, $this->countFromSQL($sql, $values, $class));
        
        $sql .= " LIMIT {$paginator->perPage()} OFFSET {$paginator->offset()}";

        return (object) [
            'items' => $this->prepare($sql, $values, $class)->all(),
            'paginator' => $paginator
        ];
    }
}
