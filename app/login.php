<?php
// app/login.php
session_start();

if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged']);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($usuario === 'admin' && $password === 'admin1234') {
        $_SESSION['admin_logged'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos.';
    }
}

$titulo_pagina = 'Login Admin - Terminal de Transportes';
$hero_titulo = 'Acceso Administrativo';
$hero_subtitulo = 'Inicia sesión para gestionar el sistema';
require_once __DIR__.'/includes/header.php';
?>

    <section class="info" style="justify-content:center;">
        <div class="card-info" style="width: 400px;">
            <h2 style="text-align:center;">
                <i class="fas fa-lock" style="color:#124f9e; margin-right:10px;"></i>Iniciar sesión
            </h2>

            <?php if ($error): ?>
            <div style="background:#ffebee; color:#c62828; padding:12px; border-radius:8px; margin-bottom:15px; text-align:center;">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['admin_logged'])): ?>
            <div style="background:#e8f5e9; color:#2e7d32; padding:12px; border-radius:8px; margin-bottom:15px; text-align:center;">
                Ya tienes una sesión activa. <a href="admin.php" style="color:#2e7d32; font-weight:bold;">Ir al panel</a>
            </div>
            <?php endif; ?>

            <form method="post">
                <div style="margin-bottom:15px;">
                    <label style="font-weight:bold; display:block; margin-bottom:6px;">Usuario</label>
                    <input type="text" name="usuario" required autofocus
                           style="width:100%; padding:10px; border:1px solid #ccc; border-radius:8px;">
                </div>

                <div style="margin-bottom:20px;">
                    <label style="font-weight:bold; display:block; margin-bottom:6px;">Contraseña</label>
                    <input type="password" name="password" required
                           style="width:100%; padding:10px; border:1px solid #ccc; border-radius:8px;">
                </div>

                <button type="submit" class="btn-buscar" style="width:100%;">
                    Ingresar
                </button>
            </form>
        </div>
    </section>

<?php require_once __DIR__.'/includes/footer.php'; ?>
