<h2>Renovaciones</h2><a class="btn btn-outline-primary btn-sm mb-2" href="/renovaciones/exportar">Exportar CSV</a>
<div class="card p-3 mb-3"><form method="POST" action="/renovaciones/crear" class="row g-2">
<input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
<div class="col-md-4"><select name="servicio_id" class="form-select"><?php foreach($services as $s): ?><option value="<?= $s['id']; ?>"><?= e($s['razon_social'].' - '.$s['nombre_servicio'].' ('.$s['fecha_vencimiento'].')'); ?></option><?php endforeach; ?></select></div>
<div class="col-md-2"><input type="date" name="fecha_nueva" class="form-control" required></div>
<div class="col-md-3"><select name="pago_id" class="form-select"><option value="">Sin pago asociado</option><?php foreach($payments as $p): ?><option value="<?= $p['id']; ?>"><?= e($p['numero_factura'] ?: ('Pago #'.$p['id'])); ?></option><?php endforeach; ?></select></div>
<div class="col-md-2"><input name="notas" class="form-control" placeholder="Notas"></div>
<div class="col-md-1"><button class="btn btn-warning">Renovar</button></div>
</form></div>
<table class="table table-sm"><tr><th>Fecha</th><th>Cliente</th><th>Servicio</th><th>Anterior</th><th>Nueva</th><th>Acciones</th></tr>
<?php foreach($renewals as $r): ?>
<tr><td><?= e($r['created_at']); ?></td><td><?= e($r['razon_social']); ?></td><td><?= e($r['nombre_servicio']); ?></td><td><?= e($r['fecha_anterior']); ?></td><td><?= e($r['fecha_nueva']); ?></td></td><td><form method="POST" action="/renovaciones/eliminar" onsubmit="return confirm('¿Eliminar renovación?')"><input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>"><input type="hidden" name="id" value="<?= $r['id']; ?>"><button class="btn btn-sm btn-danger">Eliminar</button></form></td></tr>
<?php endforeach; ?></table>
