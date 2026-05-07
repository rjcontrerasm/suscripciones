<?php

namespace App\Models;

class Domain extends BaseModel
{
    public function all(string $q = ''): array
    {
        $sql = 'SELECT d.*, c.razon_social, s.nombre_servicio, DATEDIFF(d.fecha_vencimiento, CURDATE()) AS dias_restantes
                FROM dominios d
                LEFT JOIN clientes c ON c.id = d.cliente_id
                LEFT JOIN servicios s ON s.id = d.servicio_id
                WHERE d.deleted_at IS NULL';
        $params = [];
        if ($q !== '') {
            $sql .= ' AND (d.dominio LIKE :q OR c.razon_social LIKE :q)';
            $params['q'] = '%' . $q . '%';
        }
        $sql .= ' ORDER BY d.fecha_vencimiento ASC';
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function create(array $d): void
    {
        $this->db->prepare('INSERT INTO dominios (dominio, cliente_id, servicio_id, proveedor, fecha_inicio, fecha_vencimiento, monto, moneda, estado, notas, created_at, updated_at) VALUES (:dominio,:cliente_id,:servicio_id,:proveedor,:fecha_inicio,:fecha_vencimiento,:monto,:moneda,:estado,:notas,NOW(),NOW())')->execute($d);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('UPDATE dominios SET deleted_at=NOW(), updated_at=NOW() WHERE id=:id')->execute(['id' => $id]);
    }
}
