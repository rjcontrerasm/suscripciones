<?php

declare(strict_types=1);

use App\Core\Database;

require __DIR__ . '/../app/Core/helpers.php';

spl_autoload_register(function ($class): void {
    if (!str_starts_with($class, 'App\\')) {
        return;
    }
    $file = __DIR__ . '/../app/' . str_replace(['App\\', '\\'], ['', '/'], $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

$app = require __DIR__ . '/../config/app.php';

$db = Database::connection();
foreach ($app['alerts_days'] as $days) {
    $stmt = $db->prepare(
        'SELECT s.id, s.nombre_servicio, s.fecha_vencimiento, c.correo, c.razon_social
         FROM servicios s
         INNER JOIN clientes c ON c.id = s.cliente_id
         WHERE s.deleted_at IS NULL
         AND s.fecha_vencimiento = DATE_ADD(CURDATE(), INTERVAL :days DAY)'
    );
    $stmt->bindValue('days', $days, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        $subject = "[ARPYNET] Renovación próxima: {$row['nombre_servicio']}";
        $message = "Cliente: {$row['razon_social']}\nServicio: {$row['nombre_servicio']}\nVence: {$row['fecha_vencimiento']}";
        if (!empty($row['correo'])) {
            @mail($row['correo'], $subject, $message);
        }

        $insert = $db->prepare('INSERT INTO alertas (servicio_id, dias_antes, medio, enviado_en, created_at, updated_at) VALUES (:sid, :dias, :medio, NOW(), NOW(), NOW())');
        $insert->execute(['sid' => $row['id'], 'dias' => $days, 'medio' => 'email']);

        echo "Alerta enviada para servicio #{$row['id']} ({$days} días)" . PHP_EOL;
    }
}
