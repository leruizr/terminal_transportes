<?php
// app/contacto.php

// Procesar envío de formulario
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $msg = trim($_POST['mensaje'] ?? '');

    if ($nombre && $email && $msg) {
        $mensaje = 'Mensaje enviado con éxito. Nos pondremos en contacto lo más pronto posible.';
    } else {
        $mensaje = 'Por favor complete todos los campos.';
    }
}

$titulo_pagina = 'Contacto - Terminal';
$hero_titulo = 'Contáctanos';
$hero_subtitulo = 'Estamos aquí para ayudarte con tus consultas y necesidades de viaje';
require_once __DIR__.'/includes/header.php';
?>

    <section class="info">
        <div class="card-info" style="width: 50%;">
            <h2>Envíanos un mensaje</h2>

            <?php if ($mensaje): ?>
            <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                <?= htmlspecialchars($mensaje) ?>
            </div>
            <?php endif; ?>

            <form method="post">
                <div class="campo" style="margin-bottom: 15px; background: none; box-shadow: none; padding: 0;">
                    <label style="color: #333;">Nombre Completo</label>
                    <input type="text" name="nombre" placeholder="Tu nombre" required style="border: 1px solid #ccc; color: #333;">
                </div>
                <div class="campo" style="margin-bottom: 15px; background: none; box-shadow: none; padding: 0;">
                    <label style="color: #333;">Correo Electrónico</label>
                    <input type="email" name="email" placeholder="correo@ejemplo.com" required style="border: 1px solid #ccc; color: #333;">
                </div>
                <div class="campo" style="margin-bottom: 15px; background: none; box-shadow: none; padding: 0;">
                    <label style="color: #333;">Mensaje</label>
                    <textarea name="mensaje" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc;" rows="4" placeholder="¿En qué podemos ayudarte?"></textarea>
                </div>
                <button type="submit" class="btn-buscar" style="width: 100%;">Enviar Mensaje</button>
            </form>
        </div>

        <div class="card-info" style="width: 30%;">
            <h2>Atención al Cliente</h2>
            <p><strong><i class="fas fa-map-marker-alt"></i> Dirección:</strong><br> Carrera 65 N° 8B - 91, Medellín</p>
            <p><strong><i class="fas fa-phone"></i> Teléfono:</strong><br> (604) 361 15 88</p>
            <p><strong><i class="fas fa-envelope"></i> Email:</strong><br> contacto@terminaldetransporte.com</p>
            <p><strong><i class="fas fa-clock"></i> Horario:</strong><br> Abierto las 24 horas</p>
        </div>
    </section>

<?php require_once __DIR__.'/includes/footer.php'; ?>
