<?php

namespace App\Models;

class Renewal extends BaseModel
{
    public function latest(int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            'SELECT r.*, s.nombre_servicio, c.razon_social
             FROM renovaciones r
             INNER JOIN servicios s ON s.id = r.servicio_id
             INNER JOIN clientes c ON c.id = s.cliente_id
             ORDER BY r.created_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function renew(array $data): void
    {
        $this->db->beginTransaction();

        $service = $this->db->prepare('SELECT fecha_vencimiento FROM servicios WHERE id = :id FOR UPDATE');
        $service->execute(['id' => $data['servicio_id']]);
        $current = $service->fetchColumn();

        $stmt = $this->db->prepare(
            'INSERT INTO renovaciones (servicio_id, fecha_anterior, fecha_nueva, pago_id, notas, created_at, updated_at)
             VALUES (:servicio_id, :fecha_anterior, :fecha_nueva, :pago_id, :notas, NOW(), NOW())'
        );
        $stmt->execute([
            'servicio_id' => $data['servicio_id'],
            'fecha_anterior' => $current,
            'fecha_nueva' => $data['fecha_nueva'],
            'pago_id' => $data['pago_id'] ?: null,
            'notas' => $data['notas'],
        ]);

        $update = $this->db->prepare('UPDATE servicios SET fecha_vencimiento = :fecha, estado = :estado, updated_at = NOW() WHERE id = :id');
        $update->execute([
            'fecha' => $data['fecha_nueva'],
            'estado' => 'Activo',
            'id' => $data['servicio_id'],
        ]);

        $this->db->commit();
    }
}
