<?php

namespace App\Models;

class Payment extends BaseModel
{
    public function byService(int $serviceId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM pagos WHERE servicio_id = :id AND deleted_at IS NULL ORDER BY created_at DESC');
        $stmt->execute(['id' => $serviceId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO pagos (servicio_id, estado_pago, fecha_pago, monto_facturado, monto_pagado, numero_factura, link_factura, archivo_factura, observaciones, created_at, updated_at)
             VALUES (:servicio_id, :estado_pago, :fecha_pago, :monto_facturado, :monto_pagado, :numero_factura, :link_factura, :archivo_factura, :observaciones, NOW(), NOW())'
        );
        $stmt->execute($data);
    }
}
