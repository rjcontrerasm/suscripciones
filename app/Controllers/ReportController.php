<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;

class ReportController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['Administrador', 'Operador', 'Solo lectura']);
        $this->view('reports/index');
    }

    public function exportCsv(): void
    {
        Auth::requireRole(['Administrador', 'Operador', 'Solo lectura']);
        $type = $_GET['type'] ?? 'vencidos';

        $queries = [
            'vencidos' => "SELECT c.razon_social, s.nombre_servicio, s.fecha_vencimiento, s.estado FROM servicios s INNER JOIN clientes c ON c.id=s.cliente_id WHERE s.fecha_vencimiento<CURDATE()",
            'por_vencer' => "SELECT c.razon_social, s.nombre_servicio, s.fecha_vencimiento, s.estado FROM servicios s INNER JOIN clientes c ON c.id=s.cliente_id WHERE s.fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)",
            'pagos_pendientes' => "SELECT c.razon_social, s.nombre_servicio, p.estado_pago, p.monto_facturado, p.monto_pagado FROM pagos p INNER JOIN servicios s ON s.id=p.servicio_id INNER JOIN clientes c ON c.id=s.cliente_id WHERE p.estado_pago IN ('Pendiente','Parcial','Vencido')",
            'renovaciones' => "SELECT r.created_at, c.razon_social, s.nombre_servicio, r.fecha_anterior, r.fecha_nueva FROM renovaciones r INNER JOIN servicios s ON s.id=r.servicio_id INNER JOIN clientes c ON c.id=s.cliente_id",
        ];

        $sql = $queries[$type] ?? $queries['vencidos'];
        $rows = Database::connection()->query($sql)->fetchAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=reporte_' . $type . '.csv');

        $output = fopen('php://output', 'w');
        if (!empty($rows)) {
            fputcsv($output, array_keys($rows[0]));
            foreach ($rows as $row) {
                fputcsv($output, $row);
            }
        }
        fclose($output);
        exit;
    }
}
