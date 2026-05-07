<h2>Renovaciones</h2><a class="btn btn-outline-primary btn-sm mb-2" href="/renovaciones/exportar">Exportar CSV</a><?php if (!empty($serviceId)): ?><a class="btn btn-outline-secondary btn-sm mb-2 ms-2" href="/renovaciones">Quitar filtro</a><div class="alert alert-info py-2">Mostrando historial filtrado por servicio #<?= (int)$serviceId; ?>.</div><?php endif; ?>
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
<tr><td><?= e($r['created_at']); ?></td><td><?= e($r['razon_social']); ?></td><td><?= e($r['nombre_servicio']); ?></td><td><?= e($r['fecha_anterior']); ?></td><td><?= e($r['fecha_nueva']); ?></td><td>
<button type="button" class="btn btn-sm btn-secondary" onclick="toggleEdit('view-renovacion-<?= $r['id']; ?>')">👁</button>
<button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleEdit('edit-renovacion-<?= $r['id']; ?>')">Editar</button>
<form class="d-inline" method="POST" action="/renovaciones/eliminar" onsubmit="return confirm('¿Eliminar renovación?')"><input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>"><input type="hidden" name="id" value="<?= $r['id']; ?>"><button class="btn btn-sm btn-danger">Eliminar</button></form>
</td></tr>
<tr id="view-renovacion-<?= $r['id']; ?>" style="display:none;" class="table-light"><td colspan="6"><strong>Servicio ID:</strong> <?= e((string)$r['servicio_id']); ?> | <strong>Notas:</strong> <?= e($r['notas'] ?? ''); ?></td></tr>
<tr id="edit-renovacion-<?= $r['id']; ?>" style="display:none;"><td colspan="6"><form class="row g-2" method="POST" action="/renovaciones/actualizar"><input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>"><input type="hidden" name="id" value="<?= $r['id']; ?>"><div class="col-md-3"><input class="form-control form-control-sm" type="date" name="fecha_nueva" value="<?= e($r['fecha_nueva']); ?>"></div><div class="col-md-3"><select name="pago_id" class="form-select form-select-sm"><option value="">Sin pago asociado</option><?php foreach($payments as $p): ?><option value="<?= $p['id']; ?>" <?= (string)$r['pago_id']===(string)$p['id']?'selected':''; ?>><?= e($p['numero_factura'] ?: ('Pago #'.$p['id'])); ?></option><?php endforeach; ?></select></div><div class="col-md-4"><input class="form-control form-control-sm" name="notas" value="<?= e($r['notas'] ?? ''); ?>"></div><div class="col-md-2"><button class="btn btn-sm btn-outline-primary">Guardar</button></div></form></td></tr>
<?php endforeach; ?></table>
<script>function toggleEdit(id){const el=document.getElementById(id); if(!el) return; el.style.display=(el.style.display==='none'||el.style.display==='')?'table-row':'none';}</script>
