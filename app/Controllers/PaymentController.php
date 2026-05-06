<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['Administrador', 'Operador', 'Solo lectura']);
        $services = Database::connection()->query(
            'SELECT s.id, s.nombre_servicio, c.razon_social FROM servicios s
             INNER JOIN clientes c ON c.id = s.cliente_id
             WHERE s.deleted_at IS NULL ORDER BY s.id DESC LIMIT 100'
        )->fetchAll();

        $rows = Database::connection()->query(
            'SELECT p.*, s.nombre_servicio, c.razon_social FROM pagos p
             INNER JOIN servicios s ON s.id = p.servicio_id
             INNER JOIN clientes c ON c.id = s.cliente_id
             WHERE p.deleted_at IS NULL
             ORDER BY p.created_at DESC LIMIT 200'
        )->fetchAll();

        $this->view('payments/index', ['services' => $services, 'payments' => $rows]);
    }

    public function create(): void
    {
        Auth::requireRole(['Administrador', 'Operador']);
        verify_csrf();

        $fileName = '';
        if (!empty($_FILES['archivo_factura']['name'])) {
            $fileName = time() . '_' . basename($_FILES['archivo_factura']['name']);
            move_uploaded_file($_FILES['archivo_factura']['tmp_name'], __DIR__ . '/../../public/uploads/' . $fileName);
        }

        (new Payment())->create([
            'servicio_id' => $_POST['servicio_id'],
            'estado_pago' => $_POST['estado_pago'],
            'fecha_pago' => $_POST['fecha_pago'] ?: null,
            'monto_facturado' => $_POST['monto_facturado'],
            'monto_pagado' => $_POST['monto_pagado'],
            'numero_factura' => $_POST['numero_factura'],
            'link_factura' => $_POST['link_factura'],
            'archivo_factura' => $fileName,
            'observaciones' => $_POST['observaciones'],
        ]);

        $this->redirect('/pagos');
    }
}
