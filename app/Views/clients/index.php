<h2>Clientes</h2><a class="btn btn-outline-primary btn-sm mb-2" href="/clientes/exportar">Exportar CSV</a>
<form class="row g-2 mb-3" method="GET">
  <div class="col-md-4"><input class="form-control" name="q" value="<?= e($search); ?>" placeholder="Buscar por RUC, razón social o correo"></div>
  <div class="col-md-2"><button class="btn btn-primary">Buscar</button></div>
</form>
<div class="card p-3 mb-3">
  <h5>Nuevo cliente</h5>
  <form method="POST" action="/clientes/crear" class="row g-2">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
    <div class="col-md-2"><select name="tipo_cliente" class="form-select"><option>Persona Natural</option><option>Persona Jurídica</option></select></div>
    <div class="col-md-2"><input name="ruc" class="form-control" placeholder="RUC" required></div>
    <div class="col-md-3"><input name="razon_social" class="form-control" placeholder="Razón social" required></div>
    <div class="col-md-2"><input name="correo" class="form-control" type="email" placeholder="Correo"></div>
    <div class="col-md-2"><input name="telefono" class="form-control" placeholder="Teléfono"></div>
    <div class="col-md-3"><input name="direccion" class="form-control" placeholder="Dirección"></div>
    <div class="col-md-2"><input name="contacto" class="form-control" placeholder="Contacto"></div>
    <div class="col-md-2"><select name="estado" class="form-select"><option value="activo">activo</option><option value="inactivo">inactivo</option></select></div>
    <div class="col-md-2"><button class="btn btn-warning">Guardar</button></div>
  </form>
</div>
<table class="table table-striped table-sm">
<tr><th>RUC</th><th>Razón social</th><th>Tipo</th><th>Correo</th><th>Estado</th><th>Acciones</th></tr>
<?php foreach ($clients as $c): ?>
<tr><td><?= e($c['ruc']); ?></td><td><?= e($c['razon_social']); ?></td><td><?= e($c['tipo_cliente']); ?></td><td><?= e($c['correo']); ?></td><td><?= e($c['estado']); ?></td><td>
  <form method="POST" action="/clientes/eliminar" onsubmit="return confirm('¿Eliminar cliente?')" class="d-inline">
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>"><input type="hidden" name="id" value="<?= $c['id']; ?>"><button class="btn btn-sm btn-danger">Eliminar</button>
  </form>
</td></tr>
<tr><td colspan="6"><form class="row g-2" method="POST" action="/clientes/actualizar">
  <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>"><input type="hidden" name="id" value="<?= $c['id']; ?>">
  <div class="col-md-2"><input class="form-control form-control-sm" name="ruc" value="<?= e($c['ruc']); ?>"></div>
  <div class="col-md-3"><input class="form-control form-control-sm" name="razon_social" value="<?= e($c['razon_social']); ?>"></div>
  <div class="col-md-2"><input class="form-control form-control-sm" name="correo" value="<?= e($c['correo']); ?>"></div>
  <div class="col-md-2"><input class="form-control form-control-sm" name="telefono" value="<?= e($c['telefono']); ?>"></div>
  <input type="hidden" name="tipo_cliente" value="<?= e($c['tipo_cliente']); ?>"><input type="hidden" name="direccion" value="<?= e($c['direccion']); ?>"><input type="hidden" name="contacto" value="<?= e($c['contacto']); ?>"><input type="hidden" name="estado" value="<?= e($c['estado']); ?>">
  <div class="col-md-2"><button class="btn btn-sm btn-outline-primary">Editar</button></div>
</form></td></tr>
<?php endforeach; ?>
</table>
