<?php require __DIR__ . '/../partials/login_header.php'; ?>
<div class="login-wrapper">
  <form method="POST" action="/login" class="card p-4 shadow-sm">
    <h1 class="h4 text-center mb-3">ARPYNET Renovaciones</h1>
    <?php if (!empty($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= e($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
    <div class="mb-3"><label>Email</label><input name="email" type="email" class="form-control" required></div>
    <div class="mb-3"><label>Contraseña</label><input name="password" type="password" class="form-control" required></div>
    <button class="btn btn-primary w-100">Ingresar</button>
    <small class="d-block mt-3 text-muted">Usuario inicial: admin@arpynet.com / Admin@123</small>
  </form>
</div>
<?php require __DIR__ . '/../partials/login_footer.php'; ?>
