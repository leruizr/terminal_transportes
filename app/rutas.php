<?php
// app/rutas.php
require_once __DIR__.'/includes/db.php';

// Agrupaciones de rutas por región (igual que el HTML original)
$regiones = [
    ['nombre' => 'Eje Cafetero', 'slugs' => ['armenia','pereira','manizales','salento']],
    ['nombre' => 'Costa Caribe', 'slugs' => ['cartagena','barranquilla','santamarta']],
    ['nombre' => 'Centro del País', 'slugs' => ['bogota','ibague','popayan']],
    ['nombre' => 'Oriente Colombiano', 'slugs' => ['bucaramanga','cucuta']],
    ['nombre' => 'Occidente', 'slugs' => ['cali','quibdo']],
];

$titulo_pagina = 'Rutas - Terminal';
$hero_titulo = 'Rutas y Destinos';
$hero_subtitulo = 'Descubre los destinos disponibles desde Medellín';
require_once __DIR__.'/includes/header.php';
?>

    <section class="contenido-rutas">

    <?php foreach ($regiones as $region):
        // Obtener nombres de ciudades para esta región
        $placeholders = implode(',', array_fill(0, count($region['slugs']), '?'));
        $stmt = $mysqli->prepare("SELECT nombre FROM ciudades WHERE slug IN ($placeholders) ORDER BY nombre");
        $types = str_repeat('s', count($region['slugs']));
        $stmt->bind_param($types, ...$region['slugs']);
        $stmt->execute();
        $res = $stmt->get_result();
        $nombres = [];
        while ($r = $res->fetch_assoc()) {
            $nombres[] = $r['nombre'];
        }
    ?>
        <div class="ruta-card">
            <h3><?= htmlspecialchars($region['nombre']) ?></h3>
            <p><strong>Destinos:</strong> <?= htmlspecialchars(implode(', ', $nombres)) ?></p>
        </div>
    <?php endforeach; ?>

    </section>

<?php require_once __DIR__.'/includes/footer.php'; ?>
