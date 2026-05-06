<?php

namespace App\Models;

class Client extends BaseModel
{
    public function all(string $search = ''): array
    {
        $sql = 'SELECT * FROM clientes WHERE deleted_at IS NULL';
        $params = [];

        if ($search !== '') {
            $sql .= ' AND (ruc LIKE :search OR razon_social LIKE :search OR correo LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY created_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM clientes WHERE id = :id AND deleted_at IS NULL');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO clientes (tipo_cliente, ruc, razon_social, direccion, telefono, correo, contacto, estado, created_at, updated_at)
             VALUES (:tipo_cliente, :ruc, :razon_social, :direccion, :telefono, :correo, :contacto, :estado, NOW(), NOW())'
        );
        $stmt->execute($data);
    }
}
