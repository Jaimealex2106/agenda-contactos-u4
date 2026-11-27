<?php
/*
 * index.php
 * READ: listado de contactos y búsqueda.
 * Usa PDO con una sentencia preparada tanto para traer todos los contactos
 * como para la búsqueda, y htmlspecialchars() (función h()) al imprimir
 * cualquier dato que venga de la base de datos o del usuario.
 */
require_once __DIR__ . '/config/db.php';

$titulo = 'Listado de contactos';
$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    // Búsqueda con LIKE en nombre, apellido y teléfono. Con sentencias preparadas
    // reales de MySQL (EMULATE_PREPARES = false) cada marcador con nombre solo
    // puede usarse una vez, por eso hay un marcador distinto por cada campo.
    $sql = 'SELECT id, nombre, apellido, telefono, foto, email
            FROM contactos
            WHERE nombre LIKE :busqueda_nombre
               OR apellido LIKE :busqueda_apellido
               OR telefono LIKE :busqueda_telefono
            ORDER BY nombre, apellido';
    $patron = '%' . $q . '%';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'busqueda_nombre'   => $patron,
        'busqueda_apellido' => $patron,
        'busqueda_telefono' => $patron,
    ]);
} else {
    $sql = 'SELECT id, nombre, apellido, telefono, foto, email FROM contactos ORDER BY nombre, apellido';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

$contactos = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<?php if ($q !== ''): ?>
    <p class="nota">
        Resultados para "<strong><?= h($q) ?></strong>": <?= count($contactos) ?> contacto(s).
        <a href="index.php">Ver todos</a>
    </p>
<?php endif; ?>

<?php if (empty($contactos)): ?>
    <div class="tarjeta tarjeta--vacio">
        <p>No hay contactos <?= $q !== '' ? 'que coincidan con tu búsqueda' : 'guardados todavía' ?>.</p>
        <a class="boton" href="crear.php">➕ Agregar el primer contacto</a>
    </div>
<?php else: ?>
    <div class="rejilla-contactos">
        <?php foreach ($contactos as $contacto): ?>
            <article class="tarjeta-contacto">
                <img src="archivos/<?= h($contacto['foto']) ?>" alt="Foto de <?= h($contacto['nombre']) ?>" class="tarjeta-contacto__foto">
                <div class="tarjeta-contacto__info">
                    <h3><?= h($contacto['nombre']) ?> <?= h($contacto['apellido']) ?></h3>
                    <p>📞 <?= h($contacto['telefono']) ?></p>
                    <?php if (!empty($contacto['email'])): ?>
                        <p>✉️ <?= h($contacto['email']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="tarjeta-contacto__acciones">
                    <a href="ver.php?id=<?= (int) $contacto['id'] ?>" class="boton boton--pequeno">Ver</a>
                    <a href="editar.php?id=<?= (int) $contacto['id'] ?>" class="boton boton--pequeno boton--secundario">Editar</a>
                    <a href="eliminar.php?id=<?= (int) $contacto['id'] ?>" class="boton boton--pequeno boton--peligro">Eliminar</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
