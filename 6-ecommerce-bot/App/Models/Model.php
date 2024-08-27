<?php
namespace App\Models;

use AllowDynamicProperties;
use App\Core\Database;

#[AllowDynamicProperties]
abstract class Model {

    protected string $table = '';
    protected Database $db;
    protected bool $timestamps = true;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function __get(string $name)
    {
        if (! property_exists($this, $name)) {
            return null;
        }

        return $this->$name;
    }

    public function all(?int $limit = null, string $order_by = "")
    {
        $sql = "SELECT * FROM `{$this->table}`";

        if ($order_by) {
            $sql .= " ORDER BY {$order_by}";
        } else {
            $sql .= " ORDER BY `id` DESC";
        }

        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }

        return $this->db->prepare($sql, class: get_called_class())->all();
    }

    public function findOrFail($id): ?static
    {
        return $this->db->prepare("SELECT * FROM `{$this->table}` WHERE id=:id", ['id' => $id], get_called_class())->findOrFail();

    }

    public function find($id): ?static
    {
        return $this->db->prepare("SELECT * FROM `{$this->table}` WHERE id=:id", ['id' => $id], get_called_class())->find();
    }

    public function create(array $data): ?static
    {
        if ($this->timestamps) {
            $data = $data + ['created_at' => now(), 'updated_at' => now()];
        }

        $sql = "INSERT INTO `{$this->table}` (" . implode(', ', array_keys($data)) . ") ";
        $sql .= "VALUES (:" . implode(', :', array_keys($data)) . ")";

        $this->db->prepare($sql, $data);

        return $this->find($this->db->lastInsertedId());
    }

    public function update(array $data): static
    {
        if ($this->timestamps) {
            $data = $data + ['updated_at' => now()];
        }

        if (! isset($data['id'])) {
            $data['id'] = $this->id;
        }

        $sql = "UPDATE {$this->table} SET ";

        foreach($data as $key => $value) {
            $sql .= "`$key`=:$key,";
        }

        $sql = rtrim($sql, ',');
        $sql .= " WHERE `id`=:id";

        $this->db->prepare($sql, $data);

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }

    public function where($column, $value): static|bool|null
    {
        return $this->db->prepare("SELECT * FROM `{$this->table}` WHERE {$column}=:{$column}", [
            "$column" => $value
        ], get_called_class())->find();
    }

    public function whereAll($column, $value): array|bool
    {
        return $this->db->prepare("SELECT * FROM `{$this->table}` WHERE {$column}=:{$column}", [
            "$column" => $value
        ], get_called_class())->all();
    }

    public function delete(): void
    {
        $sql = "DELETE FROM {$this->table} WHERE `id`=:id";

        $this->db->prepare($sql, [
            'id' => $this->id
        ]);
    }
}