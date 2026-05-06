<?php
namespace App\Models;
class Renewal extends BaseModel
{
    public function latest(int $limit=100): array { $s=$this->db->prepare('SELECT r.*, s.nombre_servicio, c.razon_social FROM renovaciones r INNER JOIN servicios s ON s.id=r.servicio_id INNER JOIN clientes c ON c.id=s.cliente_id ORDER BY r.created_at DESC LIMIT :limit'); $s->bindValue('limit',$limit,\PDO::PARAM_INT); $s->execute(); return $s->fetchAll(); }
    public function renew(array $data): void { $this->db->beginTransaction(); $srv=$this->db->prepare('SELECT fecha_vencimiento FROM servicios WHERE id=:id FOR UPDATE'); $srv->execute(['id'=>$data['servicio_id']]); $prev=$srv->fetchColumn(); $this->db->prepare('INSERT INTO renovaciones (servicio_id,fecha_anterior,fecha_nueva,pago_id,notas,created_at,updated_at) VALUES (:servicio_id,:fecha_anterior,:fecha_nueva,:pago_id,:notas,NOW(),NOW())')->execute(['servicio_id'=>$data['servicio_id'],'fecha_anterior'=>$prev,'fecha_nueva'=>$data['fecha_nueva'],'pago_id'=>$data['pago_id']?:null,'notas'=>$data['notas']]); $this->db->prepare('UPDATE servicios SET fecha_vencimiento=:fecha,estado=:estado,updated_at=NOW() WHERE id=:id')->execute(['fecha'=>$data['fecha_nueva'],'estado'=>'Activo','id'=>$data['servicio_id']]); $this->db->commit(); }
    public function update(int $id,array $d): void { $d['id']=$id; $this->db->prepare('UPDATE renovaciones SET fecha_nueva=:fecha_nueva,pago_id=:pago_id,notas=:notas,updated_at=NOW() WHERE id=:id')->execute(['fecha_nueva'=>$d['fecha_nueva'],'pago_id'=>$d['pago_id']?:null,'notas'=>$d['notas'],'id'=>$id]); }
    public function delete(int $id): void { $this->db->prepare('DELETE FROM renovaciones WHERE id=:id')->execute(['id'=>$id]); }
}
