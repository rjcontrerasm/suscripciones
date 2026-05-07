<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\ClientController;
use App\Controllers\DashboardController;
use App\Controllers\DomainController;
use App\Controllers\PaymentController;
use App\Controllers\RenewalController;
use App\Controllers\ReportController;
use App\Controllers\ServiceController;
use App\Core\Auth;
use App\Core\Router;

session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();

require __DIR__ . '/../app/Core/helpers.php';

spl_autoload_register(function ($class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $file = __DIR__ . '/../app/' . str_replace('App\\', '', $class);
    $file = str_replace('\\', '/', $file) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

$appConfig = require __DIR__ . '/../config/app.php';
date_default_timezone_set($appConfig['timezone']);

$router = new Router();
$router->get('/', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/clientes', [ClientController::class, 'index']);
$router->post('/clientes/crear', [ClientController::class, 'create']);

$router->post('/clientes/actualizar', [ClientController::class, 'update']);
$router->post('/clientes/eliminar', [ClientController::class, 'delete']);
$router->get('/clientes/exportar', [ClientController::class, 'export']);
$router->post('/servicios/actualizar', [ServiceController::class, 'update']);
$router->post('/servicios/eliminar', [ServiceController::class, 'delete']);
$router->get('/servicios/exportar', [ServiceController::class, 'export']);
$router->post('/pagos/actualizar', [PaymentController::class, 'update']);
$router->post('/pagos/eliminar', [PaymentController::class, 'delete']);
$router->get('/pagos/exportar', [PaymentController::class, 'export']);
$router->post('/renovaciones/actualizar', [RenewalController::class, 'update']);
$router->post('/renovaciones/eliminar', [RenewalController::class, 'delete']);
$router->get('/renovaciones/exportar', [RenewalController::class, 'export']);

$router->get('/servicios', [ServiceController::class, 'index']);
$router->post('/servicios/crear', [ServiceController::class, 'create']);
$router->get('/pagos', [PaymentController::class, 'index']);
$router->post('/pagos/crear', [PaymentController::class, 'create']);
$router->get('/renovaciones', [RenewalController::class, 'index']);
$router->post('/renovaciones/crear', [RenewalController::class, 'create']);
$router->get('/dominios', [DomainController::class, 'index']);
$router->post('/dominios/crear', [DomainController::class, 'create']);
$router->post('/dominios/eliminar', [DomainController::class, 'delete']);
$router->get('/dominios/exportar', [DomainController::class, 'export']);
$router->get('/reportes', [ReportController::class, 'index']);
$router->get('/reportes/csv', [ReportController::class, 'exportCsv']);

if (!Auth::check() && !in_array(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), ['/', '/login'], true)) {
    header('Location: /');
    exit;
}

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
