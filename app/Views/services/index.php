<h2>Servicios</h2><a class="btn btn-outline-primary btn-sm mb-2" href="/servicios/exportar">Exportar CSV</a>
<form class="row g-2 mb-3" method="GET">
  <div class="col-md-4"><input class="form-control" name="q" value="<?= e($filters['q']); ?>" placeholder="Buscar cliente, RUC, servicio, proveedor"></div>
  <div class="col-md-3"><select name="estado" class="form-select"><option value="">Todos</option><?php foreach(['Activo','Próximo a vencer','Vencido','Suspendido','Cancelado'] as $e): ?><option <?= $filters['estado']===$e?'selected':''; ?>><?= $e; ?></option><?php endforeach; ?></select></div>
  <div class="col-md-2"><button class="btn btn-primary">Filtrar</button></div>
</form>
<div class="card shadow-sm p-3 mb-3"><h5>Nuevo servicio</h5>
<form method="POST" action="/servicios/crear" class="row g-2">
<input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
<div class="col-md-3"><select class="form-select" name="cliente_id"><?php foreach($clientes as $c): ?><option value="<?= $c['id']; ?>"><?= e($c['razon_social']); ?></option><?php endforeach; ?></select></div>
<div class="col-md-2"><select class="form-select" name="tipo_servicio_id"><?php foreach($tipos as $t): ?><option value="<?= $t['id']; ?>"><?= e($t['nombre']); ?></option><?php endforeach; ?></select></div>
<div class="col-md-3"><input class="form-control" name="nombre_servicio" placeholder="Nombre servicio" required></div>
<div class="col-md-2"><input class="form-control" name="proveedor" placeholder="Proveedor"></div>
<div class="col-md-2"><input class="form-control" type="date" name="fecha_inicio" required></div>
<div class="col-md-2"><input class="form-control" type="date" name="fecha_vencimiento" required></div>
<div class="col-md-2"><select class="form-select" name="periodo"><?php foreach(['Mensual','Trimestral','Semestral','Anual','Personalizado'] as $p): ?><option><?= $p; ?></option><?php endforeach; ?></select></div>
<div class="col-md-2"><input class="form-control" name="monto" type="number" step="0.01" required></div>
<div class="col-md-2"><select class="form-select" name="moneda"><option value="USD">Dólares (USD)</option><option value="PEN">Soles (PEN)</option></select></div><div class="col-md-2"><input class="form-control" name="orden_servicio" type="number" min="1" placeholder="Orden servicio"></div>
<div class="col-md-2"><select class="form-select" name="estado"><?php foreach(['Activo','Próximo a vencer','Vencido','Suspendido','Cancelado'] as $e): ?><option><?= $e; ?></option><?php endforeach; ?></select></div>
<div class="col-md-2"><input class="form-control" name="responsable" placeholder="Responsable"></div>
<div class="col-md-4"><input class="form-control" name="notas" placeholder="Notas"></div>
<div class="col-md-2"><button class="btn btn-warning">Guardar</button></div>
</form></div>
<table class="table table-sm">
<tr><th>Código</th><th>Orden</th><th>Cliente</th><th>Servicio</th><th>Proveedor</th><th>Vence</th><th>Días</th><th>Estado</th><th>Acciones</th></tr>
<?php foreach($services as $s): $cls=$s['dias_restantes']<0?'danger':($s['dias_restantes']<=15?'warning':'success'); ?>
<tr class="table-<?= $cls; ?>"><td><?= e($s['codigo_servicio'] ?? ""); ?></td><td><?= e((string)($s['orden_servicio'] ?? "")); ?></td><td><?= e($s['razon_social']); ?></td><td><?= e($s['nombre_servicio']); ?></td><td><?= e($s['proveedor']); ?></td><td><?= e($s['fecha_vencimiento']); ?></td><td><?= e((string)$s['dias_restantes']); ?></td><td><?= e($s['estado']); ?></td><td><a class="btn btn-sm btn-info mb-1" href="/renovaciones?servicio_id=<?= $s['id']; ?>">Ver historial</a><button type="button" class="btn btn-sm btn-secondary mb-1" onclick="toggleEdit('view-servicio-<?= $s['id']; ?>')">👁</button><button type="button" class="btn btn-sm btn-outline-primary mb-1" onclick="toggleEdit('edit-servicio-<?= $s['id']; ?>')">Editar</button><form method="POST" action="/servicios/eliminar" onsubmit="return confirm('¿Eliminar servicio?')"><input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>"><input type="hidden" name="id" value="<?= $s['id']; ?>"><button class="btn btn-sm btn-outline-danger">Eliminar</button></form></td></tr>
<tr id="view-servicio-<?= $s['id']; ?>" style="display:none;" class="table-light"><td colspan="9"><strong>Código:</strong> <?= e($s['codigo_servicio'] ?? ""); ?> | <strong>Orden:</strong> <?= e((string)($s['orden_servicio'] ?? "")); ?> | <strong>Responsable:</strong> <?= e($s['responsable']); ?> | <strong>Notas:</strong> <?= e($s['notas']); ?></td></tr>
<tr id="edit-servicio-<?= $s['id']; ?>" style="display:none;"><td colspan="9"><form class="row g-2" method="POST" action="/servicios/actualizar"><input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>"><input type="hidden" name="id" value="<?= $s['id']; ?>"><input type="hidden" name="cliente_id" value="<?= $s['cliente_id']; ?>"><input type="hidden" name="tipo_servicio_id" value="<?= $s['tipo_servicio_id']; ?>"><input type="hidden" name="fecha_inicio" value="<?= e($s['fecha_inicio']); ?>"><input type="hidden" name="periodo" value="<?= e($s['periodo']); ?>"><input type="hidden" name="moneda" value="<?= e($s['moneda']); ?>"><input type="hidden" name="responsable" value="<?= e($s['responsable']); ?>"><input type="hidden" name="notas" value="<?= e($s['notas']); ?>"><div class="col-md-3"><input class="form-control form-control-sm" name="nombre_servicio" value="<?= e($s['nombre_servicio']); ?>"></div><div class="col-md-2"><input class="form-control form-control-sm" name="proveedor" value="<?= e($s['proveedor']); ?>"></div><div class="col-md-2"><input class="form-control form-control-sm" type="date" name="fecha_vencimiento" value="<?= e($s['fecha_vencimiento']); ?>"></div><div class="col-md-2"><input class="form-control form-control-sm" type="number" step="0.01" name="monto" value="<?= e((string)$s['monto']); ?>"></div><div class="col-md-2"><select class="form-select form-select-sm" name="estado"><?php foreach(['Activo','Próximo a vencer','Vencido','Suspendido','Cancelado'] as $e): ?><option <?= $s['estado']===$e?'selected':''; ?>><?= $e; ?></option><?php endforeach; ?></select></div><div class="col-md-1"><button class="btn btn-sm btn-primary">Editar</button></div></form></td></tr>
<?php endforeach; ?>
</table>

<script>function toggleEdit(id){const el=document.getElementById(id); if(!el) return; el.style.display=(el.style.display==='none'||el.style.display==='')?'table-row':'none';}</script>
