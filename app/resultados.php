<?php
// app/resultados.php
require_once __DIR__.'/includes/db.php';

$origen_slug = $_GET['origen'] ?? '';
$destino_slug = $_GET['destino'] ?? '';
$fecha = $_GET['fecha'] ?? '';

$opciones = [];
$nombre_destino = '';
$nombre_origen = '';

if ($destino_slug) {
    // Nombre del destino
    $stmt = $mysqli->prepare("SELECT nombre FROM ciudades WHERE slug = ?");
    $stmt->bind_param('s', $destino_slug);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $nombre_destino = $res['nombre'] ?? ucfirst($destino_slug);

    // Nombre del origen
    if ($origen_slug) {
        $stmt2 = $mysqli->prepare("SELECT nombre FROM ciudades WHERE slug = ?");
        $stmt2->bind_param('s', $origen_slug);
        $stmt2->execute();
        $res2 = $stmt2->get_result()->fetch_assoc();
        $nombre_origen = $res2['nombre'] ?? ucfirst($origen_slug);
    }

    // Obtener rutas para este destino
    $stmt3 = $mysqli->prepare("
        SELECT r.precio
        FROM rutas r
        JOIN ciudades c ON r.ciudad_destino_id = c.id
        WHERE c.slug = ?
    ");
    $stmt3->bind_param('s', $destino_slug);
    $stmt3->execute();
    $res3 = $stmt3->get_result();
    while ($row = $res3->fetch_assoc()) {
        $opciones[] = $row;
    }
}

$titulo_pagina = 'Resultados - Terminal';
$hero_titulo = 'Resultados de tu búsqueda';
$hero_subtitulo = 'Estas son las opciones de viaje disponibles';
require_once __DIR__.'/includes/header.php';
?>

    <section class="cotizacion">
        <h2>Viajes encontrados</h2>

        <?php if (!empty($opciones)): ?>
        <div class="resultado-card">
            <h3>Viaje a <?= htmlspecialchars($nombre_destino) ?></h3>
            <?php if ($nombre_origen): ?>
            <p><strong>Desde:</strong> <?= htmlspecialchars($nombre_origen) ?></p>
            <?php endif; ?>
            <p>Hay <?= count($opciones) ?> opciones de horarios disponibles para esta ruta.</p>
            <p>Precios desde: $<?= number_format(min(array_column($opciones, 'precio')), 0, ',', '.') ?></p>
            <br>
            <a href="cotizacion.php?origen=<?= htmlspecialchars($origen_slug) ?>&destino=<?= htmlspecialchars($destino_slug) ?>">
                <button class="btn-buscar">Ver horarios y Cotizar</button>
            </a>
        </div>
        <?php else: ?>
        <p>No hay rutas disponibles para este destino.</p>
        <?php endif; ?>

        <p style="margin-top: 30px;"><a href="index.php" class="btn-buscar" style="text-decoration: none;">Volver al inicio</a></p>
    </section>

<?php require_once __DIR__.'/includes/footer.php'; ?>
