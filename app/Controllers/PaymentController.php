<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use PDOException;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['Administrador', 'Operador', 'Solo lectura']);
        $db = Database::connection();
        $this->view('payments/index', [
            'services' => $db->query('SELECT s.id,s.nombre_servicio,c.razon_social FROM servicios s INNER JOIN clientes c ON c.id=s.cliente_id WHERE s.deleted_at IS NULL ORDER BY s.id DESC LIMIT 100')->fetchAll(),
            'payments' => $db->query('SELECT p.*, s.nombre_servicio, c.razon_social FROM pagos p INNER JOIN servicios s ON s.id=p.servicio_id INNER JOIN clientes c ON c.id=s.cliente_id WHERE p.deleted_at IS NULL ORDER BY p.created_at DESC LIMIT 200')->fetchAll(),
        ]);
    }

    private function upload(): string
    {
        if (empty($_FILES['archivo_factura']['name'])) {
            return $_POST['archivo_factura_actual'] ?? '';
        }
        $f = time() . '_' . basename($_FILES['archivo_factura']['name']);
        move_uploaded_file($_FILES['archivo_factura']['tmp_name'], __DIR__ . '/../../public/uploads/' . $f);
        return $f;
    }

    public function create(): void
    {
        Auth::requireRole(['Administrador', 'Operador']);
        verify_csrf();
        try {
            (new Payment())->create($this->payload());
            $_SESSION['ok'] = 'Pago registrado correctamente.';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error al registrar pago. Revisa campos obligatorios.';
        }
        $this->redirect('/pagos');
    }

    public function update(): void
    {
        Auth::requireRole(['Administrador', 'Operador']);
        verify_csrf();
        (new Payment())->update((int)$_POST['id'], $this->payload());
        $this->redirect('/pagos');
    }

    public function delete(): void
    {
        Auth::requireRole(['Administrador']);
        verify_csrf();
        (new Payment())->delete((int)$_POST['id']);
        $this->redirect('/pagos');
    }

    public function export(): void
    {
        Auth::requireRole(['Administrador', 'Operador', 'Solo lectura']);
        $rows = Database::connection()->query('SELECT p.*, s.nombre_servicio, c.razon_social FROM pagos p INNER JOIN servicios s ON s.id=p.servicio_id INNER JOIN clientes c ON c.id=s.cliente_id WHERE p.deleted_at IS NULL ORDER BY p.created_at DESC')->fetchAll();
        header('Content-Type:text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=pagos.csv');
        $o = fopen('php://output', 'w');
        if ($rows) {
            fputcsv($o, array_keys($rows[0]));
            foreach ($rows as $r) {
                fputcsv($o, $r);
            }
        }
        fclose($o);
        exit;
    }

    private function payload(): array
    {
        return [
            'servicio_id' => (int)($_POST['servicio_id'] ?? 0),
            'estado_pago' => $_POST['estado_pago'] ?? 'Pendiente',
            'fecha_pago' => ($_POST['fecha_pago'] ?? '') ?: null,
            'monto_facturado' => (float)($_POST['monto_facturado'] ?? 0),
            'monto_pagado' => (float)($_POST['monto_pagado'] ?? 0),
            'numero_factura' => trim($_POST['numero_factura'] ?? ''),
            'link_factura' => trim($_POST['link_factura'] ?? ''),
            'archivo_factura' => $this->upload(),
            'observaciones' => trim($_POST['observaciones'] ?? ''),
        ];
    }
}
