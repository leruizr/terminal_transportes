<?php
// app/empresas.php
require_once __DIR__.'/includes/db.php';

// Cargar empresas con sus destinos desde las rutas
$empresas = $mysqli->query("SELECT id, nombre, vehiculos_texto, capacidad FROM empresas ORDER BY nombre");

$titulo_pagina = 'Empresas - Terminal';
$hero_titulo = 'Empresas Vinculadas';
$hero_subtitulo = 'Conoce las compañías que operan en nuestra terminal';
require_once __DIR__.'/includes/header.php';
?>

    <section class="contenido-empresas">

    <?php while ($e = $empresas->fetch_assoc()):
        // Obtener destinos de esta empresa
        $stmt = $mysqli->prepare("
            SELECT DISTINCT c.nombre
            FROM rutas r
            JOIN ciudades c ON r.ciudad_destino_id = c.id
            WHERE r.empresa_id = ?
            ORDER BY c.nombre
        ");
        $stmt->bind_param('i', $e['id']);
        $stmt->execute();
        $destinos = $stmt->get_result();
    ?>
        <div class="empresa-card">
            <h3><?= htmlspecialchars($e['nombre']) ?></h3>
            <p><strong>Vehículos:</strong> <?= htmlspecialchars($e['vehiculos_texto']) ?></p>
            <p><strong>Capacidad:</strong> <?= htmlspecialchars($e['capacidad']) ?></p>
            <p><strong>Destinos:</strong></p>
            <ul class="lista-destinos">
                <?php while ($d = $destinos->fetch_assoc()): ?>
                <li><?= htmlspecialchars($d['nombre']) ?></li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php endwhile; ?>

    </section>

<?php require_once __DIR__.'/includes/footer.php'; ?>
