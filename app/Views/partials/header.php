<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($app['name']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/style.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
  <aside class="sidebar p-3 text-white">
    <h5>ARPYNET</h5>
    <nav class="nav flex-column">
      <a class="nav-link" href="/dashboard">Dashboard</a>
      <a class="nav-link" href="/clientes">Clientes</a>
      <a class="nav-link" href="/servicios">Servicios</a>
      <a class="nav-link" href="/pagos">Pagos</a>
      <a class="nav-link" href="/renovaciones">Renovaciones</a>
      <a class="nav-link" href="/reportes">Reportes</a>
      <form action="/logout" method="POST" class="mt-3">
        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
        <button class="btn btn-warning btn-sm">Salir</button>
      </form>
    </nav>
  </aside>
  <main class="content p-4">
    <?php if (!empty($_SESSION['error'])): ?><div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= e($_SESSION['error']); unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div><?php endif; ?>
    <?php if (!empty($_SESSION['ok'])): ?><div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= e($_SESSION['ok']); unset($_SESSION['ok']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div><?php endif; ?>
