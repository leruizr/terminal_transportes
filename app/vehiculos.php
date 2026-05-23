<?php
// app/vehiculos.php
require_once __DIR__.'/includes/db.php';

$tipos = $mysqli->query("SELECT id, nombre, capacidad, comodidades, ideal_para FROM tipos_vehiculo ORDER BY id");

// Iconos para cada tipo de vehículo
$iconos = [
    'Bus de Lujo' => 'fas fa-bus',
    'Bus Premium' => 'fas fa-bus-alt',
    'Aerovan' => 'fas fa-shuttle-van',
    'Buseta' => 'fas fa-bus',
    'Gacela' => 'fas fa-van-shuttle',
    'Taxi Intermunicipal' => 'fas fa-taxi',
    'Camión de Carga' => 'fas fa-truck',
];

$titulo_pagina = 'Vehículos - Terminal de Transportes';
$hero_titulo = 'Flota de Vehículos';
$hero_subtitulo = 'Conoce los tipos de vehículos disponibles para tu viaje';
require_once __DIR__.'/includes/header.php';
?>

    <section class="contenido-empresas">

    <?php while ($t = $tipos->fetch_assoc()):
        $icono = $iconos[$t['nombre']] ?? 'fas fa-bus';
        $comodidades = explode(', ', $t['comodidades']);
        $label_comodidades = ($t['nombre'] === 'Camión de Carga') ? 'Servicios' : 'Comodidades';
    ?>
        <div class="empresa-card">
            <h3><i class="<?= $icono ?>" style="color: #124f9e; margin-right: 10px;"></i><?= htmlspecialchars($t['nombre']) ?></h3>
            <p><strong>Capacidad:</strong> <?= htmlspecialchars($t['capacidad']) ?></p>
            <p><strong><?= $label_comodidades ?>:</strong></p>
            <ul class="lista-destinos">
                <?php foreach ($comodidades as $com): ?>
                <li><?= htmlspecialchars(trim($com)) ?></li>
                <?php endforeach; ?>
            </ul>
            <p style="margin-top: 10px;"><strong>Ideal para:</strong> <?= htmlspecialchars($t['ideal_para']) ?></p>
        </div>
    <?php endwhile; ?>

    </section>

<?php require_once __DIR__.'/includes/footer.php'; ?>
