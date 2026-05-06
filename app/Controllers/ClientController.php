<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Client;

class ClientController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['Administrador', 'Operador', 'Solo lectura']);
        $model = new Client();
        $search = trim($_GET['q'] ?? '');
        $this->view('clients/index', [
            'clients' => $model->all($search),
            'search' => $search,
        ]);
    }

    public function create(): void
    {
        Auth::requireRole(['Administrador', 'Operador']);
        verify_csrf();

        $ruc = trim($_POST['ruc'] ?? '');
        if ($ruc === '') {
            $_SESSION['error'] = 'El RUC es obligatorio';
            $this->redirect('/clientes');
        }

        (new Client())->create([
            'tipo_cliente' => $_POST['tipo_cliente'],
            'ruc' => $ruc,
            'razon_social' => $_POST['razon_social'],
            'direccion' => $_POST['direccion'],
            'telefono' => $_POST['telefono'],
            'correo' => $_POST['correo'],
            'contacto' => $_POST['contacto'],
            'estado' => $_POST['estado'],
        ]);

        $this->redirect('/clientes');
    }
}
