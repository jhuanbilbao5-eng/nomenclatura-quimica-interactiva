# Plan y resultados de pruebas — Semana 4

Pruebas realizadas sobre el sistema completo (navegador → controladores → modelos → base de datos),
en computadora (1366 px) y celular (375 px). **Resultado: todas las pruebas realizadas fueron correctas (62 pruebas automáticas
de extremo a extremo más la prueba de inactividad), sin errores ni advertencias de PHP.** Repítalas en el sitio publicado antes de la presentación.

## 1. Funcionalidad
| N.º | Prueba | Resultado esperado | Resultado |
|---|---|---|---|
| F01 | Registro de estudiante con datos válidos | "Cuenta creada" | ✔ Correcto |
| F02 | Inicio de sesión del administrador | Va al panel de administración | ✔ Correcto |
| F03 | Inicio de sesión del estudiante | Va a la pantalla principal | ✔ Correcto |
| F04 | Repositorio de compuestos cotidianos: búsqueda y filtro por tipo | Muestra solo los que coinciden | ✔ Correcto |
| F05 | Aprende Química: 6 temas con sus 5 apartados | Contenido completo | ✔ Correcto |
| F06 | Formular `Fe2O3` | Óxido férrico / Óxido de hierro (III) / Trióxido de dihierro | ✔ Correcto |
| F07 | Formular `fe2o3` (minúsculas) | "Compuesto no registrado" | ✔ Correcto |
| F08 | Nombrar "cloruro de sodio" y "lavandina" | NaCl y NaClO | ✔ Correcto |
| F09 | Nombrar un nombre inexistente | "Compuesto no registrado" | ✔ Correcto |
| F10 | Evaluación: elegir nivel | 10 preguntas al azar del banco del nivel | ✔ Correcto |
| F11 | Calificar evaluación con preguntas sin responder | Guarda la nota y muestra la revisión | ✔ Correcto |
| F12 | Historial con filtros por nivel y fechas | Lista filtrada y nota promedio | ✔ Correcto |
| F13 | Reporte PDF (estudiante y administrador) con FPDF | Descarga un PDF válido | ✔ Correcto |
| F14 | CRUD compuestos: registrar, listar, consultar, modificar, desactivar, activar | Operaciones correctas | ✔ Correcto |
| F15 | Compuesto desactivado | Ya no aparece en Formular ni Nombrar | ✔ Correcto |
| F16 | CRUD preguntas: registrar, listar, consultar, modificar, desactivar | Operaciones correctas | ✔ Correcto |
| F17 | Búsqueda y filtros de preguntas (nivel y tipo) | Lista filtrada | ✔ Correcto |
| F18 | Cambiar contraseña desde "Mi cuenta" | Ingresa con la nueva contraseña | ✔ Correcto |

## 2. Validaciones (JavaScript y PHP)
| N.º | Prueba | Resultado esperado | Resultado |
|---|---|---|---|
| V01 | Registro con contraseña corta o sin números y confirmación distinta | Mensajes de error, no se registra | ✔ Correcto |
| V02 | Registro con correo ya existente | "Ya existe una cuenta con ese correo" | ✔ Correcto |
| V03 | Compuesto con fórmula repetida | "Ya existe un compuesto con esa fórmula" | ✔ Correcto |
| V04 | Compuesto con caracteres inválidos, sin nombre o tipo inexistente | Mensajes de error | ✔ Correcto |
| V05 | Pregunta con opciones repetidas o respuesta fuera de A–D | Mensajes de error | ✔ Correcto |
| V06 | Filtro de resultados con fecha inicial mayor que la final | "La fecha inicial no puede ser mayor…" | ✔ Correcto |
| V07 | Formulario enviado vacío (navegador) | Campos en rojo con su mensaje | ✔ Correcto |

## 3. Seguridad
| N.º | Prueba | Resultado esperado | Resultado |
|---|---|---|---|
| S01 | Entrar a una pantalla sin iniciar sesión | Redirige al inicio de sesión | ✔ Correcto |
| S02 | Estudiante intenta entrar al CRUD de compuestos | "No tiene permiso" | ✔ Correcto |
| S03 | Administrador intenta rendir una evaluación | "No tiene permiso" | ✔ Correcto |
| S04 | Estudiante intenta ver el resultado de otro estudiante | "No se encontró el resultado" | ✔ Correcto |
| S05 | Contraseña incorrecta | Mensaje genérico "Correo o contraseña incorrectos" | ✔ Correcto |
| S06 | 5 intentos fallidos seguidos | Cuenta bloqueada 5 minutos | ✔ Correcto |
| S07 | Formulario enviado sin token CSRF (desde otro sitio) | Rechazado: "El formulario expiró" | ✔ Correcto |
| S08 | Escribir `<script>` en Formular | Rechazado y escapado (sin ejecutar código) | ✔ Correcto |
| S09 | Cerrar sesión y volver atrás | No hay acceso | ✔ Correcto |
| S10 | Sesión inactiva más de 30 minutos | Se cierra automáticamente | ✔ Correcto |
| S11 | Cabeceras de seguridad (X-Frame-Options, X-Content-Type-Options) | Presentes | ✔ Correcto |
| S12 | Abrir `/config/` o `/sql/` desde el navegador | Acceso denegado (.htaccess de Apache) | Verificar en el sitio publicado |
| S13 | Ruta inexistente | Página 404 | ✔ Correcto |

Medidas de seguridad implementadas: contraseñas con `password_hash()` (bcrypt), sentencias preparadas PDO
(evitan la inyección SQL), `htmlspecialchars()` en todas las vistas (evita XSS), token CSRF en todos los
formularios POST, control de acceso por rol en cada controlador, regeneración del identificador de sesión al
iniciar y cerrar sesión, cookie de sesión `HttpOnly` y `SameSite`, bloqueo temporal por intentos fallidos,
cierre por inactividad, errores técnicos ocultos en el sitio publicado y credenciales de MySQL fuera del
repositorio (`config/credenciales.php` en `.gitignore`).

## 4. Usabilidad y diseño adaptable
| N.º | Prueba | Resultado esperado | Resultado |
|---|---|---|---|
| U01 | Pantallas en celular (375 px) | Una columna, menú ☰ desplegable, botones grandes | ✔ Correcto |
| U02 | Pantallas en computadora (1366 px) | Tarjetas en 3–4 columnas, menú completo | ✔ Correcto |
| U03 | Tablas del administrador en celular | Desplazamiento horizontal | ✔ Correcto |
| U04 | Mensajes de éxito y error | Claros y en español | ✔ Correcto |
| U05 | Evaluación | Una pregunta a la vez, barra de avance y aviso de preguntas sin responder | ✔ Correcto |

## 5. Entorno real (publicación)
| N.º | Prueba | Resultado esperado | Resultado |
|---|---|---|---|
| R01 | Sitio publicado en InfinityFree | Abre desde cualquier navegador con el enlace público | Completar tras publicar |
| R02 | Registro e inicio de sesión desde otro celular | Funciona | Completar tras publicar |
| R03 | Reporte PDF en el sitio publicado | Se descarga | Completar tras publicar |
