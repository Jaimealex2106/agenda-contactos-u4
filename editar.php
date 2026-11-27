<?php
/*
 * editar.php
 * UPDATE (vista): formulario precargado con los datos actuales del contacto.
 * La foto es opcional al editar: si no se sube una nueva, actualizar.php
 * conserva la que ya existía (su nombre viaja en un campo oculto).
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/helpers.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM contactos WHERE id = :id');
$stmt->execute(['id' => $id]);
$contacto = $stmt->fetch();

// Si guardar.php (actualizar.php) encontró errores, recuperamos lo que el usuario había escrito
$datos = $_SESSION['datos_anteriores'] ?? $contacto ?? [];
unset($_SESSION['datos_anteriores']);

$titulo = 'Editar contacto';
include __DIR__ . '/includes/header.php';

if (!$contacto): ?>
    <div class="alerta alerta--error">No se encontró ningún contacto con ese id.</div>
    <a class="boton" href="index.php">← Volver al listado</a>
<?php else: ?>

<form class="formulario tarjeta" action="actualizar.php" method="post" enctype="multipart/form-data" novalidate>
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
    <input type="hidden" name="id" value="<?= (int) $contacto['id'] ?>">
    <input type="hidden" name="foto_actual" value="<?= h($contacto['foto']) ?>">

    <div class="campo campo--ancho">
        <img src="archivos/<?= h($contacto['foto']) ?>" alt="Foto actual" class="formulario__foto-actual">
    </div>

    <div class="campo">
        <label for="nombre">Nombre *</label>
        <input type="text" id="nombre" name="nombre" required maxlength="60" value="<?= h($datos['nombre'] ?? '') ?>">
    </div>

    <div class="campo">
        <label for="apellido">Apellido *</label>
        <input type="text" id="apellido" name="apellido" required maxlength="60" value="<?= h($datos['apellido'] ?? '') ?>">
    </div>

    <div class="campo">
        <label for="telefono">Teléfono *</label>
        <input type="tel" id="telefono" name="telefono" required pattern="[0-9+\s-]{7,20}" value="<?= h($datos['telefono'] ?? '') ?>">
    </div>

    <div class="campo">
        <label for="foto">Reemplazar foto (opcional)</label>
        <input type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/webp">
    </div>

    <div class="campo">
        <label for="email">Correo (opcional)</label>
        <input type="email" id="email" name="email" maxlength="100" value="<?= h($datos['email'] ?? '') ?>">
    </div>

    <div class="campo">
        <label for="direccion">Dirección (opcional)</label>
        <input type="text" id="direccion" name="direccion" maxlength="150" value="<?= h($datos['direccion'] ?? '') ?>">
    </div>

    <div class="campo campo--ancho">
        <label for="notas">Notas (opcional)</label>
        <textarea id="notas" name="notas" rows="3" maxlength="500"><?= h($datos['notas'] ?? '') ?></textarea>
    </div>

    <div class="formulario__acciones">
        <button type="submit" class="boton">💾 Guardar cambios</button>
        <a href="ver.php?id=<?= (int) $contacto['id'] ?>" class="boton boton--secundario">Cancelar</a>
    </div>
</form>
<?php endif;

include __DIR__ . '/includes/footer.php';
