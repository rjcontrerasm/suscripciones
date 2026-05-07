<?php
namespace App\Models;
class Payment extends BaseModel
{
    public function create(array $d): void { $this->db->prepare('INSERT INTO pagos (servicio_id,estado_pago,fecha_pago,monto_facturado,monto_pagado,numero_factura,link_factura,archivo_factura,observaciones,created_at,updated_at) VALUES (:servicio_id,:estado_pago,:fecha_pago,:monto_facturado,:monto_pagado,:numero_factura,:link_factura,:archivo_factura,:observaciones,NOW(),NOW())')->execute($d); }
    public function update(int $id,array $d): void { $d['id']=$id; $this->db->prepare('UPDATE pagos SET servicio_id=:servicio_id,estado_pago=:estado_pago,fecha_pago=:fecha_pago,monto_facturado=:monto_facturado,monto_pagado=:monto_pagado,numero_factura=:numero_factura,link_factura=:link_factura,archivo_factura=:archivo_factura,observaciones=:observaciones,updated_at=NOW() WHERE id=:id AND deleted_at IS NULL')->execute($d); }
    public function delete(int $id): void { $this->db->prepare('UPDATE pagos SET deleted_at=NOW(),updated_at=NOW() WHERE id=:id')->execute(['id'=>$id]); }
}
