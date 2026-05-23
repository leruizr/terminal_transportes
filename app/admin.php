<?php
// app/admin.php
session_start();
require_once __DIR__.'/includes/db.php';

if (empty($_SESSION['admin_logged'])) {
    header('Location: login.php');
    exit;
}

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    // GUARDAR VEHÍCULO (insert si id=0, update si id>0)
    if ($accion === 'guardar_vehiculo') {
        $id = (int)($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $capacidad = trim($_POST['capacidad'] ?? '');
        $comodidades = trim($_POST['comodidades'] ?? '');
        $ideal_para = trim($_POST['ideal_para'] ?? '');

        if ($nombre && $capacidad && $comodidades && $ideal_para) {
            if ($id > 0) {
                $stmt = $mysqli->prepare("UPDATE tipos_vehiculo SET nombre=?, capacidad=?, comodidades=?, ideal_para=? WHERE id=?");
                $stmt->bind_param('ssssi', $nombre, $capacidad, $comodidades, $ideal_para, $id);
                if ($stmt->execute()) {
                    $mensaje = "Vehículo '$nombre' actualizado correctamente.";
                } else {
                    $error = 'Error al actualizar vehículo: ' . $mysqli->error;
                }
            } else {
                $stmt = $mysqli->prepare("INSERT INTO tipos_vehiculo (nombre, capacidad, comodidades, ideal_para) VALUES (?,?,?,?)");
                $stmt->bind_param('ssss', $nombre, $capacidad, $comodidades, $ideal_para);
                if ($stmt->execute()) {
                    $mensaje = "Vehículo '$nombre' registrado correctamente.";
                } else {
                    $error = 'Error al registrar vehículo: ' . $mysqli->error;
                }
            }
        } else {
            $error = 'Complete todos los campos del vehículo.';
        }
    }

    // ELIMINAR VEHÍCULO
    elseif ($accion === 'eliminar_vehiculo') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $mysqli->prepare("DELETE FROM tipos_vehiculo WHERE id = ?");
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                $mensaje = 'Vehículo eliminado (y sus rutas asociadas, por la cascada FK).';
            } else {
                $error = 'Error al eliminar vehículo: ' . $mysqli->error;
            }
        }
    }

    // GUARDAR RUTA (insert si id=0, update si id>0)
    elseif ($accion === 'guardar_ruta') {
        $id = (int)($_POST['id'] ?? 0);
        $ciudad_id = (int)($_POST['ciudad_destino_id'] ?? 0);
        $empresa_id = (int)($_POST['empresa_id'] ?? 0);
        $tipo_id = (int)($_POST['tipo_vehiculo_id'] ?? 0);
        $horario = trim($_POST['horario'] ?? '');
        $precio = (int)($_POST['precio'] ?? 0);
        $duracion = trim($_POST['duracion'] ?? '');

        if ($ciudad_id && $empresa_id && $tipo_id && $horario && $precio > 0 && $duracion) {
            if ($id > 0) {
                $stmt = $mysqli->prepare("UPDATE rutas SET ciudad_destino_id=?, empresa_id=?, horario=?, precio=?, tipo_vehiculo_id=?, duracion=? WHERE id=?");
                $stmt->bind_param('iisiisi', $ciudad_id, $empresa_id, $horario, $precio, $tipo_id, $duracion, $id);
                if ($stmt->execute()) {
                    $mensaje = 'Ruta actualizada correctamente.';
                } else {
                    $error = 'Error al actualizar ruta: ' . $mysqli->error;
                }
            } else {
                $stmt = $mysqli->prepare("INSERT INTO rutas (ciudad_destino_id, empresa_id, horario, precio, tipo_vehiculo_id, duracion) VALUES (?,?,?,?,?,?)");
                $stmt->bind_param('iisiis', $ciudad_id, $empresa_id, $horario, $precio, $tipo_id, $duracion);
                if ($stmt->execute()) {
                    $mensaje = 'Ruta registrada correctamente.';
                } else {
                    $error = 'Error al registrar ruta: ' . $mysqli->error;
                }
            }
        } else {
            $error = 'Complete todos los campos de la ruta.';
        }
    }

    // ELIMINAR RUTA
    elseif ($accion === 'eliminar_ruta') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $mysqli->prepare("DELETE FROM rutas WHERE id = ?");
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                $mensaje = 'Ruta eliminada correctamente.';
            } else {
                $error = 'Error al eliminar ruta: ' . $mysqli->error;
            }
        }
    }
}

// Modo edición: cargar datos para prefill
$edit_vehiculo = null;
if (isset($_GET['edit_vehiculo'])) {
    $id = (int)$_GET['edit_vehiculo'];
    $stmt = $mysqli->prepare("SELECT * FROM tipos_vehiculo WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $edit_vehiculo = $stmt->get_result()->fetch_assoc();
}

$edit_ruta = null;
if (isset($_GET['edit_ruta'])) {
    $id = (int)$_GET['edit_ruta'];
    $stmt = $mysqli->prepare("SELECT * FROM rutas WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $edit_ruta = $stmt->get_result()->fetch_assoc();
}

// Datos para selects
$lista_ciudades = [];
$res = $mysqli->query("SELECT id, nombre FROM ciudades ORDER BY nombre");
while ($r = $res->fetch_assoc()) $lista_ciudades[] = $r;

$lista_empresas = [];
$res = $mysqli->query("SELECT id, nombre FROM empresas ORDER BY nombre");
while ($r = $res->fetch_assoc()) $lista_empresas[] = $r;

$lista_tipos = [];
$res = $mysqli->query("SELECT id, nombre FROM tipos_vehiculo ORDER BY nombre");
while ($r = $res->fetch_assoc()) $lista_tipos[] = $r;

// Listados
$todos_vehiculos = $mysqli->query("SELECT id, nombre, capacidad, comodidades, ideal_para FROM tipos_vehiculo ORDER BY id DESC");
$todas_rutas = $mysqli->query("
    SELECT r.id, r.ciudad_destino_id, r.empresa_id, r.tipo_vehiculo_id,
           c.nombre AS ciudad, e.nombre AS empresa, tv.nombre AS vehiculo,
           r.horario, r.precio, r.duracion
    FROM rutas r
    JOIN ciudades c ON r.ciudad_destino_id = c.id
    JOIN empresas e ON r.empresa_id = e.id
    JOIN tipos_vehiculo tv ON r.tipo_vehiculo_id = tv.id
    ORDER BY r.id DESC
");

// Valores para los formularios
$v_id = $edit_vehiculo['id'] ?? 0;
$v_nombre = $edit_vehiculo['nombre'] ?? '';
$v_capacidad = $edit_vehiculo['capacidad'] ?? '';
$v_comodidades = $edit_vehiculo['comodidades'] ?? '';
$v_ideal = $edit_vehiculo['ideal_para'] ?? '';

$r_id = $edit_ruta['id'] ?? 0;
$r_ciudad = $edit_ruta['ciudad_destino_id'] ?? 0;
$r_empresa = $edit_ruta['empresa_id'] ?? 0;
$r_tipo = $edit_ruta['tipo_vehiculo_id'] ?? 0;
$r_horario = $edit_ruta['horario'] ?? '';
$r_precio = $edit_ruta['precio'] ?? '';
$r_duracion = $edit_ruta['duracion'] ?? '';

$titulo_pagina = 'Admin - Terminal de Transportes';
$hero_titulo = 'Panel de Administración';
$hero_subtitulo = 'Sesión: admin · Gestión de vehículos y rutas';

$estilos_extra = '
    .admin-form { flex-wrap: wrap; gap: 20px; max-width: 1100px; margin: 0 auto; }
    .admin-form .campo { flex: 1 1 220px; }
    .admin-form .campo.full { flex: 1 1 100%; }
    .admin-form .btn-calcular { align-self: flex-end; }
    .tabla-admin { width: 100%; max-width: 1100px; margin: 20px auto 0 auto; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
    .tabla-admin th { background: linear-gradient(90deg, #58c1c9, #124f9e); color: white; padding: 12px; text-align: left; font-weight: 600; }
    .tabla-admin td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 14px; }
    .tabla-admin tr:hover { background: #f9f9f9; }
    .bloque-admin { padding: 40px 20px; }
    .bloque-admin + .bloque-admin { border-top: 1px solid #eee; }
    .btn-accion { background: #124f9e; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 13px; text-decoration: none; display: inline-block; }
    .btn-accion.editar { background: #2e7d32; }
    .btn-accion.eliminar { background: #c62828; }
    .btn-accion:hover { opacity: 0.9; }
    .btn-cancelar { background: #888; color: white; border: none; padding: 18px 25px; border-radius: 15px; font-weight: bold; cursor: pointer; text-decoration: none; }
    .barra-sesion { max-width:1100px; margin: 0 auto 10px auto; padding: 10px 15px; background:#fff3cd; border:1px solid #ffe58f; border-radius:8px; display:flex; justify-content:space-between; align-items:center; font-size:14px; }
';
require_once __DIR__.'/includes/header.php';
?>

    <section class="cotizacion" style="text-align:left;">

        <div class="barra-sesion">
            <span>Conectado como <strong>admin</strong></span>
            <a href="login.php?logout=1" class="btn-accion eliminar">Cerrar sesión</a>
        </div>

        <?php if ($mensaje): ?>
        <div style="background:#e8f5e9; color:#2e7d32; padding:15px; border-radius:10px; margin: 0 auto 20px auto; max-width:1100px; font-weight:bold;">
            <?= htmlspecialchars($mensaje) ?>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div style="background:#ffebee; color:#c62828; padding:15px; border-radius:10px; margin: 0 auto 20px auto; max-width:1100px; font-weight:bold;">
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <!-- ============== VEHÍCULOS ============== -->
        <div class="bloque-admin" id="vehiculos">
            <h2 style="text-align:center;">
                <?= $edit_vehiculo ? 'Editar Vehículo #'.(int)$v_id : 'Registrar Vehículo' ?>
            </h2>

            <form method="post" class="cotizacion-form admin-form">
                <input type="hidden" name="accion" value="guardar_vehiculo">
                <input type="hidden" name="id" value="<?= (int)$v_id ?>">

                <div class="campo">
                    <label>Nombre</label>
                    <input type="text" name="nombre" placeholder="Ej: Bus Económico" required
                           value="<?= htmlspecialchars($v_nombre) ?>">
                </div>

                <div class="campo">
                    <label>Capacidad</label>
                    <input type="text" name="capacidad" placeholder="Ej: 30 - 35 pasajeros" required
                           value="<?= htmlspecialchars($v_capacidad) ?>">
                </div>

                <div class="campo full">
                    <label>Comodidades (separadas por coma)</label>
                    <input type="text" name="comodidades" placeholder="Aire acondicionado, WiFi, Asientos reclinables" required
                           value="<?= htmlspecialchars($v_comodidades) ?>">
                </div>

                <div class="campo full">
                    <label>Ideal para</label>
                    <input type="text" name="ideal_para" placeholder="Ej: Rutas intermedias y viajes económicos" required
                           value="<?= htmlspecialchars($v_ideal) ?>">
                </div>

                <button type="submit" class="btn-calcular">
                    <?= $edit_vehiculo ? 'Actualizar Vehículo' : 'Guardar Vehículo' ?>
                </button>
                <?php if ($edit_vehiculo): ?>
                <a href="admin.php#vehiculos" class="btn-cancelar">Cancelar</a>
                <?php endif; ?>
            </form>

            <h3 style="max-width:1100px; margin: 40px auto 10px auto; color:#124f9e;">Vehículos registrados</h3>
            <table class="tabla-admin">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Capacidad</th>
                        <th>Ideal para</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($v = $todos_vehiculos->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$v['id'] ?></td>
                        <td><strong><?= htmlspecialchars($v['nombre']) ?></strong></td>
                        <td><?= htmlspecialchars($v['capacidad']) ?></td>
                        <td><?= htmlspecialchars($v['ideal_para']) ?></td>
                        <td style="white-space:nowrap;">
                            <a href="admin.php?edit_vehiculo=<?= (int)$v['id'] ?>#vehiculos" class="btn-accion editar">Editar</a>
                            <form method="post" style="display:inline;"
                                  onsubmit="return confirm('¿Eliminar el vehículo &quot;<?= htmlspecialchars($v['nombre'], ENT_QUOTES) ?>&quot;? Esto también eliminará las rutas asociadas.');">
                                <input type="hidden" name="accion" value="eliminar_vehiculo">
                                <input type="hidden" name="id" value="<?= (int)$v['id'] ?>">
                                <button type="submit" class="btn-accion eliminar">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- ============== RUTAS ============== -->
        <div class="bloque-admin" id="rutas">
            <h2 style="text-align:center;">
                <?= $edit_ruta ? 'Editar Ruta #'.(int)$r_id : 'Registrar Ruta' ?>
            </h2>

            <form method="post" class="cotizacion-form admin-form">
                <input type="hidden" name="accion" value="guardar_ruta">
                <input type="hidden" name="id" value="<?= (int)$r_id ?>">

                <div class="campo">
                    <label>Empresa</label>
                    <select name="empresa_id" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($lista_empresas as $e): ?>
                        <option value="<?= (int)$e['id'] ?>" <?= ((int)$e['id'] === (int)$r_empresa) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($e['nombre']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo">
                    <label>Ciudad destino</label>
                    <select name="ciudad_destino_id" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($lista_ciudades as $c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= ((int)$c['id'] === (int)$r_ciudad) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nombre']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo">
                    <label>Tipo de vehículo</label>
                    <select name="tipo_vehiculo_id" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($lista_tipos as $t): ?>
                        <option value="<?= (int)$t['id'] ?>" <?= ((int)$t['id'] === (int)$r_tipo) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['nombre']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo">
                    <label>Horario</label>
                    <input type="text" name="horario" placeholder="Ej: 6:00 AM" required
                           value="<?= htmlspecialchars($r_horario) ?>">
                </div>

                <div class="campo">
                    <label>Precio (COP)</label>
                    <input type="number" name="precio" min="1" placeholder="Ej: 80000" required
                           value="<?= htmlspecialchars((string)$r_precio) ?>">
                </div>

                <div class="campo">
                    <label>Duración</label>
                    <input type="text" name="duracion" placeholder="Ej: 6h 30m" required
                           value="<?= htmlspecialchars($r_duracion) ?>">
                </div>

                <button type="submit" class="btn-calcular">
                    <?= $edit_ruta ? 'Actualizar Ruta' : 'Guardar Ruta' ?>
                </button>
                <?php if ($edit_ruta): ?>
                <a href="admin.php#rutas" class="btn-cancelar">Cancelar</a>
                <?php endif; ?>
            </form>

            <h3 style="max-width:1100px; margin: 40px auto 10px auto; color:#124f9e;">Rutas registradas</h3>
            <table class="tabla-admin">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Empresa</th>
                        <th>Destino</th>
                        <th>Vehículo</th>
                        <th>Horario</th>
                        <th>Duración</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($r = $todas_rutas->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$r['id'] ?></td>
                        <td><strong><?= htmlspecialchars($r['empresa']) ?></strong></td>
                        <td><?= htmlspecialchars($r['ciudad']) ?></td>
                        <td><?= htmlspecialchars($r['vehiculo']) ?></td>
                        <td><?= htmlspecialchars($r['horario']) ?></td>
                        <td><?= htmlspecialchars($r['duracion']) ?></td>
                        <td>$<?= number_format($r['precio'], 0, ',', '.') ?></td>
                        <td style="white-space:nowrap;">
                            <a href="admin.php?edit_ruta=<?= (int)$r['id'] ?>#rutas" class="btn-accion editar">Editar</a>
                            <form method="post" style="display:inline;"
                                  onsubmit="return confirm('¿Eliminar esta ruta (#<?= (int)$r['id'] ?>)?');">
                                <input type="hidden" name="accion" value="eliminar_ruta">
                                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn-accion eliminar">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </section>

<?php require_once __DIR__.'/includes/footer.php'; ?>
