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
<tr><td><?= e($p['razon_social']); ?></td><td><?= e($p['nombre_servicio']); ?></td><td><?= e($p['estado_pago']); ?></td><td><?= e((string)$p['monto_facturado']); ?></td><td><?= e((string)$p['monto_pagado']); ?></td><td><?= e($p['numero_factura']); ?></td></td><td><form method="POST" action="/pagos/eliminar" onsubmit="return confirm('¿Eliminar pago?')"><input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>"><input type="hidden" name="id" value="<?= $p['id']; ?>"><button class="btn btn-sm btn-danger">Eliminar</button></form></td></tr>
<?php endforeach; ?></table>
