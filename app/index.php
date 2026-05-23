<?php
// app/index.php
require_once __DIR__.'/includes/db.php';

// Cargar ciudades para los selects
$ciudades = $mysqli->query("SELECT id, nombre, slug FROM ciudades ORDER BY nombre");
$lista_ciudades = [];
while ($c = $ciudades->fetch_assoc()) {
    $lista_ciudades[] = $c;
}

// Cargar empresas para la sección info
$empresas = $mysqli->query("SELECT nombre FROM empresas ORDER BY nombre");

// Cargar ciudades agrupadas para rutas/destinos info
$destinos = $mysqli->query("SELECT nombre FROM ciudades ORDER BY nombre");

$titulo_pagina = 'Terminal de Transportes | Compra de Tiquetes y Horarios de Buses';
$meta_description = 'Consulta horarios de buses, rutas disponibles y compra tiquetes online en la Terminal de Transportes de Colombia.';
$meta_keywords = 'terminal de transportes, tiquetes online, horarios de buses, rutas nacionales, viajes en bus Colombia';
$og_title = 'Terminal de Transportes';
$og_description = 'Compra tiquetes online y consulta rutas disponibles.';
$hero_titulo = 'Compra tus tiquetes y consulta horarios de buses';
$hero_subtitulo = 'Viaja por Colombia con las mejores rutas y empresas transportadoras.';
require_once __DIR__.'/includes/header.php';
?>

    <section class="buscador">

        <div class="campo">
            <label>Origen</label>
            <select id="origen">
                <option value="">Seleccione ciudad</option>
                <?php foreach ($lista_ciudades as $c): ?>
                <option value="<?= htmlspecialchars($c['slug']) ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="campo">
            <label>Destino</label>
            <select id="destino">
                <option value="">Seleccione ciudad</option>
                <?php foreach ($lista_ciudades as $c): ?>
                <option value="<?= htmlspecialchars($c['slug']) ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="campo">
            <label>Fecha</label>
            <input type="date" id="fecha">
        </div>

        <button class="btn-buscar" onclick="buscarRutas()">Buscar</button>

        <section id="resultadosBusqueda" style="display:none; padding:50px 20px;">
            <h2>Resultados disponibles</h2>
            <div id="contenidoResultados"></div>
        </section>

    </section>

    <section class="info">

        <div class="columna card-info">
            <h2>Empresas vinculadas</h2>
            <ul>
                <?php while ($e = $empresas->fetch_assoc()): ?>
                <li><?= htmlspecialchars($e['nombre']) ?></li>
                <?php endwhile; ?>
            </ul>
        </div>

        <div class="columna card-info">
            <h2>Rutas y Destinos</h2>
            <p>Armenia, Pereira, Salento, Quibdó</p>
            <p>Cali, Manizales, Pereira</p>
            <p>Medellín, Bogotá, Cali, Popayán</p>
            <p>Cúcuta, Bucaramanga, Ibagué</p>
            <p>Bogotá, Cartagena, Santa Marta</p>
            <p>Bucaramanga, Medellín, Barranquilla</p>
            <p>Barranquilla, Bogotá, Medellín, Cartagena, Cali</p>
        </div>

    </section>

    <section class="beneficios">

        <h2>¿Por qué viajar con nosotros?</h2>

        <div class="beneficios-contenedor">

            <div class="beneficio">
                <h3>Empresas certificadas</h3>
                <p>Trabajamos con las principales empresas de transporte del país.</p>
            </div>

            <div class="beneficio">
                <h3>Horarios diarios</h3>
                <p>Disponibilidad constante para múltiples destinos nacionales.</p>
            </div>

            <div class="beneficio">
                <h3>Compra segura</h3>
                <p>Simulación de compra confiable y organizada.</p>
            </div>

        </div>

    </section>

<?php require_once __DIR__.'/includes/footer.php'; ?>
