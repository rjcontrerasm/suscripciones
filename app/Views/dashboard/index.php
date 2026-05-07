<h2 class="mb-4">Dashboard</h2>
<div class="row g-3 mb-4">
  <div class="col-md-3"><div class="card metric"><p>Clientes activos</p><h3><?= $metrics['clientes_activos']; ?></h3></div></div>
  <div class="col-md-3"><div class="card metric"><p>Servicios activos</p><h3><?= $metrics['servicios_activos']; ?></h3></div></div>
  <div class="col-md-3"><div class="card metric bg-danger-subtle"><p>Servicios vencidos</p><h3><?= $metrics['servicios_vencidos']; ?></h3></div></div>
  <div class="col-md-3"><div class="card metric bg-warning-subtle"><p>Pagos pendientes</p><h3><?= $metrics['pendientes_pago']; ?></h3></div></div>
</div>
<div class="row g-3 mb-4">
  <div class="col-md-6"><div class="card p-3"><strong>Monto pendiente:</strong> <?= number_format($metrics['monto_pendiente'], 2); ?></div></div>
  <div class="col-md-6"><div class="card p-3"><strong>Monto cobrado mes:</strong> <?= number_format($metrics['monto_mes'], 2); ?></div></div>
</div>
<div class="card p-3 mb-4">
<h5>Servicios por vencer (7/15/30)</h5>
<p>7 días: <?= count($v7); ?> | 15 días: <?= count($v15); ?> | 30 días: <?= count($v30); ?></p>
</div>
<div class="card p-3">
<h5>Últimas renovaciones</h5>
<table class="table table-sm"><tr><th>Cliente</th><th>Servicio</th><th>Anterior</th><th>Nueva</th></tr>
<?php foreach ($renovaciones as $r): ?>
<tr><td><?= e($r['razon_social']); ?></td><td><?= e($r['nombre_servicio']); ?></td><td><?= e($r['fecha_anterior']); ?></td><td><?= e($r['fecha_nueva']); ?></td></tr>
<?php endforeach; ?>
</table>
</div>
