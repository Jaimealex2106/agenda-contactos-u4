# Mini Proyecto 2 · Unidad 4 — Agenda de Contactos con PHP y MySQL (CRUD)

## Integrantes del equipo

- Jaime A. Espinosa
- José M. Pérez
- Julia C. Gordillo

## Materia

**Programación Web** — Unidad 4: Programación del lado del servidor con PHP
Instituto Tecnológico de Tuxtla Gutiérrez · Ingeniería en Sistemas Computacionales · Grupo S5B
Docente: Ing. Jorge Octavio Guzmán Sánchez

## Descripción del proyecto

Aplicación web dinámica que gestiona una agenda de contactos con operaciones **CRUD completas**
(crear, leer, actualizar y eliminar), conectada a una base de datos **MySQL** mediante **PDO** y
sentencias preparadas. Cada contacto guarda nombre, apellido, teléfono, foto, correo, dirección
y notas. Incluye búsqueda, validación de formularios en cliente y servidor, protección contra
inyección SQL y contra XSS, confirmación previa antes de eliminar y un token CSRF en los
formularios que modifican datos. No usa frameworks externos.

## Tecnologías utilizadas

- **PHP 8** (servidor)
- **MySQL / MariaDB** como motor de base de datos
- **PDO** (`pdo_mysql`) con sentencias preparadas (`prepare()` / `execute()`)
- **HTML5 + CSS3** (sin frameworks)

## Funcionalidades implementadas (CRUD completo)

| Página | Operación | Descripción |
|---|---|---|
| `index.php` | **READ** | Listado de contactos y búsqueda por nombre, apellido o teléfono |
| `crear.php` | CREATE (vista) | Formulario para agregar un contacto nuevo |
| `guardar.php` | **CREATE** (lógica) | Valida y guarda el contacto (`INSERT`) |
| `ver.php` | **READ** individual | Detalle completo de un contacto |
| `editar.php` | UPDATE (vista) | Formulario precargado con los datos actuales |
| `actualizar.php` | **UPDATE** (lógica) | Valida y guarda los cambios (`UPDATE`) |
| `eliminar.php` | **DELETE** | Confirmación previa (GET) y borrado real (POST) |

## Sitio publicado

- 🌐 Sitio: `https://TU-SUBDOMINIO.infinityfreeapp.com` *(reemplazar con el link real)*
- 🔗 Repositorio: `https://github.com/USUARIO/agenda-contactos-u4` *(reemplazar con el link real)*

## Estructura

```
/
├── index.php
├── crear.php
├── guardar.php
├── editar.php
├── actualizar.php
├── ver.php
├── eliminar.php
├── schema.sql
├── config/
│   └── db.php
├── includes/
│   ├── helpers.php
│   ├── header.php
│   ├── menu.php
│   └── footer.php
├── css/
│   └── styles.css
└── archivos/        (fotos subidas por los usuarios)
```

## Puesta en marcha

1. Crear una base de datos MySQL e importar `schema.sql`.
2. Editar `config/db.php` con el host, nombre de base, usuario y contraseña reales.
3. Dar permisos de escritura a la carpeta `archivos/` (ahí se guardan las fotos subidas).
4. Abrir `index.php` en el navegador.
