<?php

namespace App\Models;

class Service extends BaseModel
{
    public function all(array $filters = []): array
    {
        $sql = 'SELECT s.*, c.razon_social, c.ruc,
                       DATEDIFF(s.fecha_vencimiento, CURDATE()) AS dias_restantes
                FROM servicios s
                INNER JOIN clientes c ON c.id = s.cliente_id
                WHERE s.deleted_at IS NULL';
        $params = [];

        if (!empty($filters['estado'])) {
            $sql .= ' AND s.estado = :estado';
            $params['estado'] = $filters['estado'];
        }

        if (!empty($filters['q'])) {
            $sql .= ' AND (s.nombre_servicio LIKE :q OR c.razon_social LIKE :q OR c.ruc LIKE :q OR s.proveedor LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        $sql .= ' ORDER BY s.fecha_vencimiento ASC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create(array $data): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO servicios (cliente_id, tipo_servicio_id, nombre_servicio, proveedor, fecha_inicio, fecha_vencimiento, periodo, monto, moneda, estado, responsable, notas, created_at, updated_at)
             VALUES (:cliente_id, :tipo_servicio_id, :nombre_servicio, :proveedor, :fecha_inicio, :fecha_vencimiento, :periodo, :monto, :moneda, :estado, :responsable, :notas, NOW(), NOW())'
        );
        $stmt->execute($data);
    }

    public function expiringByDays(int $days): array
    {
        $stmt = $this->db->prepare(
            'SELECT s.*, c.razon_social
             FROM servicios s
             INNER JOIN clientes c ON c.id = s.cliente_id
             WHERE s.deleted_at IS NULL
             AND s.fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :days DAY)
             ORDER BY s.fecha_vencimiento ASC'
        );
        $stmt->bindValue('days', $days, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function dashboard(): array
    {
        return [
            'clientes_activos' => (int)$this->db->query("SELECT COUNT(*) FROM clientes WHERE estado='activo' AND deleted_at IS NULL")->fetchColumn(),
            'servicios_activos' => (int)$this->db->query("SELECT COUNT(*) FROM servicios WHERE estado='Activo' AND deleted_at IS NULL")->fetchColumn(),
            'servicios_vencidos' => (int)$this->db->query("SELECT COUNT(*) FROM servicios WHERE fecha_vencimiento < CURDATE() AND deleted_at IS NULL")->fetchColumn(),
            'pendientes_pago' => (int)$this->db->query("SELECT COUNT(*) FROM pagos WHERE estado_pago IN ('Pendiente','Vencido') AND deleted_at IS NULL")->fetchColumn(),
            'monto_pendiente' => (float)$this->db->query("SELECT COALESCE(SUM(monto_facturado - monto_pagado),0) FROM pagos WHERE estado_pago IN ('Pendiente','Parcial','Vencido') AND deleted_at IS NULL")->fetchColumn(),
            'monto_mes' => (float)$this->db->query("SELECT COALESCE(SUM(monto_pagado),0) FROM pagos WHERE MONTH(fecha_pago)=MONTH(CURDATE()) AND YEAR(fecha_pago)=YEAR(CURDATE()) AND deleted_at IS NULL")->fetchColumn(),
        ];
    }
}
