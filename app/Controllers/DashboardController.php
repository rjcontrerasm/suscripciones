<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Renewal;
use App\Models\Service;

class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['Administrador', 'Operador', 'Solo lectura']);
        $service = new Service();
        $renewal = new Renewal();

        $this->view('dashboard/index', [
            'metrics' => $service->dashboard(),
            'v7' => $service->expiringByDays(7),
            'v15' => $service->expiringByDays(15),
            'v30' => $service->expiringByDays(30),
            'renovaciones' => $renewal->latest(),
        ]);
    }
}
