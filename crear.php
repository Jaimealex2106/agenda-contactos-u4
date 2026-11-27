<?php
/*
 * crear.php
 * CREATE (vista): formulario para agregar un contacto nuevo.
 * Si guardar.php encontró errores, los datos que el usuario ya había escrito
 * se recuperan de $_SESSION['datos_anteriores'] para no hacerlo escribir todo
 * de nuevo.
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/includes/helpers.php';

$titulo = 'Nuevo contacto';
$datos = $_SESSION['datos_anteriores'] ?? [];
unset($_SESSION['datos_anteriores']);

include __DIR__ . '/includes/header.php';
?>

<form class="formulario tarjeta" action="guardar.php" method="post" enctype="multipart/form-data" novalidate>
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

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
        <input type="tel" id="telefono" name="telefono" required pattern="[0-9+\s-]{7,20}"
               placeholder="961 123 4567" value="<?= h($datos['telefono'] ?? '') ?>">
    </div>

    <div class="campo">
        <label for="foto">Foto *</label>
        <input type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/webp" required>
        <small>JPG, PNG o WEBP, máximo 2&nbsp;MB.</small>
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
        <button type="submit" class="boton">💾 Guardar contacto</button>
        <a href="index.php" class="boton boton--secundario">Cancelar</a>
    </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
