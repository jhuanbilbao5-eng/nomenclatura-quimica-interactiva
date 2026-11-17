# ⚗ Nomenclatura Química Interactiva

**Plataforma educativa para aprender, practicar y evaluar la nomenclatura de compuestos inorgánicos**
(óxidos, hidruros, hidróxidos, ácidos y sales), pensada para estudiantes de secundaria de Sacaba, Cochabamba.

Proyecto Final 2026 · Programación II · Carrera de Sistemas Informáticos · Instituto Tecnológico Sacaba
**Equipo:** Bilbao Ramírez Juan José · Magne Rodriguez Jheyson Willy · **Docente:** Lic. Evelin Ala

🔗 **Sitio publicado:** `https://SU-SUBDOMINIO.infinityfreeapp.com` *(reemplazar con el enlace real)*

## Cuentas de prueba
| Rol | Correo | Contraseña |
|---|---|---|
| Administrador (docente) | `admin@nomenclatura.local` | `Admin123*` |
| Usuario (estudiante) | `estudiante@nomenclatura.local` | `Estudiante123` |

Cualquier estudiante puede crear su cuenta con **“Regístrate aquí”** en la pantalla de inicio de sesión.

## Módulos
| Módulo | Descripción |
|---|---|
| **Aprende Química** | 6 temas: qué es, cómo se formula, cómo se nombra, reacción de formación y ejemplos. |
| **Formular** | Se escribe una fórmula y se obtiene su nombre tradicional, Stock y sistemático. |
| **Nombrar** | Se escribe un nombre (o el nombre común) y se obtiene la fórmula. |
| **Evaluación** | Tres niveles (básico, intermedio, avanzado); 10 preguntas al azar; nota, revisión e historial. |
| **Repositorio** | Compuestos de la vida cotidiana (sal de mesa, lavandina, cal, bicarbonato…) con búsqueda y filtro. |
| **Administración** | CRUD de compuestos y preguntas con búsqueda y filtros, resultados de todos y reporte PDF. |

**Datos incluidos:** 371 compuestos y 156 preguntas (52 por nivel).

## Tecnologías (sin frameworks)
- **PHP 8 orientado a objetos** con arquitectura **MVC propia** (controlador frontal + enrutador). Sin frameworks ni Composer.
- **MySQL / MariaDB** con PDO y sentencias preparadas; base de datos relacional en 2FN con claves primarias y foráneas.
- **HTML5, CSS propio** (Flexbox, Grid, media queries) y **JavaScript puro**. No usa Bootstrap.
- **FPDF 1.9** (librería incluida en `libs/fpdf/`) para el reporte PDF.

## Seguridad
- Contraseñas cifradas con `password_hash()` (bcrypt) y verificación con `password_verify()`.
- Sentencias preparadas (contra inyección SQL) y `htmlspecialchars()` en las vistas (contra XSS).
- Token **CSRF** en todos los formularios POST.
- Control de acceso por rol en cada controlador; cada estudiante solo ve sus resultados.
- Bloqueo de la cuenta por 5 minutos tras 5 intentos fallidos y mensaje de error genérico.
- Sesión: identificador regenerado al iniciar sesión, cookie `HttpOnly`/`SameSite` y cierre tras 30 min de inactividad.
- Cabeceras de seguridad, errores técnicos ocultos en producción y carpetas internas bloqueadas con `.htaccess`.
- Las credenciales del hosting van en `config/credenciales.php`, que **no se sube** al repositorio (`.gitignore`).

## Instalación local (XAMPP)
1. Inicie **Apache** y **MySQL** en el XAMPP Control Panel.
2. Copie esta carpeta como `C:\xampp\htdocs\nomenclatura_quimica\`.
3. En `http://localhost/phpmyadmin` → **Importar** → `sql/nomenclatura_quimica.sql` → **Importar**.
4. Abra `http://localhost/nomenclatura_quimica/`.

Si su MySQL tiene contraseña, cree `config/credenciales.php` a partir de `config/credenciales.ejemplo.php`.

## Publicación (hosting gratuito)
Guía completa paso a paso: [`docs/GUIA_PUBLICACION.md`](docs/GUIA_PUBLICACION.md).
En resumen: crear la base de datos en el hosting, importar **`sql/nomenclatura_quimica_hosting.sql`**,
crear `config/credenciales.php` con los datos del hosting y subir los archivos a `htdocs/`.

## Pruebas
Plan y resultados de las pruebas de funcionalidad, validación, seguridad y usabilidad:
[`docs/PRUEBAS.md`](docs/PRUEBAS.md).

## Estructura del proyecto (MVC)
```
├── config/        config.php · Database.php (PDO) · credenciales.ejemplo.php
├── core/          Router · Controller · Model · Auth · Validator · helpers.php
├── controllers/   Auth · Home · Aprende · Formular · Nombrar · Evaluacion · Resultado · Compuesto · Pregunta
├── models/        Usuario · Rol · TipoCompuesto · Compuesto · Nivel · Pregunta · Evaluacion · DetalleEvaluacion
├── views/         layout · auth · home · aprende · formular · nombrar · evaluacion · resultados · admin
├── public/        index.php (controlador frontal) · css/estilos.css · js/app.js · js/validaciones.js
├── libs/fpdf/     librería FPDF
├── sql/           nomenclatura_quimica.sql (XAMPP) · nomenclatura_quimica_hosting.sql (hosting)
└── docs/          guía de publicación y plan de pruebas
```
Las peticiones tienen la forma `public/index.php?c=<controlador>&a=<acción>`.

## Base de datos
8 tablas: `roles`, `usuarios`, `tipos_compuesto`, `compuestos`, `niveles`, `preguntas`, `evaluaciones`,
`detalle_evaluacion` (resuelve la relación N:M entre evaluaciones y preguntas). Los compuestos y preguntas
se desactivan en lugar de borrarse, para conservar el historial de evaluaciones.
