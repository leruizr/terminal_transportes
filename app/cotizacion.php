<?php
// app/cotizacion.php
require_once __DIR__.'/includes/db.php';

// Cargar ciudades para los selects
$ciudades = $mysqli->query("SELECT id, nombre, slug FROM ciudades ORDER BY nombre");
$lista_ciudades = [];
while ($c = $ciudades->fetch_assoc()) {
    $lista_ciudades[] = $c;
}

// Procesar cotización si se envió el form
$destino_slug = $_GET['destino'] ?? ($_POST['destino'] ?? '');
$origen_slug = $_GET['origen'] ?? ($_POST['origen'] ?? '');
$personas = (int)($_POST['personas'] ?? 0);
$fecha = $_POST['fecha'] ?? '';
$resultados = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $destino_slug && $personas > 0) {
    $stmt = $mysqli->prepare("
        SELECT r.id AS ruta_id, e.nombre AS empresa, tv.nombre AS vehiculo,
               r.horario, r.duracion, r.precio
        FROM rutas r
        JOIN empresas e ON r.empresa_id = e.id
        JOIN tipos_vehiculo tv ON r.tipo_vehiculo_id = tv.id
        JOIN ciudades c ON r.ciudad_destino_id = c.id
        WHERE c.slug = ?
        ORDER BY r.horario
    ");
    $stmt->bind_param('s', $destino_slug);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $row['total'] = $row['precio'] * $personas;
        $resultados[] = $row;
    }
}

// Procesar compra de tiquete
$mensaje_compra = '';
if (($_POST['accion'] ?? '') === 'comprar') {
    $ruta_id = (int)($_POST['ruta_id'] ?? 0);
    $nombre_p = trim($_POST['nombre_pasajero'] ?? '');
    $email_p = trim($_POST['email_pasajero'] ?? '');
    $fecha_v = $_POST['fecha_viaje'] ?? '';
    $personas_c = (int)($_POST['personas_compra'] ?? 1);
    $total_c = (int)($_POST['total_compra'] ?? 0);

    if ($ruta_id && $nombre_p && $email_p && $fecha_v && $personas_c > 0) {
        $stmt = $mysqli->prepare("INSERT INTO tiquetes (nombre_pasajero, email_pasajero, ruta_id, fecha_viaje, personas, total) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param('ssissi', $nombre_p, $email_p, $ruta_id, $fecha_v, $personas_c, $total_c);
        if ($stmt->execute()) {
            $mensaje_compra = 'COMPRA EXITOSA - Su tiquete ha sido reservado. Gracias por elegirnos.';
        } else {
            $mensaje_compra = 'Error al procesar la compra.';
        }
    }
}

$titulo_pagina = 'Cotización - Terminal';
$hero_titulo = 'Cotiza tu Viaje';
$hero_subtitulo = 'Calcula el valor estimado de tu trayecto';
require_once __DIR__.'/includes/header.php';
?>

    <section class="cotizacion">

        <?php if ($mensaje_compra): ?>
        <div style="background: #e8f5e9; color: #2e7d32; padding: 20px; border-radius: 10px; margin-bottom: 20px; font-weight: bold; font-size: 1.1em;">
            <?= htmlspecialchars($mensaje_compra) ?>
        </div>
        <?php endif; ?>

        <h2>Datos del viaje</h2>

        <form method="post" class="cotizacion-form">
            <div class="campo">
                <label>Origen</label>
                <select name="origen" id="origen">
                    <option value="">Seleccione origen</option>
                    <?php foreach ($lista_ciudades as $c): ?>
                    <option value="<?= htmlspecialchars($c['slug']) ?>" <?= $c['slug'] === $origen_slug ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nombre']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo">
                <label>Destino</label>
                <select name="destino" id="destino">
                    <option value="">Seleccione destino</option>
                    <?php foreach ($lista_ciudades as $c): ?>
                    <option value="<?= htmlspecialchars($c['slug']) ?>" <?= $c['slug'] === $destino_slug ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nombre']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo">
                <label>Fecha</label>
                <input type="date" name="fecha" id="fecha" value="<?= htmlspecialchars($fecha) ?>">
            </div>

            <div class="campo">
                <label>Número de personas</label>
                <input type="number" name="personas" id="personas" min="1" placeholder="Ej: 2" value="<?= $personas > 0 ? $personas : '' ?>">
            </div>

            <button type="submit" class="btn-calcular">Calcular</button>
        </form>

        <?php if (!empty($resultados)): ?>
        <div style="margin-top: 40px;">
            <h2 style="color:#124f9e; margin: 30px 0 20px 0;">Empresas y Horarios Disponibles</h2>

            <?php
            // Obtener nombre del destino
            $stmt_nombre = $mysqli->prepare("SELECT nombre FROM ciudades WHERE slug = ?");
            $stmt_nombre->bind_param('s', $destino_slug);
            $stmt_nombre->execute();
            $nombre_destino = $stmt_nombre->get_result()->fetch_assoc()['nombre'] ?? $destino_slug;

            foreach ($resultados as $r): ?>
            <div class="resultado-card" style="border-left: 8px solid #58c1c9; text-align: left; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; max-width: 100%;">
                <div style="flex: 1; min-width: 300px;">
                    <h3 style="color:#124f9e; margin-bottom: 10px;"><?= htmlspecialchars($r['empresa']) ?></h3>
                    <p><strong>Vehículo:</strong> <?= htmlspecialchars($r['vehiculo']) ?></p>
                    <p><strong>Salida:</strong> <span class="disponible"><?= htmlspecialchars($r['horario']) ?></span> | <strong>Duración:</strong> <?= htmlspecialchars($r['duracion']) ?></p>
                    <p><strong>Valor unitario:</strong> $<?= number_format($r['precio'], 0, ',', '.') ?></p>
                </div>
                <div style="text-align: right; min-width: 200px;">
                    <p style="font-size: 1.4em; font-weight: bold; color: #333; margin-bottom: 10px;">Total: $<?= number_format($r['total'], 0, ',', '.') ?></p>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="accion" value="comprar">
                        <input type="hidden" name="ruta_id" value="<?= (int)$r['ruta_id'] ?>">
                        <input type="hidden" name="fecha_viaje" value="<?= htmlspecialchars($fecha) ?>">
                        <input type="hidden" name="personas_compra" value="<?= $personas ?>">
                        <input type="hidden" name="total_compra" value="<?= (int)$r['total'] ?>">
                        <input type="hidden" name="nombre_pasajero" value="Pasajero Web">
                        <input type="hidden" name="email_pasajero" value="web@terminal.com">
                        <button type="submit" class="btn-buscar" onclick="return confirm('¿Desea comprar su tiquete con <?= htmlspecialchars($r['empresa']) ?> para viajar a <?= htmlspecialchars($nombre_destino) ?> por un total de $<?= number_format($r['total'], 0, ',', '.') ?>?')">
                            Seleccionar
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </section>

<?php require_once __DIR__.'/includes/footer.php'; ?>
