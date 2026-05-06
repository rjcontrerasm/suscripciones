<?php
// cPanel fallback: when DocumentRoot points to project root instead of /public,
// bootstrap the real front controller.
require __DIR__ . '/public/index.php';
