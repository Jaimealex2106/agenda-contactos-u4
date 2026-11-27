-- schema.sql
-- Importar en phpMyAdmin (InfinityFree: panel → MySQL Databases → Administrar → Importar)
-- Crea la tabla contactos usada por toda la aplicación.

CREATE TABLE IF NOT EXISTS contactos (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nombre     VARCHAR(60)  NOT NULL,
    apellido   VARCHAR(60)  NOT NULL,
    telefono   VARCHAR(20)  NOT NULL,
    foto       VARCHAR(255) NOT NULL,
    email      VARCHAR(100) NULL,
    direccion  VARCHAR(150) NULL,
    notas      TEXT NULL,
    creado_en  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Un par de contactos de ejemplo (opcional, se puede borrar).
-- Nota: estos dos registros usan un nombre de foto que no existe en el
-- servidor; bórralos o reemplaza "foto" por el nombre real de una imagen
-- que hayas subido a la carpeta archivos/.
-- INSERT INTO contactos (nombre, apellido, telefono, foto, email) VALUES
-- ('Ejemplo', 'Uno', '9611234567', 'placeholder.png', 'ejemplo1@correo.com'),
-- ('Ejemplo', 'Dos', '9619876543', 'placeholder.png', NULL);
