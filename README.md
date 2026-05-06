# Plataforma de Renovaciones ARPYNET (PHP + MySQL)

Sistema web para gestionar clientes, servicios recurrentes, vencimientos, pagos, renovaciones y reportes en un hosting tradicional (cPanel/Apache/PHP 8+).

## 1) Estructura

- `public/`: entrada web y assets.
- `app/`: núcleo MVC simple (Core, Controllers, Models, Views).
- `database/`: `schema.sql` y `seed.sql`.
- `scripts/send_alerts.php`: envío de alertas por Cron.
- `public/uploads/`: comprobantes/facturas.

## 2) Instalación rápida

1. Crear base de datos en MySQL.
2. Importar:
   - `database/schema.sql`
   - `database/seed.sql`
3. Configurar credenciales en `config/database.php`.
4. (Opcional) Ajustar URL y SMTP en `config/app.php`.
5. Apuntar el DocumentRoot a `public/`.

## 3) Usuario administrador inicial

- Email: `admin@arpynet.com`
- Password: `Admin@123`

> Cambia la contraseña en el primer ingreso.

## 4) Seguridad implementada

- Autenticación con `password_hash`/`password_verify`.
- Sesiones con cookie `httponly`.
- Protección CSRF por token.
- Consultas preparadas con PDO.
- Control de acceso por roles: Administrador, Operador, Solo lectura.

## 5) Módulos incluidos

- Dashboard: KPIs, servicios por vencer y últimas renovaciones.
- Clientes: búsqueda y alta rápida.
- Servicios: filtros + cálculo de días restantes con semáforo visual.
- Pagos/facturación: estados, montos, archivo adjunto.
- Renovaciones: trazabilidad de fecha anterior/nueva y vínculo a pago.
- Reportes CSV: por vencer, vencidos, pagos pendientes, renovaciones.

## 6) SMTP y alertas

### SMTP

El proyecto deja lista la sección SMTP en `config/app.php`. Puedes:
- Usar `mail()` de PHP (ya implementado en `scripts/send_alerts.php`), o
- Sustituir por PHPMailer en el mismo script para autenticación SMTP robusta.

Variables recomendadas:
- `SMTP_HOST`
- `SMTP_PORT`
- `SMTP_USER`
- `SMTP_PASS`
- `SMTP_FROM`

### Cron Job (cPanel)

Ejecutar diariamente:

```bash
php /home/USUARIO/public_html/scripts/send_alerts.php
```

Frecuencia sugerida:
- Una vez al día a las 08:00.

## 7) Compatibilidad

- PHP 8+
- MySQL 5.7+ / MariaDB
- Apache + cPanel

## 8) Notas de escalabilidad

- La arquitectura separa Controladores/Modelos/Vistas.
- Se soporta eliminación lógica (`deleted_at`) en entidades clave.
- Índices incluidos para búsquedas de vencimiento, estado y RUC.
