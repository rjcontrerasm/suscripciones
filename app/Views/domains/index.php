<h2>Dominios</h2>
<a class="btn btn-outline-primary btn-sm mb-2" href="/dominios/exportar">Exportar CSV</a>
<form class="row g-2 mb-3" method="GET">
  <div class="col-md-4"><input class="form-control" name="q" value="<?= e($q); ?>" placeholder="Buscar dominio o cliente"></div>
  <div class="col-md-2"><button class="btn btn-primary">Buscar</button></div>
</form>

<div class="card shadow-sm p-3 mb-3">
  <h5>Nuevo dominio (anual)</h5>
  <form method="POST" action="/dominios/crear" class="row g-2">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
    <div class="col-md-3"><input name="dominio" class="form-control" placeholder="ejemplo.com" required></div>
    <div class="col-md-3"><select name="cliente_id" class="form-select" required><option value="">Cliente vinculado</option><?php foreach($clientes as $c): ?><option value="<?= $c['id']; ?>"><?= e($c['razon_social']); ?></option><?php endforeach; ?></select></div>
    <div class="col-md-3"><select name="servicio_id" class="form-select"><option value="">(Opcional) Servicio vinculado</option><?php foreach($servicios as $s): ?><option value="<?= $s['id']; ?>"><?= e($s['nombre_servicio']); ?></option><?php endforeach; ?></select></div>
    <div class="col-md-2"><input type="date" name="fecha_inicio" class="form-control" required></div>
    <div class="col-md-2"><input type="date" name="fecha_vencimiento" class="form-control" required></div>
    <div class="col-md-2"><input type="number" step="0.01" name="monto" class="form-control" placeholder="Monto"></div>
    <div class="col-md-2"><select name="moneda" class="form-select"><option value="USD">USD</option><option value="PEN">PEN</option></select></div>
    <div class="col-md-2"><input name="proveedor" class="form-control" placeholder="Proveedor"></div>
    <div class="col-md-2"><select name="estado" class="form-select"><option>Activo</option><option>Próximo a vencer</option><option>Vencido</option></select></div>
    <div class="col-md-4"><input name="notas" class="form-control" placeholder="Notas"></div>
    <div class="col-md-2"><button class="btn btn-warning">Guardar dominio</button></div>
  </form>
</div>

<table class="table table-sm">
<tr><th>Dominio</th><th>Cliente</th><th>Servicio</th><th>Vence</th><th>Días</th><th>Estado</th><th>Acciones</th></tr>
<?php foreach($domains as $d): $cls=$d['dias_restantes']<0?'danger':($d['dias_restantes']<=30?'warning':'success'); ?>
<tr class="table-<?= $cls; ?>"><td><?= e($d['dominio']); ?></td><td><?= e($d['razon_social'] ?? ''); ?></td><td><?= e($d['nombre_servicio'] ?? ''); ?></td><td><?= e($d['fecha_vencimiento']); ?></td><td><?= e((string)$d['dias_restantes']); ?></td><td><?= e($d['estado']); ?></td><td><form method="POST" action="/dominios/eliminar" onsubmit="return confirm('¿Eliminar dominio?')"><input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>"><input type="hidden" name="id" value="<?= $d['id']; ?>"><button class="btn btn-sm btn-outline-danger">Eliminar</button></form></td></tr>
<?php endforeach; ?>
</table>
