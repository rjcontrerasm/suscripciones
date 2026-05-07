<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Models\Domain;
use PDOException;

class DomainController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['Administrador', 'Operador', 'Solo lectura']);
        $q = trim($_GET['q'] ?? '');
        $db = Database::connection();
        $this->view('domains/index', [
            'domains' => (new Domain())->all($q),
            'q' => $q,
            'clientes' => $db->query('SELECT id, razon_social FROM clientes WHERE deleted_at IS NULL ORDER BY razon_social')->fetchAll(),
            'servicios' => $db->query('SELECT id, nombre_servicio FROM servicios WHERE deleted_at IS NULL ORDER BY nombre_servicio')->fetchAll(),
        ]);
    }

    public function create(): void
    {
        Auth::requireRole(['Administrador', 'Operador']);
        verify_csrf();
        try {
            (new Domain())->create([
                'dominio' => trim($_POST['dominio'] ?? ''),
                'cliente_id' => (int)($_POST['cliente_id'] ?? 0) ?: null,
                'servicio_id' => (int)($_POST['servicio_id'] ?? 0) ?: null,
                'proveedor' => trim($_POST['proveedor'] ?? ''),
                'fecha_inicio' => $_POST['fecha_inicio'] ?? date('Y-m-d'),
                'fecha_vencimiento' => $_POST['fecha_vencimiento'] ?? date('Y-m-d', strtotime('+1 year')),
                'monto' => (float)($_POST['monto'] ?? 0),
                'moneda' => in_array($_POST['moneda'] ?? 'USD', ['USD','PEN'], true) ? $_POST['moneda'] : 'USD',
                'estado' => $_POST['estado'] ?? 'Activo',
                'notas' => trim($_POST['notas'] ?? ''),
            ]);
            $_SESSION['ok'] = 'Dominio registrado correctamente.';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'No se pudo registrar el dominio.';
        }
        $this->redirect('/dominios');
    }

    public function delete(): void
    {
        Auth::requireRole(['Administrador']);
        verify_csrf();
        (new Domain())->delete((int)$_POST['id']);
        $_SESSION['ok'] = 'Dominio eliminado.';
        $this->redirect('/dominios');
    }

    public function export(): void
    {
        Auth::requireRole(['Administrador', 'Operador', 'Solo lectura']);
        $rows = (new Domain())->all(trim($_GET['q'] ?? ''));
        header('Content-Type:text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=dominios.csv');
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
}
