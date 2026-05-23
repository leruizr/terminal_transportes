<?php
// app/includes/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo_pagina ?? 'Terminal de Transportes') ?></title>

    <!-- SEO -->
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Consulta horarios de buses, rutas disponibles y compra tiquetes online en la Terminal de Transportes de Colombia.') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_keywords ?? 'terminal de transportes, tiquetes online, horarios de buses, rutas nacionales, viajes en bus Colombia') ?>">
    <meta name="author" content="Luis Enrique Ruiz y Juan Camilo Zuleta">
    <meta name="robots" content="index, follow">

    <!-- Open Graph (compartir en redes) -->
    <meta property="og:title" content="<?= htmlspecialchars($og_title ?? ($titulo_pagina ?? 'Terminal de Transportes')) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($og_description ?? ($meta_description ?? 'Compra tiquetes online y consulta rutas disponibles.')) ?>">
    <meta property="og:image" content="http://localhost:8888/terminal_completo/img/logo.png">
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://<?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'localhost:8888') ?><?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/') ?>">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <?php if (!empty($estilos_extra)): ?>
    <style><?= $estilos_extra ?></style>
    <?php endif; ?>
</head>
<body>

    <section class="hero">

        <header>
            <div class="logo">
                <img src="../img/logo.png" alt="Logo Terminal de Transportes">
            </div>

            <nav class="menu">
                <a href="index.php">Inicio</a>
                <a href="nosotros.php">Nosotros</a>
                <a href="empresas.php">Empresas</a>
                <a href="vehiculos.php">Vehículos</a>
                <a href="rutas.php">Rutas</a>
                <a href="horarios.php">Horarios</a>
                <a href="disponibilidad.php">Disponibilidad</a>
                <a href="cotizacion.php">Cotización</a>
                <a href="contacto.php">Contacto</a>
                <a href="admin.php">Admin</a>
                <?php if (!empty($_SESSION['admin_logged'])): ?>
                <a href="login.php?logout=1">Cerrar sesión</a>
                <?php endif; ?>
            </nav>
        </header>

        <div class="hero-text">
            <h1><?= htmlspecialchars($hero_titulo ?? '') ?></h1>
            <p><?= htmlspecialchars($hero_subtitulo ?? '') ?></p>
        </div>

    </section>
