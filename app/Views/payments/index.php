<h2>Pagos y facturación</h2><a class="btn btn-outline-primary btn-sm mb-2" href="/pagos/exportar">Exportar CSV</a>
<div class="card p-3 mb-3">
<form method="POST" enctype="multipart/form-data" action="/pagos/crear" class="row g-2">
<input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
<div class="col-md-3"><select name="servicio_id" class="form-select"><?php foreach($services as $s): ?><option value="<?= $s['id']; ?>"><?= e($s['razon_social'].' - '.$s['nombre_servicio']); ?></option><?php endforeach; ?></select></div>
<div class="col-md-2"><select name="estado_pago" class="form-select"><?php foreach(['Pendiente','Pagado','Parcial','Vencido'] as $e): ?><option><?= $e; ?></option><?php endforeach; ?></select></div>
<div class="col-md-2"><input name="fecha_pago" type="date" class="form-control"></div>
<div class="col-md-2"><input name="monto_facturado" type="number" step="0.01" class="form-control" placeholder="Facturado"></div>
<div class="col-md-2"><input name="monto_pagado" type="number" step="0.01" class="form-control" placeholder="Pagado"></div>
<div class="col-md-2"><input name="numero_factura" class="form-control" placeholder="N° factura"></div>
<div class="col-md-3"><input name="link_factura" class="form-control" placeholder="Link factura"></div>
<div class="col-md-3"><input name="archivo_factura" type="file" class="form-control"></div>
<div class="col-md-3"><input name="observaciones" class="form-control" placeholder="Observaciones"></div>
<div class="col-md-2"><button class="btn btn-warning">Registrar</button></div>
</form>
</div>
<table class="table table-sm"><tr><th>Cliente</th><th>Servicio</th><th>Estado</th><th>Facturado</th><th>Pagado</th><th>Factura</th><th>Acciones</th></tr>
<?php foreach($payments as $p): ?>
<tr><td><?= e($p['razon_social']); ?></td><td><?= e($p['nombre_servicio']); ?></td><td><?= e($p['estado_pago']); ?></td><td><?= e((string)$p['monto_facturado']); ?></td><td><?= e((string)$p['monto_pagado']); ?></td><td><?= e($p['numero_factura']); ?></td><td>
<button type="button" class="btn btn-sm btn-outline-secondary" title="Ver detalle" onclick="toggleEdit('view-pago-<?= $p['id']; ?>')">👁</button>
<button type="button" class="btn btn-sm btn-primary" onclick="toggleEdit('edit-pago-<?= $p['id']; ?>')">Editar</button>
<form class="d-inline" method="POST" action="/pagos/eliminar" onsubmit="return confirm('¿Eliminar pago?')"><input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>"><input type="hidden" name="id" value="<?= $p['id']; ?>"><button class="btn btn-sm btn-outline-danger">Eliminar</button></form>
</td></tr>
<tr id="view-pago-<?= $p['id']; ?>" style="display:none;" class="table-light"><td colspan="7"><strong>Factura link:</strong> <?= e($p['link_factura']); ?> | <strong>Observaciones:</strong> <?= e($p['observaciones']); ?></td></tr>
<tr id="edit-pago-<?= $p['id']; ?>" style="display:none;"><td colspan="7"><form class="row g-2" method="POST" action="/pagos/actualizar"><input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>"><input type="hidden" name="id" value="<?= $p['id']; ?>"><input type="hidden" name="servicio_id" value="<?= $p['servicio_id']; ?>"><input type="hidden" name="archivo_factura_actual" value="<?= e($p['archivo_factura']); ?>"><div class="col-md-2"><select class="form-select form-select-sm" name="estado_pago"><?php foreach(['Pendiente','Pagado','Parcial','Vencido'] as $e): ?><option <?= $p['estado_pago']===$e?'selected':''; ?>><?= $e; ?></option><?php endforeach; ?></select></div><div class="col-md-2"><input class="form-control form-control-sm" type="date" name="fecha_pago" value="<?= e((string)$p['fecha_pago']); ?>"></div><div class="col-md-2"><input class="form-control form-control-sm" type="number" step="0.01" name="monto_facturado" value="<?= e((string)$p['monto_facturado']); ?>"></div><div class="col-md-2"><input class="form-control form-control-sm" type="number" step="0.01" name="monto_pagado" value="<?= e((string)$p['monto_pagado']); ?>"></div><div class="col-md-2"><input class="form-control form-control-sm" name="numero_factura" value="<?= e($p['numero_factura']); ?>"></div><div class="col-md-2"><button class="btn btn-sm btn-primary">Guardar</button></div><input type="hidden" name="link_factura" value="<?= e($p['link_factura']); ?>"><input type="hidden" name="observaciones" value="<?= e($p['observaciones']); ?>"></form></td></tr>
<?php endforeach; ?></table>
<script>function toggleEdit(id){const el=document.getElementById(id); if(!el) return; el.style.display=(el.style.display==='none'||el.style.display==='')?'table-row':'none';}</script>
