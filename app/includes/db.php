<?php
// app/includes/db.php
// Conexión MySQL (AppServ). Las credenciales se leen desde .env
// para no exponerlas en el código fuente ni en el repositorio.

require_once __DIR__ . '/env.php';

$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$DB_NAME = getenv('DB_NAME') ?: 'terminal_transportes';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
  die('Error de conexión MySQL: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
