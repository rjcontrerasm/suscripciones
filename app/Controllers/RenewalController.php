<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Models\Renewal;

class RenewalController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['Administrador', 'Operador', 'Solo lectura']);

        $services = Database::connection()->query(
            'SELECT s.id, s.nombre_servicio, s.fecha_vencimiento, c.razon_social
             FROM servicios s
             INNER JOIN clientes c ON c.id = s.cliente_id
             WHERE s.deleted_at IS NULL
             ORDER BY s.fecha_vencimiento ASC'
        )->fetchAll();

        $payments = Database::connection()->query('SELECT id, numero_factura FROM pagos WHERE deleted_at IS NULL ORDER BY id DESC')->fetchAll();

        $this->view('renewals/index', [
            'services' => $services,
            'payments' => $payments,
            'renewals' => (new Renewal())->latest(100),
        ]);
    }

    public function create(): void
    {
        Auth::requireRole(['Administrador', 'Operador']);
        verify_csrf();

        (new Renewal())->renew([
            'servicio_id' => (int)$_POST['servicio_id'],
            'fecha_nueva' => $_POST['fecha_nueva'],
            'pago_id' => $_POST['pago_id'],
            'notas' => $_POST['notas'],
        ]);

        $this->redirect('/renovaciones');
    }
}
