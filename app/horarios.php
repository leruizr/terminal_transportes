<?php
// app/horarios.php
require_once __DIR__.'/includes/db.php';

// Obtener destinos que tienen rutas, con sus empresas y horarios
$sql = "
    SELECT c.nombre AS ciudad,
           GROUP_CONCAT(DISTINCT e.nombre ORDER BY e.nombre SEPARATOR ', ') AS empresas,
           GROUP_CONCAT(DISTINCT r.horario ORDER BY r.horario SEPARATOR ' - ') AS salidas
    FROM rutas r
    JOIN ciudades c ON r.ciudad_destino_id = c.id
    JOIN empresas e ON r.empresa_id = e.id
    GROUP BY c.id, c.nombre
    ORDER BY c.nombre
";
$horarios = $mysqli->query($sql);

$titulo_pagina = 'Horarios - Terminal';
$hero_titulo = 'Horarios Disponibles';
$hero_subtitulo = 'Consulta las principales salidas programadas desde Medellín';
require_once __DIR__.'/includes/header.php';
?>

    <section class="contenido-horarios">

    <?php while ($h = $horarios->fetch_assoc()): ?>
        <div class="horario-card">
            <h3><?= htmlspecialchars($h['ciudad']) ?></h3>
            <p><strong>Empresas:</strong> <?= htmlspecialchars($h['empresas']) ?></p>
            <p><strong>Salidas:</strong> <?= htmlspecialchars($h['salidas']) ?></p>
        </div>
    <?php endwhile; ?>

    </section>

<?php require_once __DIR__.'/includes/footer.php'; ?>
