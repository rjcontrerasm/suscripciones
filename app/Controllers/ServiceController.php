<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['Administrador', 'Operador', 'Solo lectura']);
        $filters = [
            'q' => trim($_GET['q'] ?? ''),
            'estado' => trim($_GET['estado'] ?? ''),
        ];

        $clientes = Database::connection()->query('SELECT id, razon_social FROM clientes WHERE deleted_at IS NULL ORDER BY razon_social')->fetchAll();
        $tipos = Database::connection()->query('SELECT id, nombre FROM tipos_servicio ORDER BY nombre')->fetchAll();

        $this->view('services/index', [
            'services' => (new Service())->all($filters),
            'filters' => $filters,
            'clientes' => $clientes,
            'tipos' => $tipos,
        ]);
    }

    public function create(): void
    {
        Auth::requireRole(['Administrador', 'Operador']);
        verify_csrf();

        (new Service())->create([
            'cliente_id' => $_POST['cliente_id'],
            'tipo_servicio_id' => $_POST['tipo_servicio_id'],
            'nombre_servicio' => $_POST['nombre_servicio'],
            'proveedor' => $_POST['proveedor'],
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_vencimiento' => $_POST['fecha_vencimiento'],
            'periodo' => $_POST['periodo'],
            'monto' => $_POST['monto'],
            'moneda' => $_POST['moneda'],
            'estado' => $_POST['estado'],
            'responsable' => $_POST['responsable'],
            'notas' => $_POST['notas'],
        ]);

        $this->redirect('/servicios');
    }
}
