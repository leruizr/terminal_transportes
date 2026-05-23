<?php
// app/nosotros.php
$titulo_pagina = 'Nosotros - Terminal de Transportes';
$hero_titulo = 'Sobre Nosotros';
$hero_subtitulo = 'Conoce la Terminal de Transportes de Medellín';
require_once __DIR__.'/includes/header.php';
?>

    <!-- HISTORIA -->
    <section class="info" style="flex-direction: column; align-items: center;">

        <div class="card-info" style="width: 80%; margin-bottom: 30px;">
            <h2><i class="fas fa-history" style="color: #124f9e; margin-right: 10px;"></i>Nuestra Historia</h2>
            <p style="line-height: 1.8; text-align: justify;">
                La Terminal de Transportes de Medellín es el principal centro de operaciones de transporte terrestre
                intermunicipal e interdepartamental de la ciudad. Desde su fundación, hemos conectado a miles de
                colombianos con sus destinos, ofreciendo servicios de calidad, seguridad y comodidad.
            </p>
            <p style="line-height: 1.8; text-align: justify; margin-top: 15px;">
                Contamos con modernas instalaciones que operan las 24 horas del día, los 7 días de la semana,
                garantizando que nuestros usuarios siempre encuentren una opción de viaje que se ajuste a sus necesidades.
                Nuestra terminal se ha convertido en un punto de referencia para el transporte en Antioquia y Colombia.
            </p>
        </div>

        <!-- MISIÓN Y VISIÓN -->
        <div style="display: flex; gap: 30px; width: 80%; flex-wrap: wrap; justify-content: center;">

            <div class="card-info" style="flex: 1; min-width: 300px;">
                <h2><i class="fas fa-bullseye" style="color: #124f9e; margin-right: 10px;"></i>Misión</h2>
                <p style="line-height: 1.8; text-align: justify;">
                    Facilitar la movilidad terrestre de pasajeros mediante una infraestructura moderna y segura,
                    conectando a las empresas de transporte con los usuarios, garantizando calidad en el servicio,
                    comodidad y eficiencia en cada viaje.
                </p>
            </div>

            <div class="card-info" style="flex: 1; min-width: 300px;">
                <h2><i class="fas fa-eye" style="color: #124f9e; margin-right: 10px;"></i>Visión</h2>
                <p style="line-height: 1.8; text-align: justify;">
                    Ser reconocidos como la terminal de transporte líder en Colombia, destacándonos por la innovación
                    tecnológica, la excelencia en el servicio al cliente y el compromiso con el desarrollo sostenible
                    de nuestra región.
                </p>
            </div>

        </div>

    </section>

    <!-- SERVICIOS -->
    <section class="beneficios">

        <h2>Nuestros Servicios</h2>

        <div class="beneficios-contenedor">

            <div class="beneficio">
                <i class="fas fa-ticket-alt" style="font-size: 40px; color: #124f9e; margin-bottom: 15px;"></i>
                <h3>Venta de Tiquetes</h3>
                <p>Compra presencial en taquillas o en línea a través de nuestra plataforma web.</p>
            </div>

            <div class="beneficio">
                <i class="fas fa-couch" style="font-size: 40px; color: #124f9e; margin-bottom: 15px;"></i>
                <h3>Sala de Espera</h3>
                <p>Espacios cómodos y seguros para que esperes tu viaje con tranquilidad.</p>
            </div>

            <div class="beneficio">
                <i class="fas fa-info-circle" style="font-size: 40px; color: #124f9e; margin-bottom: 15px;"></i>
                <h3>Información 24/7</h3>
                <p>Atención personalizada las 24 horas para resolver todas tus dudas.</p>
            </div>

            <div class="beneficio">
                <i class="fas fa-store" style="font-size: 40px; color: #124f9e; margin-bottom: 15px;"></i>
                <h3>Locales Comerciales</h3>
                <p>Tiendas, restaurantes y servicios para tu comodidad antes de viajar.</p>
            </div>

            <div class="beneficio">
                <i class="fas fa-parking" style="font-size: 40px; color: #124f9e; margin-bottom: 15px;"></i>
                <h3>Parqueadero</h3>
                <p>Estacionamiento vigilado para que dejes tu vehículo con total seguridad.</p>
            </div>

            <div class="beneficio">
                <i class="fas fa-suitcase" style="font-size: 40px; color: #124f9e; margin-bottom: 15px;"></i>
                <h3>Guarda Equipaje</h3>
                <p>Servicio de custodia de maletas y paquetes mientras realizas tus diligencias.</p>
            </div>

        </div>

    </section>

<?php require_once __DIR__.'/includes/footer.php'; ?>
