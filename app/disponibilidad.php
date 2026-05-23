<?php
// app/disponibilidad.php
require_once __DIR__.'/includes/db.php';

// Cargar ciudades para los selects
$ciudades = $mysqli->query("SELECT id, nombre, slug FROM ciudades ORDER BY nombre");
$lista_ciudades = [];
while ($c = $ciudades->fetch_assoc()) {
    $lista_ciudades[] = $c;
}

// Procesar consulta
$destino_slug = $_GET['destino'] ?? 'bogota';
$fecha = $_GET['fecha'] ?? date('Y-m-d');

// Obtener nombre del destino
$stmt = $mysqli->prepare("SELECT id, nombre FROM ciudades WHERE slug = ?");
$stmt->bind_param('s', $destino_slug);
$stmt->execute();
$ciudad = $stmt->get_result()->fetch_assoc();

$resultados = [];
if ($ciudad) {
    $stmt2 = $mysqli->prepare("
        SELECT e.nombre AS empresa, r.horario, tv.nombre AS vehiculo,
               r.duracion, tv.capacidad, r.precio
        FROM rutas r
        JOIN empresas e ON r.empresa_id = e.id
        JOIN tipos_vehiculo tv ON r.tipo_vehiculo_id = tv.id
        WHERE r.ciudad_destino_id = ?
        ORDER BY r.horario
    ");
    $stmt2->bind_param('i', $ciudad['id']);
    $stmt2->execute();
    $res = $stmt2->get_result();
    while ($row = $res->fetch_assoc()) {
        // Simular asientos disponibles basado en capacidad
        $cap_texto = $row['capacidad'];
        preg_match('/(\d+)/', $cap_texto, $matches);
        $cap_max = isset($matches[1]) ? (int)$matches[1] : 40;
        // Generar disponibilidad simulada determinista
        $seed = crc32($row['empresa'] . $row['horario'] . $fecha);
        $disponibles = abs($seed) % ($cap_max + 1);
        $row['cap_max'] = $cap_max;
        $row['disponibles'] = $disponibles;
        $resultados[] = $row;
    }
}

// Formatear fecha para mostrar
$fecha_mostrar = date('d \d\e F \d\e Y', strtotime($fecha));

$titulo_pagina = 'Disponibilidad - Terminal de Transportes';
$hero_titulo = 'Disponibilidad de Asientos';
$hero_subtitulo = 'Consulta la disponibilidad en tiempo real para tu viaje';

$estilos_extra = '
    .tabla-disponibilidad {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .tabla-disponibilidad th {
        background: linear-gradient(90deg, #58c1c9, #124f9e);
        color: white;
        padding: 15px 10px;
        text-align: left;
        font-weight: 600;
    }
    .tabla-disponibilidad td {
        padding: 12px 10px;
        border-bottom: 1px solid #eee;
    }
    .tabla-disponibilidad tr:hover {
        background: #f5f5f5;
    }
    .estado-disponible {
        background: #27ae60;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
    }
    .estado-pocos {
        background: #f39c12;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
    }
    .estado-agotado {
        background: #e74c3c;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
    }
    .contenedor-tabla {
        padding: 40px 60px;
        background: #f2f2f2;
    }
    .leyenda {
        display: flex;
        gap: 30px;
        margin-top: 20px;
        flex-wrap: wrap;
    }
    .leyenda-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    .filtros-disponibilidad {
        display: flex;
        gap: 20px;
        padding: 30px 60px;
        background: #f2f2f2;
        flex-wrap: wrap;
        align-items: flex-end;
    }
';
require_once __DIR__.'/includes/header.php';
?>

    <!-- FILTROS -->
    <form method="get" class="filtros-disponibilidad">
        <div class="campo">
            <label>Origen</label>
            <select disabled>
                <option value="medellin" selected>Medellín</option>
            </select>
        </div>

        <div class="campo">
            <label>Destino</label>
            <select name="destino">
                <option value="">Todos los destinos</option>
                <?php foreach ($lista_ciudades as $c): ?>
                <option value="<?= htmlspecialchars($c['slug']) ?>" <?= $c['slug'] === $destino_slug ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nombre']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="campo">
            <label>Fecha</label>
            <input type="date" name="fecha" value="<?= htmlspecialchars($fecha) ?>">
        </div>

        <button type="submit" class="btn-buscar">Consultar</button>
    </form>

    <!-- TABLA DE DISPONIBILIDAD -->
    <section class="contenedor-tabla">

        <?php if ($ciudad): ?>
        <h2 style="margin-bottom: 10px; color: #124f9e;">Disponibilidad para: Medellín &rarr; <?= htmlspecialchars($ciudad['nombre']) ?></h2>
        <p style="color: #666; margin-bottom: 20px;">Fecha: <?= htmlspecialchars($fecha_mostrar) ?></p>

        <table class="tabla-disponibilidad">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Horario</th>
                    <th>Vehículo</th>
                    <th>Duración</th>
                    <th>Asientos Disponibles</th>
                    <th>Precio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($resultados as $r):
                if ($r['disponibles'] === 0) {
                    $estado_class = 'estado-agotado';
                    $estado_texto = 'Agotado';
                } elseif ($r['disponibles'] <= 5) {
                    $estado_class = 'estado-pocos';
                    $estado_texto = 'Últimos asientos';
                } else {
                    $estado_class = 'estado-disponible';
                    $estado_texto = 'Disponible';
                }
            ?>
                <tr>
                    <td><strong><?= htmlspecialchars($r['empresa']) ?></strong></td>
                    <td><?= htmlspecialchars($r['horario']) ?></td>
                    <td><?= htmlspecialchars($r['vehiculo']) ?></td>
                    <td><?= htmlspecialchars($r['duracion']) ?></td>
                    <td><?= $r['disponibles'] ?> / <?= $r['cap_max'] ?></td>
                    <td>$<?= number_format($r['precio'], 0, ',', '.') ?></td>
                    <td><span class="<?= $estado_class ?>"><?= $estado_texto ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="leyenda">
            <div class="leyenda-item">
                <span class="estado-disponible">Disponible</span>
                <span>Más de 5 asientos</span>
            </div>
            <div class="leyenda-item">
                <span class="estado-pocos">Últimos asientos</span>
                <span>Menos de 5 asientos</span>
            </div>
            <div class="leyenda-item">
                <span class="estado-agotado">Agotado</span>
                <span>Sin disponibilidad</span>
            </div>
        </div>

        <p style="margin-top: 30px; color: #666; font-size: 14px;">
            <i class="fas fa-info-circle"></i>
            Para comprar tiquetes, dirígete a la sección de <a href="cotizacion.php" style="color: #124f9e;">Cotización</a>.
        </p>
        <?php else: ?>
        <p>Seleccione un destino para ver la disponibilidad.</p>
        <?php endif; ?>

    </section>

<?php require_once __DIR__.'/includes/footer.php'; ?>
