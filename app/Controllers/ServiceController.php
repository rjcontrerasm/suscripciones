<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use PDOException;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['Administrador', 'Operador', 'Solo lectura']);
        $f = ['q' => trim($_GET['q'] ?? ''), 'estado' => trim($_GET['estado'] ?? '')];
        $db = Database::connection();
        $this->view('services/index', [
            'services' => (new Service())->all($f),
            'filters' => $f,
            'clientes' => $db->query('SELECT id, razon_social FROM clientes WHERE deleted_at IS NULL ORDER BY razon_social')->fetchAll(),
            'tipos' => $db->query('SELECT id, nombre FROM tipos_servicio ORDER BY nombre')->fetchAll(),
        ]);
    }

    public function create(): void
    {
        Auth::requireRole(['Administrador', 'Operador']);
        verify_csrf();
        try {
            (new Service())->create($this->payload());
            $_SESSION['ok'] = 'Servicio creado correctamente.';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error al crear servicio. Verifica datos y estructura de base de datos.';
        }
        $this->redirect('/servicios');
    }

    public function update(): void
    {
        Auth::requireRole(['Administrador', 'Operador']);
        verify_csrf();
        (new Service())->update((int)$_POST['id'], $this->payload());
        $this->redirect('/servicios');
    }

    public function delete(): void
    {
        Auth::requireRole(['Administrador']);
        verify_csrf();
        (new Service())->delete((int)$_POST['id']);
        $this->redirect('/servicios');
    }

    public function export(): void
    {
        Auth::requireRole(['Administrador', 'Operador', 'Solo lectura']);
        $rows = (new Service())->all(['q' => trim($_GET['q'] ?? ''), 'estado' => trim($_GET['estado'] ?? '')]);
        header('Content-Type:text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=servicios.csv');
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
            'cliente_id' => (int)($_POST['cliente_id'] ?? 0),
            'tipo_servicio_id' => (int)($_POST['tipo_servicio_id'] ?? 0),
            'nombre_servicio' => trim($_POST['nombre_servicio'] ?? ''),
            'proveedor' => trim($_POST['proveedor'] ?? ''),
            'fecha_inicio' => $_POST['fecha_inicio'] ?? date('Y-m-d'),
            'fecha_vencimiento' => $_POST['fecha_vencimiento'] ?? date('Y-m-d'),
            'periodo' => $_POST['periodo'] ?? 'Anual',
            'monto' => (float)($_POST['monto'] ?? 0),
            'moneda' => in_array(($_POST['moneda'] ?? 'USD'), ['USD','PEN'], true) ? $_POST['moneda'] : 'USD',
            'orden_servicio' => (int)($_POST['orden_servicio'] ?? 0),
            'estado' => $_POST['estado'] ?? 'Activo',
            'responsable' => trim($_POST['responsable'] ?? ''),
            'notas' => trim($_POST['notas'] ?? ''),
        ];
    }
}
