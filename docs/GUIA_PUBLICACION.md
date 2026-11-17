# Guía de publicación — Semana 4

Esta guía explica cómo entregar los dos productos de la Semana 4:

1. **Repositorio de GitHub** con el código y el SQL.
2. **URL del sitio web publicado** (InfinityFree, gratis, con PHP y MySQL).

> Orden recomendado: **primero GitHub, después el hosting.** Así el archivo con la contraseña del
> hosting (`config/credenciales.php`) nunca llega a GitHub.

---

## Parte 1 · Subir el proyecto a GitHub (desde el navegador, sin instalar nada)

1. Entre a **https://github.com** y cree una cuenta (o inicie sesión). Verifique su correo.
2. Arriba a la derecha presione **+** → **New repository**.
   - **Repository name:** `nomenclatura-quimica-interactiva`
   - **Description:** `Plataforma educativa de nomenclatura química inorgánica - PHP MVC sin frameworks + MySQL`
   - Marque **Public**.
   - **NO** marque "Add a README" (el proyecto ya trae uno).
   - Presione **Create repository**.
3. En la página del repositorio vacío haga clic en el enlace **"uploading an existing file"**.
4. Abra en su computadora la carpeta `nomenclatura_quimica`, **seleccione todo su contenido**
   (carpetas `config`, `controllers`, `core`, `docs`, `libs`, `models`, `public`, `sql`, `views` y los
   archivos `index.php`, `README.md`, `.gitignore`) y **arrástrelo** a la página de GitHub.
   - Son menos de 100 archivos, así que entran en una sola subida.
   - Si Windows oculta `.gitignore`, actívelo en el Explorador: **Vista → Mostrar → Elementos ocultos**.
   - **No suba** `config/credenciales.php` si ya lo creó (GitHub en el navegador no respeta `.gitignore`).
5. Abajo, en **Commit changes**, escriba: `Proyecto final: Nomenclatura Química Interactiva` → **Commit changes**.
6. Agregue a su compañero: **Settings → Collaborators → Add people** (su usuario de GitHub).
7. Copie la dirección del repositorio, por ejemplo:
   `https://github.com/SU_USUARIO/nomenclatura-quimica-interactiva` ← **este es el enlace 1 que entregan**.

Compruebe que en GitHub se vea la carpeta `sql/` con `nomenclatura_quimica.sql` y que el README se muestre abajo.

---

## Parte 2 · Publicar el sitio en InfinityFree

### 2.1 Crear la cuenta de hosting
1. Entre a **https://www.infinityfree.com** → **Register** → cree su cuenta y verifique el correo.
2. En el panel (Client Area) presione **Create Account**.
3. Elija un **subdominio gratuito**, por ejemplo `nomenclatura-quimica` con la terminación
   `.infinityfreeapp.com` → **Create Account**.
4. Espere a que la cuenta diga **Active** (unos minutos). Un subdominio nuevo puede tardar hasta unas horas
   en abrir en todos los navegadores.
5. En la cuenta creada, abra **Account Details** y anote:
   - **Username** (ejemplo `if0_37123456`)
   - **Password** (botón *Show*)
   - **FTP Hostname** (ejemplo `ftpupload.net`)

### 2.2 Crear la base de datos e importar el SQL
1. Presione **Control Panel** (se abre el panel del hosting).
2. Entre a **MySQL Databases** → en *Create New Database* escriba `nomenclatura` → **Create Database**.
3. Anote los datos que aparecen en la lista:
   - **MySQL DB Name** (ejemplo `if0_37123456_nomenclatura`)
   - **MySQL User Name** (ejemplo `if0_37123456`)
   - **MySQL Host Name** (ejemplo `sql301.infinityfree.com`) ← **no es `localhost`**
   - **MySQL Password**: es la misma contraseña de la cuenta de hosting (paso 2.1.5).
4. Al lado de la base presione **Admin** (abre **phpMyAdmin**).
5. Pestaña **Importar** → **Seleccionar archivo** → elija **`sql/nomenclatura_quimica_hosting.sql`**
   (la versión para hosting, que no intenta crear la base) → **Continuar / Go**.
6. Deben aparecer 8 tablas: `roles`, `usuarios`, `tipos_compuesto`, `compuestos`, `niveles`, `preguntas`,
   `evaluaciones`, `detalle_evaluacion`.

### 2.3 Preparar el archivo de conexión
En su computadora, dentro de `nomenclatura_quimica/config/`:
1. Copie `credenciales.ejemplo.php` y nombre la copia **`credenciales.php`**.
2. Ábralo con Visual Studio Code y reemplace los datos con los del paso 2.2.3:
   ```php
   define('DB_HOST', 'sql301.infinityfree.com');
   define('DB_NAME', 'if0_37123456_nomenclatura');
   define('DB_USER', 'if0_37123456');
   define('DB_PASS', 'LaContraseñaDeLaCuenta');
   ```
3. Guarde. (En XAMPP este archivo no molesta: si quiere seguir probando en local, renómbrelo
   temporalmente o bórrelo; sin él se usan los datos de XAMPP.)

### 2.4 Subir los archivos
**Opción A — FileZilla (recomendada, sube carpetas completas):**
1. Instale FileZilla Client (https://filezilla-project.org).
2. Arriba escriba: **Servidor** `ftpupload.net` · **Usuario** `if0_37123456` · **Contraseña** la de la cuenta ·
   **Puerto** `21` → **Conexión rápida**.
3. A la derecha (servidor) entre a la carpeta **`htdocs`** y borre el archivo `index2.html` que trae por defecto.
4. A la izquierda (su PC) entre a la carpeta `nomenclatura_quimica`, seleccione **todo su contenido**
   (incluido `config/credenciales.php`) y arrástrelo a `htdocs`.

**Opción B — Administrador de archivos web:** Control Panel → **Online File Manager** → `htdocs` →
borre `index2.html` → suba las carpetas y archivos (es más lento porque se suben de a poco).

Al final, en `htdocs` deben quedar: `config/`, `controllers/`, `core/`, `libs/`, `models/`, `public/`,
`sql/`, `views/`, `index.php`, etc.

### 2.5 Probar el sitio publicado
1. Abra `https://nomenclatura-quimica.infinityfreeapp.com` (su subdominio). Lo envía solo a `/public/`.
2. Inicie sesión con las cuentas de prueba y revise: registro de un estudiante, evaluación, reporte PDF,
   CRUD del administrador, y que se vea bien en el celular.
3. **https:** en el Control Panel → **SSL Certificates** se puede pedir un certificado gratuito (opcional).
4. Copie la dirección ← **este es el enlace 2 que entregan**.

---

## Qué entregar
| Producto | Ejemplo |
|---|---|
| Repositorio de GitHub | `https://github.com/SU_USUARIO/nomenclatura-quimica-interactiva` |
| Sitio publicado | `https://nomenclatura-quimica.infinityfreeapp.com` |
| Cuentas de prueba | Administrador `admin@nomenclatura.local` / `Admin123*` · Estudiante `estudiante@nomenclatura.local` / `Estudiante123` |

Después de la calificación, cambie la contraseña del administrador desde **👤 Mi cuenta**.

---

## Problemas frecuentes
| Problema | Solución |
|---|---|
| "No se pudo conectar a la base de datos" | Revise `config/credenciales.php`: el host es `sqlXXX.infinityfree.com` (no `localhost`) y la contraseña es la de la cuenta de hosting. |
| Error al importar: `CREATE DATABASE` / acceso denegado | Importó el archivo equivocado: use `sql/nomenclatura_quimica_hosting.sql`. |
| Se ve la página por defecto de InfinityFree | Borre `index2.html` de `htdocs` y verifique que `index.php` esté directamente en `htdocs`. |
| "This site can't be reached" justo después de crear la cuenta | Espere: el subdominio nuevo tarda en activarse (a veces hasta unas horas). |
| Error 500 | Faltó subir alguna carpeta (por ejemplo `core/` o `libs/`). Vuelva a subir todo. |
| El formulario dice "El formulario expiró" | La página estuvo abierta mucho tiempo; recárguela y vuelva a enviar. |
