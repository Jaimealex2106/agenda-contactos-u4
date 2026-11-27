<?php
/*
 * ver.php
 * READ individual: muestra el detalle completo de un contacto.
 * El id llega por GET, se fuerza a entero con (int) y se usa en una
 * sentencia preparada; nunca se concatena directo en el SQL.
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/helpers.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM contactos WHERE id = :id');
$stmt->execute(['id' => $id]);
$contacto = $stmt->fetch();

$titulo = 'Detalle del contacto';
include __DIR__ . '/includes/header.php';

if (!$contacto): ?>
    <div class="alerta alerta--error">No se encontró ningún contacto con ese id.</div>
    <a class="boton" href="index.php">← Volver al listado</a>
<?php else: ?>
    <div class="tarjeta tarjeta-detalle">
        <img src="archivos/<?= h($contacto['foto']) ?>" alt="Foto de <?= h($contacto['nombre']) ?>" class="tarjeta-detalle__foto">
        <div class="tarjeta-detalle__datos">
            <h3><?= h($contacto['nombre']) ?> <?= h($contacto['apellido']) ?></h3>
            <dl>
                <dt>Teléfono</dt><dd>📞 <?= h($contacto['telefono']) ?></dd>
                <?php if (!empty($contacto['email'])): ?>
                    <dt>Correo</dt><dd>✉️ <?= h($contacto['email']) ?></dd>
                <?php endif; ?>
                <?php if (!empty($contacto['direccion'])): ?>
                    <dt>Dirección</dt><dd>📍 <?= h($contacto['direccion']) ?></dd>
                <?php endif; ?>
                <?php if (!empty($contacto['notas'])): ?>
                    <dt>Notas</dt><dd><?= nl2br(h($contacto['notas'])) ?></dd>
                <?php endif; ?>
                <dt>Registrado</dt><dd><?= h(date('d/m/Y H:i', strtotime($contacto['creado_en']))) ?></dd>
            </dl>
            <div class="formulario__acciones">
                <a href="editar.php?id=<?= (int) $contacto['id'] ?>" class="boton boton--secundario">✏️ Editar</a>
                <a href="eliminar.php?id=<?= (int) $contacto['id'] ?>" class="boton boton--peligro">🗑 Eliminar</a>
                <a href="index.php" class="boton">← Volver</a>
            </div>
        </div>
    </div>
<?php endif;

include __DIR__ . '/includes/footer.php';
