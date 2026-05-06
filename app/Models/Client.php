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
    public function create(array $data): void { $this->db->prepare('INSERT INTO clientes (tipo_cliente,ruc,razon_social,direccion,telefono,correo,contacto,estado,created_at,updated_at) VALUES (:tipo_cliente,:ruc,:razon_social,:direccion,:telefono,:correo,:contacto,:estado,NOW(),NOW())')->execute($data); }
    public function update(int $id, array $data): void { $data['id']=$id; $this->db->prepare('UPDATE clientes SET tipo_cliente=:tipo_cliente,ruc=:ruc,razon_social=:razon_social,direccion=:direccion,telefono=:telefono,correo=:correo,contacto=:contacto,estado=:estado,updated_at=NOW() WHERE id=:id AND deleted_at IS NULL')->execute($data); }
    public function delete(int $id): void { $this->db->prepare('UPDATE clientes SET deleted_at=NOW(), updated_at=NOW() WHERE id=:id')->execute(['id'=>$id]); }
}
