-- =====================================================================
-- Base de datos: nomenclatura_quimica
-- Proyecto: Nomenclatura Química Interactiva (Proyecto Final 2026)
-- Motor: MySQL / MariaDB (XAMPP) - InnoDB - utf8mb4
-- VERSIÓN PARA HOSTING (InfinityFree, ByetHost, AwardSpace…): no crea la base de datos.
-- 1) Cree la base en el panel del hosting. 2) Ábrala en phpMyAdmin. 3) Importe este archivo.
-- =====================================================================

SET NAMES utf8mb4;

-- Se eliminan las tablas en orden inverso a sus dependencias (permite volver a importar)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS detalle_evaluacion;
DROP TABLE IF EXISTS evaluaciones;
DROP TABLE IF EXISTS preguntas;
DROP TABLE IF EXISTS compuestos;
DROP TABLE IF EXISTS niveles;
DROP TABLE IF EXISTS tipos_compuesto;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS roles;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- Tabla roles
-- ---------------------------------------------------------------------
CREATE TABLE roles (
    id_rol  INT         NOT NULL AUTO_INCREMENT,
    nombre  VARCHAR(30) NOT NULL,
    PRIMARY KEY (id_rol),
    UNIQUE KEY uq_roles_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Tabla usuarios
-- ---------------------------------------------------------------------
CREATE TABLE usuarios (
    id_usuario      INT          NOT NULL AUTO_INCREMENT,
    nombres         VARCHAR(60)  NOT NULL,
    apellidos       VARCHAR(60)  NOT NULL,
    correo          VARCHAR(100) NOT NULL,
    contrasena      VARCHAR(255) NOT NULL COMMENT 'Hash generado con password_hash()',
    id_rol          INT          NOT NULL,
    estado          TINYINT(1)   NOT NULL DEFAULT 1 COMMENT '1 = activo, 0 = desactivado',
    intentos_fallidos TINYINT    NOT NULL DEFAULT 0 COMMENT 'Intentos de inicio de sesión fallidos seguidos',
    bloqueado_hasta DATETIME     NULL COMMENT 'Bloqueo temporal tras 5 intentos fallidos',
    fecha_registro  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_usuario),
    UNIQUE KEY uq_usuarios_correo (correo),
    KEY idx_usuarios_rol (id_rol),
    CONSTRAINT fk_usuarios_roles FOREIGN KEY (id_rol) REFERENCES roles (id_rol)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Tabla tipos_compuesto
-- ---------------------------------------------------------------------
CREATE TABLE tipos_compuesto (
    id_tipo      INT         NOT NULL AUTO_INCREMENT,
    nombre       VARCHAR(40) NOT NULL,
    descripcion  TEXT        NULL,
    PRIMARY KEY (id_tipo),
    UNIQUE KEY uq_tipos_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Tabla compuestos (catálogo + repositorio de compuestos cotidianos)
-- La fórmula usa colación binaria: distingue mayúsculas (CO no es lo mismo que Co)
-- ---------------------------------------------------------------------
CREATE TABLE compuestos (
    id_compuesto        INT          NOT NULL AUTO_INCREMENT,
    formula             VARCHAR(30)  CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    nombre_tradicional  VARCHAR(100) NOT NULL,
    nombre_stock        VARCHAR(100) NOT NULL,
    nombre_sistematico  VARCHAR(100) NOT NULL,
    nombre_comun        VARCHAR(80)  NULL,
    uso                 TEXT         NULL,
    cotidiano           TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '1 = aparece en el repositorio de la pantalla principal',
    id_tipo             INT          NOT NULL,
    estado              TINYINT(1)   NOT NULL DEFAULT 1 COMMENT '1 = activo, 0 = desactivado',
    PRIMARY KEY (id_compuesto),
    UNIQUE KEY uq_compuestos_formula (formula),
    KEY idx_compuestos_tipo (id_tipo),
    CONSTRAINT fk_compuestos_tipos FOREIGN KEY (id_tipo) REFERENCES tipos_compuesto (id_tipo)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Tabla niveles
-- ---------------------------------------------------------------------
CREATE TABLE niveles (
    id_nivel            INT          NOT NULL AUTO_INCREMENT,
    nombre              VARCHAR(20)  NOT NULL,
    descripcion         VARCHAR(150) NULL,
    cantidad_preguntas  TINYINT      NOT NULL DEFAULT 10,
    PRIMARY KEY (id_nivel),
    UNIQUE KEY uq_niveles_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Tabla preguntas
-- ---------------------------------------------------------------------
CREATE TABLE preguntas (
    id_pregunta         INT          NOT NULL AUTO_INCREMENT,
    id_nivel            INT          NOT NULL,
    id_tipo             INT          NOT NULL,
    enunciado           VARCHAR(255) NOT NULL,
    opcion_a            VARCHAR(100) NOT NULL,
    opcion_b            VARCHAR(100) NOT NULL,
    opcion_c            VARCHAR(100) NOT NULL,
    opcion_d            VARCHAR(100) NOT NULL,
    respuesta_correcta  CHAR(1)      NOT NULL,
    estado              TINYINT(1)   NOT NULL DEFAULT 1 COMMENT '1 = activa, 0 = desactivada',
    PRIMARY KEY (id_pregunta),
    KEY idx_preguntas_nivel (id_nivel),
    KEY idx_preguntas_tipo (id_tipo),
    CONSTRAINT fk_preguntas_niveles FOREIGN KEY (id_nivel) REFERENCES niveles (id_nivel)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_preguntas_tipos FOREIGN KEY (id_tipo) REFERENCES tipos_compuesto (id_tipo)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT ck_preguntas_respuesta CHECK (respuesta_correcta IN ('A', 'B', 'C', 'D'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Tabla evaluaciones
-- ---------------------------------------------------------------------
CREATE TABLE evaluaciones (
    id_evaluacion    INT           NOT NULL AUTO_INCREMENT,
    id_usuario       INT           NOT NULL,
    id_nivel         INT           NOT NULL,
    fecha            DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total_preguntas  TINYINT       NOT NULL,
    correctas        TINYINT       NOT NULL,
    nota             DECIMAL(5,2)  NOT NULL COMMENT 'correctas / total x 100',
    PRIMARY KEY (id_evaluacion),
    KEY idx_evaluaciones_usuario (id_usuario),
    KEY idx_evaluaciones_nivel (id_nivel),
    CONSTRAINT fk_evaluaciones_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_evaluaciones_niveles FOREIGN KEY (id_nivel) REFERENCES niveles (id_nivel)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT ck_evaluaciones_nota CHECK (nota >= 0 AND nota <= 100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Tabla detalle_evaluacion (resuelve la relación N:M evaluaciones - preguntas)
-- ---------------------------------------------------------------------
CREATE TABLE detalle_evaluacion (
    id_detalle         INT        NOT NULL AUTO_INCREMENT,
    id_evaluacion      INT        NOT NULL,
    id_pregunta        INT        NOT NULL,
    respuesta_usuario  CHAR(1)    NULL COMMENT 'NULL si no respondió',
    es_correcta        TINYINT(1) NOT NULL,
    PRIMARY KEY (id_detalle),
    KEY idx_detalle_evaluacion (id_evaluacion),
    KEY idx_detalle_pregunta (id_pregunta),
    CONSTRAINT fk_detalle_evaluaciones FOREIGN KEY (id_evaluacion) REFERENCES evaluaciones (id_evaluacion)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detalle_preguntas FOREIGN KEY (id_pregunta) REFERENCES preguntas (id_pregunta)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- DATOS INICIALES
-- (los identificadores se indican de forma explícita para que las claves
--  foráneas de los datos de ejemplo sean fáciles de leer)
-- =====================================================================

INSERT INTO roles (id_rol, nombre) VALUES
(1, 'Administrador'),
(2, 'Usuario');

-- Cuentas de prueba (las contraseñas se guardan cifradas con password_hash / bcrypt)
--   Administrador: admin@nomenclatura.local      Contraseña: Admin123*
--   Estudiante:    estudiante@nomenclatura.local Contraseña: Estudiante123
INSERT INTO usuarios (nombres, apellidos, correo, contrasena, id_rol) VALUES
('Administrador', 'del Sistema', 'admin@nomenclatura.local',
 '$2y$10$vlVHzoRqRfWBye8H20JDruJoIrypTfdQ0ankSEutv.NuLS1DJ1gtS', 1),
('Estudiante', 'de Prueba', 'estudiante@nomenclatura.local',
 '$2y$10$.DT87A1f7rH5baxHmPdKHujzwe4kGG7rDKskYEXHSUXiD6JGS1qRG', 2);

INSERT INTO tipos_compuesto (id_tipo, nombre, descripcion) VALUES
(1, 'Óxidos',        'Compuestos binarios formados por un elemento y oxígeno (óxidos básicos y anhídridos).'),
(2, 'Hidruros',      'Compuestos binarios formados por un elemento e hidrógeno.'),
(3, 'Hidróxidos',    'Compuestos formados por un metal y el grupo hidroxilo (OH).'),
(4, 'Ácidos',        'Compuestos que liberan hidrógeno en disolución acuosa (hidrácidos y oxácidos).'),
(5, 'Sales neutras', 'Sales en las que se reemplazaron todos los hidrógenos del ácido.'),
(6, 'Sales ácidas',  'Sales que conservan hidrógenos del ácido.'),
(7, 'Sales básicas', 'Sales que conservan grupos hidroxilo (OH) del hidróxido.'),
(8, 'Sales mixtas',  'Sales con dos cationes o dos aniones distintos.');

INSERT INTO niveles (id_nivel, nombre, descripcion, cantidad_preguntas) VALUES
(1, 'Básico',     'Óxidos, hidruros e hidróxidos: reconocer fórmulas y nombres sencillos.', 10),
(2, 'Intermedio', 'Valencias, nomenclatura Stock y tradicional, hidróxidos y ácidos.',      10),
(3, 'Avanzado',   'Sales neutras, ácidas, básicas y mixtas, y nomenclatura sistemática.',   10);

-- ---------------------------------------------------------------------
-- Catálogo de compuestos (Formular, Nombrar y repositorio de la pantalla principal)
-- cotidiano = 1: aparece en la pantalla principal con su nombre común y su uso.
-- Datos de ejemplo: revisarlos y ampliarlos con la docente de Química.
-- id_tipo: 1 Óxidos, 2 Hidruros, 3 Hidróxidos, 4 Ácidos, 5 Sales neutras,
--          6 Sales ácidas, 7 Sales básicas, 8 Sales mixtas
-- ---------------------------------------------------------------------
INSERT INTO compuestos
(formula, nombre_tradicional, nombre_stock, nombre_sistematico, nombre_comun, uso, cotidiano, id_tipo) VALUES
-- Óxidos básicos
('Na2O',   'Óxido sódico',          'Óxido de sodio',          'Monóxido de disodio',     NULL, NULL, 0, 1),
('K2O',    'Óxido potásico',        'Óxido de potasio',        'Monóxido de dipotasio',   NULL, NULL, 0, 1),
('CaO',    'Óxido cálcico',         'Óxido de calcio',         'Monóxido de calcio',      'Cal viva', 'Se usa en la construcción y para preparar la cal apagada.', 1, 1),
('MgO',    'Óxido magnésico',       'Óxido de magnesio',       'Monóxido de magnesio',    'Magnesia', 'Se usa como antiácido y en materiales resistentes al calor.', 1, 1),
('FeO',    'Óxido ferroso',         'Óxido de hierro (II)',    'Monóxido de hierro',      NULL, NULL, 0, 1),
('Fe2O3',  'Óxido férrico',         'Óxido de hierro (III)',   'Trióxido de dihierro',    'Herrumbre', 'Es la capa rojiza que se forma cuando el hierro se oxida; también se usa como pigmento.', 1, 1),
('Cu2O',   'Óxido cuproso',         'Óxido de cobre (I)',      'Monóxido de dicobre',     NULL, NULL, 0, 1),
('CuO',    'Óxido cúprico',         'Óxido de cobre (II)',     'Monóxido de cobre',       NULL, NULL, 0, 1),
('Al2O3',  'Óxido alumínico',       'Óxido de aluminio',       'Trióxido de dialuminio',  'Alúmina', 'Se usa en lijas, abrasivos y para obtener aluminio.', 1, 1),
('ZnO',    'Óxido cíncico',         'Óxido de zinc',           'Monóxido de zinc',        'Blanco de zinc', 'Se usa en cremas para la piel, protectores solares y pinturas.', 1, 1),
-- Óxidos ácidos (anhídridos)
('CO',     'Anhídrido carbonoso',   'Óxido de carbono (II)',   'Monóxido de carbono',     NULL, 'Gas tóxico que se produce en la combustión incompleta.', 0, 1),
('CO2',    'Anhídrido carbónico',   'Óxido de carbono (IV)',   'Dióxido de carbono',      'Gas carbónico', 'Está en las bebidas gaseosas y lo exhalamos al respirar.', 1, 1),
('SO2',    'Anhídrido sulfuroso',   'Óxido de azufre (IV)',    'Dióxido de azufre',       NULL, NULL, 0, 1),
('SO3',    'Anhídrido sulfúrico',   'Óxido de azufre (VI)',    'Trióxido de azufre',      NULL, NULL, 0, 1),
('N2O5',   'Anhídrido nítrico',     'Óxido de nitrógeno (V)',  'Pentóxido de dinitrógeno', NULL, NULL, 0, 1),
('Cl2O',   'Anhídrido hipocloroso', 'Óxido de cloro (I)',      'Monóxido de dicloro',     NULL, NULL, 0, 1),
('Cl2O7',  'Anhídrido perclórico',  'Óxido de cloro (VII)',    'Heptóxido de dicloro',    NULL, NULL, 0, 1),
('SiO2',   'Anhídrido silícico',    'Óxido de silicio (IV)',   'Dióxido de silicio',      'Sílice', 'Es el componente principal de la arena y se usa para fabricar vidrio.', 1, 1),
('P2O5',   'Anhídrido fosfórico',   'Óxido de fósforo (V)',    'Pentóxido de difósforo',  NULL, NULL, 0, 1),
-- Hidruros
('NaH',    'Hidruro sódico',        'Hidruro de sodio',        'Monohidruro de sodio',    NULL, NULL, 0, 2),
('CaH2',   'Hidruro cálcico',       'Hidruro de calcio',       'Dihidruro de calcio',     NULL, NULL, 0, 2),
('FeH2',   'Hidruro ferroso',       'Hidruro de hierro (II)',  'Dihidruro de hierro',     NULL, NULL, 0, 2),
('FeH3',   'Hidruro férrico',       'Hidruro de hierro (III)', 'Trihidruro de hierro',    NULL, NULL, 0, 2),
('NH3',    'Amoníaco',              'Hidruro de nitrógeno (III)', 'Trihidruro de nitrógeno', 'Amoníaco', 'Se usa en productos de limpieza y en la fabricación de fertilizantes.', 1, 2),
('CH4',    'Metano',                'Hidruro de carbono (IV)', 'Tetrahidruro de carbono', 'Gas natural', 'Es el combustible del gas domiciliario que se usa para cocinar.', 1, 2),
('PH3',    'Fosfina',               'Hidruro de fósforo (III)', 'Trihidruro de fósforo',  NULL, NULL, 0, 2),
-- Hidróxidos
('NaOH',   'Hidróxido sódico',      'Hidróxido de sodio',      'Monohidróxido de sodio',  'Soda cáustica', 'Se usa para fabricar jabón y destapar cañerías.', 1, 3),
('KOH',    'Hidróxido potásico',    'Hidróxido de potasio',    'Monohidróxido de potasio', 'Potasa cáustica', 'Se usa para fabricar jabones blandos y detergentes.', 1, 3),
('Ca(OH)2','Hidróxido cálcico',     'Hidróxido de calcio',     'Dihidróxido de calcio',   'Cal apagada', 'Se usa en la construcción, para blanquear paredes y para preparar el mote.', 1, 3),
('Mg(OH)2','Hidróxido magnésico',   'Hidróxido de magnesio',   'Dihidróxido de magnesio', 'Leche de magnesia', 'Se usa como antiácido y laxante.', 1, 3),
('Al(OH)3','Hidróxido alumínico',   'Hidróxido de aluminio',   'Trihidróxido de aluminio', NULL, 'Se usa en medicamentos antiácidos.', 0, 3),
('CuOH',   'Hidróxido cuproso',     'Hidróxido de cobre (I)',  'Monohidróxido de cobre',  NULL, NULL, 0, 3),
('Cu(OH)2','Hidróxido cúprico',     'Hidróxido de cobre (II)', 'Dihidróxido de cobre',    NULL, NULL, 0, 3),
('Fe(OH)2','Hidróxido ferroso',     'Hidróxido de hierro (II)', 'Dihidróxido de hierro',  NULL, NULL, 0, 3),
('Fe(OH)3','Hidróxido férrico',     'Hidróxido de hierro (III)', 'Trihidróxido de hierro', NULL, NULL, 0, 3),
-- Ácidos: hidrácidos
('HCl',    'Ácido clorhídrico',     'Cloruro de hidrógeno',    'Cloruro de hidrógeno',    'Ácido muriático', 'Se usa para limpiar superficies y eliminar sarro.', 1, 4),
('HF',     'Ácido fluorhídrico',    'Fluoruro de hidrógeno',   'Fluoruro de hidrógeno',   NULL, NULL, 0, 4),
('HBr',    'Ácido bromhídrico',     'Bromuro de hidrógeno',    'Bromuro de hidrógeno',    NULL, NULL, 0, 4),
('H2S',    'Ácido sulfhídrico',     'Sulfuro de hidrógeno',    'Sulfuro de dihidrógeno',  NULL, 'Gas con olor a huevo podrido.', 0, 4),
-- Ácidos: oxácidos
('H2CO3',  'Ácido carbónico',       'Ácido trioxocarbónico (IV)', 'Trioxocarbonato (IV) de hidrógeno', NULL, 'Se forma al disolver gas carbónico en agua (bebidas gaseosas).', 0, 4),
('H2SO3',  'Ácido sulfuroso',       'Ácido trioxosulfúrico (IV)', 'Trioxosulfato (IV) de hidrógeno',   NULL, NULL, 0, 4),
('H2SO4',  'Ácido sulfúrico',       'Ácido tetraoxosulfúrico (VI)', 'Tetraoxosulfato (VI) de hidrógeno', 'Ácido de batería', 'Se usa en las baterías de los autos y en la industria.', 1, 4),
('HNO2',   'Ácido nitroso',         'Ácido dioxonítrico (III)', 'Dioxonitrato (III) de hidrógeno',    NULL, NULL, 0, 4),
('HNO3',   'Ácido nítrico',         'Ácido trioxonítrico (V)', 'Trioxonitrato (V) de hidrógeno',      NULL, 'Se usa para fabricar fertilizantes.', 0, 4),
('HClO',   'Ácido hipocloroso',     'Ácido oxoclórico (I)',    'Oxoclorato (I) de hidrógeno',         NULL, NULL, 0, 4),
('HClO4',  'Ácido perclórico',      'Ácido tetraoxoclórico (VII)', 'Tetraoxoclorato (VII) de hidrógeno', NULL, NULL, 0, 4),
('H3PO4',  'Ácido fosfórico',       'Ácido tetraoxofosfórico (V)', 'Tetraoxofosfato (V) de hidrógeno', NULL, 'Se usa como acidulante en bebidas cola y en fertilizantes.', 0, 4),
-- Sales neutras
('NaCl',   'Cloruro sódico',        'Cloruro de sodio',        'Cloruro de sodio',        'Sal de mesa', 'Sirve para sazonar y conservar alimentos.', 1, 5),
('KCl',    'Cloruro potásico',      'Cloruro de potasio',      'Cloruro de potasio',      NULL, 'Se usa como sustituto de la sal y como fertilizante.', 0, 5),
('FeCl2',  'Cloruro ferroso',       'Cloruro de hierro (II)',  'Dicloruro de hierro',     NULL, NULL, 0, 5),
('FeCl3',  'Cloruro férrico',       'Cloruro de hierro (III)', 'Tricloruro de hierro',    NULL, 'Se usa para potabilizar agua.', 0, 5),
('FeS',    'Sulfuro ferroso',       'Sulfuro de hierro (II)',  'Monosulfuro de hierro',   NULL, NULL, 0, 5),
('CaCO3',  'Carbonato cálcico',     'Carbonato de calcio',     'Trioxocarbonato (IV) de calcio', 'Caliza', 'Está en la tiza, el mármol y la cáscara de huevo.', 1, 5),
('Na2CO3', 'Carbonato sódico',      'Carbonato de sodio',      'Trioxocarbonato (IV) de sodio',  'Soda Solvay', 'Se usa en detergentes y en la fabricación de vidrio.', 1, 5),
('CaSO4',  'Sulfato cálcico',       'Sulfato de calcio',       'Tetraoxosulfato (VI) de calcio', 'Yeso', 'Su forma hidratada se usa en la construcción y para inmovilizar fracturas.', 1, 5),
('CuSO4',  'Sulfato cúprico',       'Sulfato de cobre (II)',   'Tetraoxosulfato (VI) de cobre (II)', 'Vitriolo azul', 'Se usa como fungicida en la agricultura y para limpiar piscinas.', 1, 5),
('KNO3',   'Nitrato potásico',      'Nitrato de potasio',      'Trioxonitrato (V) de potasio',   'Salitre', 'Se usa como fertilizante y para conservar carnes.', 1, 5),
('NaClO',  'Hipoclorito sódico',    'Hipoclorito de sodio',    'Oxoclorato (I) de sodio',        'Lavandina', 'Se usa para desinfectar el agua, blanquear la ropa y limpiar.', 1, 5),
('KMnO4',  'Permanganato potásico', 'Permanganato de potasio', 'Tetraoxomanganato (VII) de potasio', 'Permanganato', 'Se usa como desinfectante para lavar frutas y verduras.', 1, 5),
('Ca3(PO4)2', 'Fosfato cálcico',    'Fosfato de calcio',       'Tetraoxofosfato (V) de calcio',  NULL, 'Es el principal componente mineral de los huesos.', 0, 5),
-- Sales ácidas
('NaHCO3', 'Carbonato ácido de sodio', 'Hidrogenocarbonato de sodio', 'Hidrogenotrioxocarbonato (IV) de sodio', 'Bicarbonato', 'Se usa en repostería y como antiácido casero.', 1, 6),
('KHCO3',  'Carbonato ácido de potasio', 'Hidrogenocarbonato de potasio', 'Hidrogenotrioxocarbonato (IV) de potasio', NULL, NULL, 0, 6),
('NaHSO4', 'Sulfato ácido de sodio', 'Hidrogenosulfato de sodio', 'Hidrogenotetraoxosulfato (VI) de sodio', NULL, NULL, 0, 6),
('NaH2PO4','Fosfato diácido de sodio', 'Dihidrogenofosfato de sodio', 'Dihidrogenotetraoxofosfato (V) de sodio', NULL, NULL, 0, 6),
-- Sales básicas
('Mg(OH)Cl',  'Cloruro básico de magnesio', 'Hidroxicloruro de magnesio', 'Hidroxicloruro de magnesio', NULL, NULL, 0, 7),
('Ca(OH)NO3', 'Nitrato básico de calcio',   'Hidroxinitrato de calcio',   'Hidroxitrioxonitrato (V) de calcio', NULL, NULL, 0, 7),
('Al(OH)SO4', 'Sulfato básico de aluminio', 'Hidroxisulfato de aluminio', 'Hidroxitetraoxosulfato (VI) de aluminio', NULL, NULL, 0, 7),
-- Sales mixtas
('KNaSO4',    'Sulfato sódico potásico',    'Sulfato de potasio y sodio', 'Tetraoxosulfato (VI) de potasio y sodio', NULL, NULL, 0, 8),
('KNaCO3',    'Carbonato sódico potásico',  'Carbonato de potasio y sodio', 'Trioxocarbonato (IV) de potasio y sodio', NULL, NULL, 0, 8),
('KAl(SO4)2', 'Sulfato alumínico potásico', 'Sulfato de aluminio y potasio', 'Disulfato de aluminio y potasio', 'Alumbre', 'Se usa para aclarar el agua turbia y como desodorante natural.', 1, 8);

-- ---------------------------------------------------------------------
-- Banco de preguntas inicial: 12 por nivel (cada evaluación elige 10 al azar)
-- id_nivel: 1 Básico, 2 Intermedio, 3 Avanzado
-- ---------------------------------------------------------------------
INSERT INTO preguntas
(id_nivel, id_tipo, enunciado, opcion_a, opcion_b, opcion_c, opcion_d, respuesta_correcta) VALUES
-- Nivel Básico
(1, 1, '¿Cuál es el nombre tradicional del compuesto Na2O?', 'Óxido sódico', 'Hidróxido de sodio', 'Peróxido de sodio', 'Cloruro de sodio', 'A'),
(1, 1, '¿Cuál es la fórmula del óxido de calcio?', 'CaO2', 'CaO', 'Ca2O', 'CaOH', 'B'),
(1, 1, 'En nomenclatura sistemática, el CO2 se llama:', 'Monóxido de carbono', 'Dióxido de carbono', 'Óxido de carbono (II)', 'Trióxido de carbono', 'B'),
(1, 1, '¿Qué elementos forman un óxido?', 'Un metal e hidrógeno', 'Un metal y el grupo OH', 'Un elemento y oxígeno', 'Un no metal e hidrógeno', 'C'),
(1, 3, '¿Qué grupo caracteriza a los hidróxidos?', 'El ion H+', 'El grupo CO3', 'El grupo OH', 'El oxígeno O2', 'C'),
(1, 2, '¿Cuál es la fórmula del hidruro de sodio?', 'NaOH', 'Na2O', 'NaH2', 'NaH', 'D'),
(1, 1, '¿Cuál es el nombre común del CaO?', 'Cal viva', 'Cal apagada', 'Yeso', 'Caliza', 'A'),
(1, 3, '¿Cuál es la fórmula del hidróxido de potasio?', 'KOH', 'K2O', 'KH', 'K(OH)2', 'A'),
(1, 1, '¿Con qué número de oxidación actúa el oxígeno en los óxidos?', '-1', '-2', '+2', '+1', 'B'),
(1, 5, 'El NaCl se conoce comúnmente como:', 'Bicarbonato', 'Sal de mesa', 'Lavandina', 'Soda cáustica', 'B'),
(1, 1, 'En la nomenclatura tradicional, un óxido de un no metal se llama:', 'Hidruro', 'Hidróxido', 'Anhídrido', 'Sal', 'C'),
(1, 2, '¿Cuál es el nombre del NH3?', 'Metano', 'Fosfina', 'Ácido nítrico', 'Amoníaco', 'D'),
-- Nivel Intermedio
(2, 3, '¿Cuál es la fórmula del hidróxido de sodio?', 'NaO', 'NaH', 'NaOH', 'Na2OH', 'C'),
(2, 3, '¿Cómo se llama Mg(OH)2 en nomenclatura sistemática?', 'Hidróxido de magnesio (II)', 'Dihidróxido de magnesio', 'Hidruro de magnesio', 'Óxido de magnesio', 'B'),
(2, 4, '¿Cuál es el nombre tradicional del HCl en disolución acuosa?', 'Ácido clorhídrico', 'Ácido hipocloroso', 'Ácido clórico', 'Ácido perclórico', 'A'),
(2, 1, '¿Cuál es el nombre Stock del FeO?', 'Óxido de hierro (III)', 'Óxido de hierro (II)', 'Óxido férrico', 'Trióxido de dihierro', 'B'),
(2, 1, '¿Cuál es el nombre tradicional del Cu2O?', 'Óxido cúprico', 'Óxido de cobre', 'Óxido cuproso', 'Peróxido de cobre', 'C'),
(2, 4, '¿Cuál es la fórmula del ácido sulfúrico?', 'H2SO3', 'H2S', 'H2SO4', 'HSO4', 'C'),
(2, 3, '¿Qué compuesto se forma cuando un óxido básico reacciona con agua?', 'Un ácido oxácido', 'Un hidróxido', 'Una sal', 'Un hidruro', 'B'),
(2, 4, '¿Qué compuesto se forma cuando un anhídrido reacciona con agua?', 'Un hidróxido', 'Una sal neutra', 'Un ácido oxácido', 'Un óxido básico', 'C'),
(2, 4, '¿Cuál es el nombre tradicional del HNO3?', 'Ácido nítrico', 'Ácido nitroso', 'Ácido hiponitroso', 'Ácido pernítrico', 'A'),
(2, 4, '¿Cuál es la fórmula del ácido sulfhídrico?', 'H2SO4', 'HS', 'H2SO3', 'H2S', 'D'),
(2, 1, '¿Con qué valencia actúa el hierro en el Fe2O3?', '2', '3', '6', '1', 'B'),
(2, 3, '¿Cuál es el nombre Stock del Fe(OH)3?', 'Hidróxido ferroso', 'Hidróxido de hierro (II)', 'Trihidróxido de hierro', 'Hidróxido de hierro (III)', 'D'),
-- Nivel Avanzado
(3, 5, '¿Cuál es la fórmula del cloruro de sodio?', 'NaCl', 'NaClO', 'Na2Cl', 'NaCl2', 'A'),
(3, 1, '¿Cuál es el nombre Stock del Fe2O3?', 'Óxido de hierro (II)', 'Óxido de hierro (III)', 'Trióxido de dihierro', 'Óxido férrico', 'B'),
(3, 6, 'El bicarbonato de sodio (NaHCO3) es una sal:', 'Neutra', 'Ácida', 'Básica', 'Mixta', 'B'),
(3, 5, '¿Cuál es el nombre tradicional del NaClO (lavandina)?', 'Clorito sódico', 'Clorato sódico', 'Hipoclorito sódico', 'Perclorato sódico', 'C'),
(3, 5, 'Cuando un ácido reacciona con un hidróxido se forma:', 'Óxido y agua', 'Hidruro y oxígeno', 'Ácido y sal', 'Sal y agua', 'D'),
(3, 5, 'Una sal terminada en “-ato” proviene de un ácido terminado en:', '-ico', '-oso', '-hídrico', '-ito', 'A'),
(3, 5, '¿Cuál es la fórmula del carbonato de calcio?', 'CaCO2', 'Ca2CO3', 'CaCO3', 'Ca(CO3)2', 'C'),
(3, 4, '¿Cuál es el nombre sistemático del H2SO4?', 'Trioxosulfato (IV) de hidrógeno', 'Tetraoxosulfato (VI) de hidrógeno', 'Sulfuro de hidrógeno', 'Tetraoxosulfato (IV) de hidrógeno', 'B'),
(3, 7, 'El compuesto Mg(OH)Cl es una sal:', 'Neutra', 'Ácida', 'Mixta', 'Básica', 'D'),
(3, 8, 'El alumbre, KAl(SO4)2, es una sal:', 'Mixta', 'Básica', 'Ácida', 'Neutra', 'A'),
(3, 5, '¿Cuál es el nombre Stock del CuSO4?', 'Sulfato de cobre (I)', 'Sulfito de cobre (II)', 'Sulfato de cobre (II)', 'Sulfuro de cobre (II)', 'C'),
(3, 4, '¿Cuál es la fórmula del ácido perclórico?', 'HClO', 'HClO3', 'HClO2', 'HClO4', 'D');

-- ---------------------------------------------------------------------
-- Catálogo ampliado (generado con las reglas de nomenclatura de cada tipo).
-- Óxidos, hidruros, hidróxidos, ácidos y sales neutras de los metales y no metales más usados.
-- ---------------------------------------------------------------------
INSERT INTO compuestos
(formula, nombre_tradicional, nombre_stock, nombre_sistematico, nombre_comun, uso, cotidiano, id_tipo) VALUES
('Li2O', 'Óxido lítico', 'Óxido de litio', 'Monóxido de dilitio', NULL, NULL, 0, 1),
('LiOH', 'Hidróxido lítico', 'Hidróxido de litio', 'Monohidróxido de litio', NULL, NULL, 0, 3),
('LiH', 'Hidruro lítico', 'Hidruro de litio', 'Monohidruro de litio', NULL, NULL, 0, 2),
('KH', 'Hidruro potásico', 'Hidruro de potasio', 'Monohidruro de potasio', NULL, NULL, 0, 2),
('Ag2O', 'Óxido argéntico', 'Óxido de plata', 'Monóxido de diplata', NULL, NULL, 0, 1),
('AgOH', 'Hidróxido argéntico', 'Hidróxido de plata', 'Monohidróxido de plata', NULL, NULL, 0, 3),
('MgH2', 'Hidruro magnésico', 'Hidruro de magnesio', 'Dihidruro de magnesio', NULL, NULL, 0, 2),
('BaO', 'Óxido bárico', 'Óxido de bario', 'Monóxido de bario', NULL, NULL, 0, 1),
('Ba(OH)2', 'Hidróxido bárico', 'Hidróxido de bario', 'Dihidróxido de bario', NULL, NULL, 0, 3),
('BaH2', 'Hidruro bárico', 'Hidruro de bario', 'Dihidruro de bario', NULL, NULL, 0, 2),
('Zn(OH)2', 'Hidróxido cíncico', 'Hidróxido de zinc', 'Dihidróxido de zinc', NULL, NULL, 0, 3),
('ZnH2', 'Hidruro cíncico', 'Hidruro de zinc', 'Dihidruro de zinc', NULL, NULL, 0, 2),
('AlH3', 'Hidruro alumínico', 'Hidruro de aluminio', 'Trihidruro de aluminio', NULL, NULL, 0, 2),
('CuH', 'Hidruro cuproso', 'Hidruro de cobre (I)', 'Monohidruro de cobre', NULL, NULL, 0, 2),
('CuH2', 'Hidruro cúprico', 'Hidruro de cobre (II)', 'Dihidruro de cobre', NULL, NULL, 0, 2),
('CoO', 'Óxido cobaltoso', 'Óxido de cobalto (II)', 'Monóxido de cobalto', NULL, NULL, 0, 1),
('Co(OH)2', 'Hidróxido cobaltoso', 'Hidróxido de cobalto (II)', 'Dihidróxido de cobalto', NULL, NULL, 0, 3),
('CoH2', 'Hidruro cobaltoso', 'Hidruro de cobalto (II)', 'Dihidruro de cobalto', NULL, NULL, 0, 2),
('Co2O3', 'Óxido cobáltico', 'Óxido de cobalto (III)', 'Trióxido de dicobalto', NULL, NULL, 0, 1),
('Co(OH)3', 'Hidróxido cobáltico', 'Hidróxido de cobalto (III)', 'Trihidróxido de cobalto', NULL, NULL, 0, 3),
('CoH3', 'Hidruro cobáltico', 'Hidruro de cobalto (III)', 'Trihidruro de cobalto', NULL, NULL, 0, 2),
('NiO', 'Óxido niqueloso', 'Óxido de níquel (II)', 'Monóxido de níquel', NULL, NULL, 0, 1),
('Ni(OH)2', 'Hidróxido niqueloso', 'Hidróxido de níquel (II)', 'Dihidróxido de níquel', NULL, NULL, 0, 3),
('NiH2', 'Hidruro niqueloso', 'Hidruro de níquel (II)', 'Dihidruro de níquel', NULL, NULL, 0, 2),
('Ni2O3', 'Óxido niquélico', 'Óxido de níquel (III)', 'Trióxido de diníquel', NULL, NULL, 0, 1),
('Ni(OH)3', 'Hidróxido niquélico', 'Hidróxido de níquel (III)', 'Trihidróxido de níquel', NULL, NULL, 0, 3),
('NiH3', 'Hidruro niquélico', 'Hidruro de níquel (III)', 'Trihidruro de níquel', NULL, NULL, 0, 2),
('SnO', 'Óxido estannoso', 'Óxido de estaño (II)', 'Monóxido de estaño', NULL, NULL, 0, 1),
('Sn(OH)2', 'Hidróxido estannoso', 'Hidróxido de estaño (II)', 'Dihidróxido de estaño', NULL, NULL, 0, 3),
('SnO2', 'Óxido estánnico', 'Óxido de estaño (IV)', 'Dióxido de estaño', NULL, NULL, 0, 1),
('Sn(OH)4', 'Hidróxido estánnico', 'Hidróxido de estaño (IV)', 'Tetrahidróxido de estaño', NULL, NULL, 0, 3),
('PbO', 'Óxido plumboso', 'Óxido de plomo (II)', 'Monóxido de plomo', NULL, NULL, 0, 1),
('Pb(OH)2', 'Hidróxido plumboso', 'Hidróxido de plomo (II)', 'Dihidróxido de plomo', NULL, NULL, 0, 3),
('PbO2', 'Óxido plúmbico', 'Óxido de plomo (IV)', 'Dióxido de plomo', NULL, NULL, 0, 1),
('Pb(OH)4', 'Hidróxido plúmbico', 'Hidróxido de plomo (IV)', 'Tetrahidróxido de plomo', NULL, NULL, 0, 3),
('Au2O', 'Óxido auroso', 'Óxido de oro (I)', 'Monóxido de dioro', NULL, NULL, 0, 1),
('AuOH', 'Hidróxido auroso', 'Hidróxido de oro (I)', 'Monohidróxido de oro', NULL, NULL, 0, 3),
('Au2O3', 'Óxido áurico', 'Óxido de oro (III)', 'Trióxido de dioro', NULL, NULL, 0, 1),
('Au(OH)3', 'Hidróxido áurico', 'Hidróxido de oro (III)', 'Trihidróxido de oro', NULL, NULL, 0, 3),
('Cl2O3', 'Anhídrido cloroso', 'Óxido de cloro (III)', 'Trióxido de dicloro', NULL, NULL, 0, 1),
('HClO2', 'Ácido cloroso', 'Ácido dioxoclórico (III)', 'Dioxoclorato (III) de hidrógeno', NULL, NULL, 0, 4),
('Cl2O5', 'Anhídrido clórico', 'Óxido de cloro (V)', 'Pentóxido de dicloro', NULL, NULL, 0, 1),
('HClO3', 'Ácido clórico', 'Ácido trioxoclórico (V)', 'Trioxoclorato (V) de hidrógeno', NULL, NULL, 0, 4),
('Br2O', 'Anhídrido hipobromoso', 'Óxido de bromo (I)', 'Monóxido de dibromo', NULL, NULL, 0, 1),
('HBrO', 'Ácido hipobromoso', 'Ácido oxobrómico (I)', 'Oxobromato (I) de hidrógeno', NULL, NULL, 0, 4),
('Br2O3', 'Anhídrido bromoso', 'Óxido de bromo (III)', 'Trióxido de dibromo', NULL, NULL, 0, 1),
('HBrO2', 'Ácido bromoso', 'Ácido dioxobrómico (III)', 'Dioxobromato (III) de hidrógeno', NULL, NULL, 0, 4),
('Br2O5', 'Anhídrido brómico', 'Óxido de bromo (V)', 'Pentóxido de dibromo', NULL, NULL, 0, 1),
('HBrO3', 'Ácido brómico', 'Ácido trioxobrómico (V)', 'Trioxobromato (V) de hidrógeno', NULL, NULL, 0, 4),
('Br2O7', 'Anhídrido perbrómico', 'Óxido de bromo (VII)', 'Heptóxido de dibromo', NULL, NULL, 0, 1),
('HBrO4', 'Ácido perbrómico', 'Ácido tetraoxobrómico (VII)', 'Tetraoxobromato (VII) de hidrógeno', NULL, NULL, 0, 4),
('I2O', 'Anhídrido hipoyodoso', 'Óxido de yodo (I)', 'Monóxido de diyodo', NULL, NULL, 0, 1),
('HIO', 'Ácido hipoyodoso', 'Ácido oxoyódico (I)', 'Oxoyodato (I) de hidrógeno', NULL, NULL, 0, 4),
('I2O3', 'Anhídrido yodoso', 'Óxido de yodo (III)', 'Trióxido de diyodo', NULL, NULL, 0, 1),
('HIO2', 'Ácido yodoso', 'Ácido dioxoyódico (III)', 'Dioxoyodato (III) de hidrógeno', NULL, NULL, 0, 4),
('I2O5', 'Anhídrido yódico', 'Óxido de yodo (V)', 'Pentóxido de diyodo', NULL, NULL, 0, 1),
('HIO3', 'Ácido yódico', 'Ácido trioxoyódico (V)', 'Trioxoyodato (V) de hidrógeno', NULL, NULL, 0, 4),
('I2O7', 'Anhídrido peryódico', 'Óxido de yodo (VII)', 'Heptóxido de diyodo', NULL, NULL, 0, 1),
('HIO4', 'Ácido peryódico', 'Ácido tetraoxoyódico (VII)', 'Tetraoxoyodato (VII) de hidrógeno', NULL, NULL, 0, 4),
('SeO2', 'Anhídrido selenoso', 'Óxido de selenio (IV)', 'Dióxido de selenio', NULL, NULL, 0, 1),
('H2SeO3', 'Ácido selenoso', 'Ácido trioxoselénico (IV)', 'Trioxoseleniato (IV) de hidrógeno', NULL, NULL, 0, 4),
('SeO3', 'Anhídrido selénico', 'Óxido de selenio (VI)', 'Trióxido de selenio', NULL, NULL, 0, 1),
('H2SeO4', 'Ácido selénico', 'Ácido tetraoxoselénico (VI)', 'Tetraoxoseleniato (VI) de hidrógeno', NULL, NULL, 0, 4),
('TeO2', 'Anhídrido teluroso', 'Óxido de teluro (IV)', 'Dióxido de teluro', NULL, NULL, 0, 1),
('H2TeO3', 'Ácido teluroso', 'Ácido trioxotelúrico (IV)', 'Trioxotelurato (IV) de hidrógeno', NULL, NULL, 0, 4),
('TeO3', 'Anhídrido telúrico', 'Óxido de teluro (VI)', 'Trióxido de teluro', NULL, NULL, 0, 1),
('H2TeO4', 'Ácido telúrico', 'Ácido tetraoxotelúrico (VI)', 'Tetraoxotelurato (VI) de hidrógeno', NULL, NULL, 0, 4),
('N2O3', 'Anhídrido nitroso', 'Óxido de nitrógeno (III)', 'Trióxido de dinitrógeno', NULL, NULL, 0, 1),
('P2O3', 'Anhídrido fosforoso', 'Óxido de fósforo (III)', 'Trióxido de difósforo', NULL, NULL, 0, 1),
('H3PO3', 'Ácido fosforoso', 'Ácido trioxofosfórico (III)', 'Trioxofosfato (III) de hidrógeno', NULL, NULL, 0, 4),
('HI', 'Ácido yodhídrico', 'Yoduro de hidrógeno', 'Yoduro de hidrógeno', NULL, NULL, 0, 4),
('H2Se', 'Ácido selenhídrico', 'Seleniuro de hidrógeno', 'Seleniuro de dihidrógeno', NULL, NULL, 0, 4),
('H2Te', 'Ácido telurhídrico', 'Telururo de hidrógeno', 'Telururo de dihidrógeno', NULL, NULL, 0, 4),
('NaBr', 'Bromuro sódico', 'Bromuro de sodio', 'Bromuro de sodio', NULL, NULL, 0, 5),
('NaI', 'Yoduro sódico', 'Yoduro de sodio', 'Yoduro de sodio', NULL, NULL, 0, 5),
('NaF', 'Fluoruro sódico', 'Fluoruro de sodio', 'Fluoruro de sodio', NULL, NULL, 0, 5),
('Na2S', 'Sulfuro sódico', 'Sulfuro de sodio', 'Monosulfuro de disodio', NULL, NULL, 0, 5),
('NaNO3', 'Nitrato sódico', 'Nitrato de sodio', 'Trioxonitrato (V) de sodio', NULL, NULL, 0, 5),
('NaNO2', 'Nitrito sódico', 'Nitrito de sodio', 'Dioxonitrato (III) de sodio', NULL, NULL, 0, 5),
('Na2SO4', 'Sulfato sódico', 'Sulfato de sodio', 'Tetraoxosulfato (VI) de sodio', NULL, NULL, 0, 5),
('Na2SO3', 'Sulfito sódico', 'Sulfito de sodio', 'Trioxosulfato (IV) de sodio', NULL, NULL, 0, 5),
('Na3PO4', 'Fosfato sódico', 'Fosfato de sodio', 'Tetraoxofosfato (V) de sodio', NULL, NULL, 0, 5),
('NaClO3', 'Clorato sódico', 'Clorato de sodio', 'Trioxoclorato (V) de sodio', NULL, NULL, 0, 5),
('NaClO4', 'Perclorato sódico', 'Perclorato de sodio', 'Tetraoxoclorato (VII) de sodio', NULL, NULL, 0, 5),
('NaBrO3', 'Bromato sódico', 'Bromato de sodio', 'Trioxobromato (V) de sodio', NULL, NULL, 0, 5),
('NaIO3', 'Yodato sódico', 'Yodato de sodio', 'Trioxoyodato (V) de sodio', NULL, NULL, 0, 5),
('KBr', 'Bromuro potásico', 'Bromuro de potasio', 'Bromuro de potasio', NULL, NULL, 0, 5),
('KI', 'Yoduro potásico', 'Yoduro de potasio', 'Yoduro de potasio', NULL, NULL, 0, 5),
('KF', 'Fluoruro potásico', 'Fluoruro de potasio', 'Fluoruro de potasio', NULL, NULL, 0, 5),
('K2S', 'Sulfuro potásico', 'Sulfuro de potasio', 'Monosulfuro de dipotasio', NULL, NULL, 0, 5),
('KNO2', 'Nitrito potásico', 'Nitrito de potasio', 'Dioxonitrato (III) de potasio', NULL, NULL, 0, 5),
('K2SO4', 'Sulfato potásico', 'Sulfato de potasio', 'Tetraoxosulfato (VI) de potasio', NULL, NULL, 0, 5),
('K2SO3', 'Sulfito potásico', 'Sulfito de potasio', 'Trioxosulfato (IV) de potasio', NULL, NULL, 0, 5),
('K2CO3', 'Carbonato potásico', 'Carbonato de potasio', 'Trioxocarbonato (IV) de potasio', NULL, NULL, 0, 5),
('K3PO4', 'Fosfato potásico', 'Fosfato de potasio', 'Tetraoxofosfato (V) de potasio', NULL, NULL, 0, 5),
('KClO', 'Hipoclorito potásico', 'Hipoclorito de potasio', 'Oxoclorato (I) de potasio', NULL, NULL, 0, 5),
('KClO3', 'Clorato potásico', 'Clorato de potasio', 'Trioxoclorato (V) de potasio', NULL, NULL, 0, 5),
('KClO4', 'Perclorato potásico', 'Perclorato de potasio', 'Tetraoxoclorato (VII) de potasio', NULL, NULL, 0, 5),
('KBrO3', 'Bromato potásico', 'Bromato de potasio', 'Trioxobromato (V) de potasio', NULL, NULL, 0, 5),
('KIO3', 'Yodato potásico', 'Yodato de potasio', 'Trioxoyodato (V) de potasio', NULL, NULL, 0, 5),
('AgCl', 'Cloruro argéntico', 'Cloruro de plata', 'Cloruro de plata', NULL, NULL, 0, 5),
('AgBr', 'Bromuro argéntico', 'Bromuro de plata', 'Bromuro de plata', NULL, NULL, 0, 5),
('AgI', 'Yoduro argéntico', 'Yoduro de plata', 'Yoduro de plata', NULL, NULL, 0, 5),
('AgF', 'Fluoruro argéntico', 'Fluoruro de plata', 'Fluoruro de plata', NULL, NULL, 0, 5),
('Ag2S', 'Sulfuro argéntico', 'Sulfuro de plata', 'Monosulfuro de diplata', NULL, NULL, 0, 5),
('AgNO3', 'Nitrato argéntico', 'Nitrato de plata', 'Trioxonitrato (V) de plata', NULL, NULL, 0, 5),
('AgNO2', 'Nitrito argéntico', 'Nitrito de plata', 'Dioxonitrato (III) de plata', NULL, NULL, 0, 5),
('Ag2SO4', 'Sulfato argéntico', 'Sulfato de plata', 'Tetraoxosulfato (VI) de plata', NULL, NULL, 0, 5),
('Ag2SO3', 'Sulfito argéntico', 'Sulfito de plata', 'Trioxosulfato (IV) de plata', NULL, NULL, 0, 5),
('Ag2CO3', 'Carbonato argéntico', 'Carbonato de plata', 'Trioxocarbonato (IV) de plata', NULL, NULL, 0, 5),
('Ag3PO4', 'Fosfato argéntico', 'Fosfato de plata', 'Tetraoxofosfato (V) de plata', NULL, NULL, 0, 5),
('AgClO', 'Hipoclorito argéntico', 'Hipoclorito de plata', 'Oxoclorato (I) de plata', NULL, NULL, 0, 5),
('AgClO3', 'Clorato argéntico', 'Clorato de plata', 'Trioxoclorato (V) de plata', NULL, NULL, 0, 5),
('AgClO4', 'Perclorato argéntico', 'Perclorato de plata', 'Tetraoxoclorato (VII) de plata', NULL, NULL, 0, 5),
('AgBrO3', 'Bromato argéntico', 'Bromato de plata', 'Trioxobromato (V) de plata', NULL, NULL, 0, 5),
('AgIO3', 'Yodato argéntico', 'Yodato de plata', 'Trioxoyodato (V) de plata', NULL, NULL, 0, 5),
('LiCl', 'Cloruro lítico', 'Cloruro de litio', 'Cloruro de litio', NULL, NULL, 0, 5),
('LiBr', 'Bromuro lítico', 'Bromuro de litio', 'Bromuro de litio', NULL, NULL, 0, 5),
('LiI', 'Yoduro lítico', 'Yoduro de litio', 'Yoduro de litio', NULL, NULL, 0, 5),
('LiF', 'Fluoruro lítico', 'Fluoruro de litio', 'Fluoruro de litio', NULL, NULL, 0, 5),
('Li2S', 'Sulfuro lítico', 'Sulfuro de litio', 'Monosulfuro de dilitio', NULL, NULL, 0, 5),
('LiNO3', 'Nitrato lítico', 'Nitrato de litio', 'Trioxonitrato (V) de litio', NULL, NULL, 0, 5),
('LiNO2', 'Nitrito lítico', 'Nitrito de litio', 'Dioxonitrato (III) de litio', NULL, NULL, 0, 5),
('Li2SO4', 'Sulfato lítico', 'Sulfato de litio', 'Tetraoxosulfato (VI) de litio', NULL, NULL, 0, 5),
('Li2SO3', 'Sulfito lítico', 'Sulfito de litio', 'Trioxosulfato (IV) de litio', NULL, NULL, 0, 5),
('Li2CO3', 'Carbonato lítico', 'Carbonato de litio', 'Trioxocarbonato (IV) de litio', NULL, NULL, 0, 5),
('Li3PO4', 'Fosfato lítico', 'Fosfato de litio', 'Tetraoxofosfato (V) de litio', NULL, NULL, 0, 5),
('LiClO', 'Hipoclorito lítico', 'Hipoclorito de litio', 'Oxoclorato (I) de litio', NULL, NULL, 0, 5),
('LiClO3', 'Clorato lítico', 'Clorato de litio', 'Trioxoclorato (V) de litio', NULL, NULL, 0, 5),
('LiClO4', 'Perclorato lítico', 'Perclorato de litio', 'Tetraoxoclorato (VII) de litio', NULL, NULL, 0, 5),
('LiBrO3', 'Bromato lítico', 'Bromato de litio', 'Trioxobromato (V) de litio', NULL, NULL, 0, 5),
('LiIO3', 'Yodato lítico', 'Yodato de litio', 'Trioxoyodato (V) de litio', NULL, NULL, 0, 5),
('MgCl2', 'Cloruro magnésico', 'Cloruro de magnesio', 'Dicloruro de magnesio', NULL, NULL, 0, 5),
('MgBr2', 'Bromuro magnésico', 'Bromuro de magnesio', 'Dibromuro de magnesio', NULL, NULL, 0, 5),
('MgI2', 'Yoduro magnésico', 'Yoduro de magnesio', 'Diyoduro de magnesio', NULL, NULL, 0, 5),
('MgF2', 'Fluoruro magnésico', 'Fluoruro de magnesio', 'Difluoruro de magnesio', NULL, NULL, 0, 5),
('MgS', 'Sulfuro magnésico', 'Sulfuro de magnesio', 'Sulfuro de magnesio', NULL, NULL, 0, 5),
('Mg(NO3)2', 'Nitrato magnésico', 'Nitrato de magnesio', 'Trioxonitrato (V) de magnesio', NULL, NULL, 0, 5),
('Mg(NO2)2', 'Nitrito magnésico', 'Nitrito de magnesio', 'Dioxonitrato (III) de magnesio', NULL, NULL, 0, 5),
('MgSO4', 'Sulfato magnésico', 'Sulfato de magnesio', 'Tetraoxosulfato (VI) de magnesio', NULL, NULL, 0, 5),
('MgSO3', 'Sulfito magnésico', 'Sulfito de magnesio', 'Trioxosulfato (IV) de magnesio', NULL, NULL, 0, 5),
('MgCO3', 'Carbonato magnésico', 'Carbonato de magnesio', 'Trioxocarbonato (IV) de magnesio', NULL, NULL, 0, 5),
('Mg3(PO4)2', 'Fosfato magnésico', 'Fosfato de magnesio', 'Tetraoxofosfato (V) de magnesio', NULL, NULL, 0, 5),
('Mg(ClO)2', 'Hipoclorito magnésico', 'Hipoclorito de magnesio', 'Oxoclorato (I) de magnesio', NULL, NULL, 0, 5),
('Mg(ClO3)2', 'Clorato magnésico', 'Clorato de magnesio', 'Trioxoclorato (V) de magnesio', NULL, NULL, 0, 5),
('Mg(ClO4)2', 'Perclorato magnésico', 'Perclorato de magnesio', 'Tetraoxoclorato (VII) de magnesio', NULL, NULL, 0, 5),
('Mg(BrO3)2', 'Bromato magnésico', 'Bromato de magnesio', 'Trioxobromato (V) de magnesio', NULL, NULL, 0, 5),
('Mg(IO3)2', 'Yodato magnésico', 'Yodato de magnesio', 'Trioxoyodato (V) de magnesio', NULL, NULL, 0, 5),
('CaCl2', 'Cloruro cálcico', 'Cloruro de calcio', 'Dicloruro de calcio', NULL, NULL, 0, 5),
('CaBr2', 'Bromuro cálcico', 'Bromuro de calcio', 'Dibromuro de calcio', NULL, NULL, 0, 5),
('CaI2', 'Yoduro cálcico', 'Yoduro de calcio', 'Diyoduro de calcio', NULL, NULL, 0, 5),
('CaF2', 'Fluoruro cálcico', 'Fluoruro de calcio', 'Difluoruro de calcio', NULL, NULL, 0, 5),
('CaS', 'Sulfuro cálcico', 'Sulfuro de calcio', 'Sulfuro de calcio', NULL, NULL, 0, 5),
('Ca(NO3)2', 'Nitrato cálcico', 'Nitrato de calcio', 'Trioxonitrato (V) de calcio', NULL, NULL, 0, 5),
('Ca(NO2)2', 'Nitrito cálcico', 'Nitrito de calcio', 'Dioxonitrato (III) de calcio', NULL, NULL, 0, 5),
('CaSO3', 'Sulfito cálcico', 'Sulfito de calcio', 'Trioxosulfato (IV) de calcio', NULL, NULL, 0, 5),
('Ca(ClO)2', 'Hipoclorito cálcico', 'Hipoclorito de calcio', 'Oxoclorato (I) de calcio', NULL, NULL, 0, 5),
('Ca(ClO3)2', 'Clorato cálcico', 'Clorato de calcio', 'Trioxoclorato (V) de calcio', NULL, NULL, 0, 5),
('Ca(ClO4)2', 'Perclorato cálcico', 'Perclorato de calcio', 'Tetraoxoclorato (VII) de calcio', NULL, NULL, 0, 5),
('Ca(BrO3)2', 'Bromato cálcico', 'Bromato de calcio', 'Trioxobromato (V) de calcio', NULL, NULL, 0, 5),
('Ca(IO3)2', 'Yodato cálcico', 'Yodato de calcio', 'Trioxoyodato (V) de calcio', NULL, NULL, 0, 5),
('BaCl2', 'Cloruro bárico', 'Cloruro de bario', 'Dicloruro de bario', NULL, NULL, 0, 5),
('BaBr2', 'Bromuro bárico', 'Bromuro de bario', 'Dibromuro de bario', NULL, NULL, 0, 5),
('BaI2', 'Yoduro bárico', 'Yoduro de bario', 'Diyoduro de bario', NULL, NULL, 0, 5),
('BaF2', 'Fluoruro bárico', 'Fluoruro de bario', 'Difluoruro de bario', NULL, NULL, 0, 5),
('BaS', 'Sulfuro bárico', 'Sulfuro de bario', 'Sulfuro de bario', NULL, NULL, 0, 5),
('Ba(NO3)2', 'Nitrato bárico', 'Nitrato de bario', 'Trioxonitrato (V) de bario', NULL, NULL, 0, 5),
('Ba(NO2)2', 'Nitrito bárico', 'Nitrito de bario', 'Dioxonitrato (III) de bario', NULL, NULL, 0, 5),
('BaSO4', 'Sulfato bárico', 'Sulfato de bario', 'Tetraoxosulfato (VI) de bario', NULL, NULL, 0, 5),
('BaSO3', 'Sulfito bárico', 'Sulfito de bario', 'Trioxosulfato (IV) de bario', NULL, NULL, 0, 5),
('BaCO3', 'Carbonato bárico', 'Carbonato de bario', 'Trioxocarbonato (IV) de bario', NULL, NULL, 0, 5),
('Ba3(PO4)2', 'Fosfato bárico', 'Fosfato de bario', 'Tetraoxofosfato (V) de bario', NULL, NULL, 0, 5),
('Ba(ClO)2', 'Hipoclorito bárico', 'Hipoclorito de bario', 'Oxoclorato (I) de bario', NULL, NULL, 0, 5),
('Ba(ClO3)2', 'Clorato bárico', 'Clorato de bario', 'Trioxoclorato (V) de bario', NULL, NULL, 0, 5),
('Ba(ClO4)2', 'Perclorato bárico', 'Perclorato de bario', 'Tetraoxoclorato (VII) de bario', NULL, NULL, 0, 5),
('Ba(BrO3)2', 'Bromato bárico', 'Bromato de bario', 'Trioxobromato (V) de bario', NULL, NULL, 0, 5),
('Ba(IO3)2', 'Yodato bárico', 'Yodato de bario', 'Trioxoyodato (V) de bario', NULL, NULL, 0, 5),
('ZnCl2', 'Cloruro cíncico', 'Cloruro de zinc', 'Dicloruro de zinc', NULL, NULL, 0, 5),
('ZnBr2', 'Bromuro cíncico', 'Bromuro de zinc', 'Dibromuro de zinc', NULL, NULL, 0, 5),
('ZnI2', 'Yoduro cíncico', 'Yoduro de zinc', 'Diyoduro de zinc', NULL, NULL, 0, 5),
('ZnF2', 'Fluoruro cíncico', 'Fluoruro de zinc', 'Difluoruro de zinc', NULL, NULL, 0, 5),
('ZnS', 'Sulfuro cíncico', 'Sulfuro de zinc', 'Sulfuro de zinc', NULL, NULL, 0, 5),
('Zn(NO3)2', 'Nitrato cíncico', 'Nitrato de zinc', 'Trioxonitrato (V) de zinc', NULL, NULL, 0, 5),
('Zn(NO2)2', 'Nitrito cíncico', 'Nitrito de zinc', 'Dioxonitrato (III) de zinc', NULL, NULL, 0, 5),
('ZnSO4', 'Sulfato cíncico', 'Sulfato de zinc', 'Tetraoxosulfato (VI) de zinc', NULL, NULL, 0, 5),
('ZnSO3', 'Sulfito cíncico', 'Sulfito de zinc', 'Trioxosulfato (IV) de zinc', NULL, NULL, 0, 5),
('ZnCO3', 'Carbonato cíncico', 'Carbonato de zinc', 'Trioxocarbonato (IV) de zinc', NULL, NULL, 0, 5),
('Zn3(PO4)2', 'Fosfato cíncico', 'Fosfato de zinc', 'Tetraoxofosfato (V) de zinc', NULL, NULL, 0, 5),
('Zn(ClO)2', 'Hipoclorito cíncico', 'Hipoclorito de zinc', 'Oxoclorato (I) de zinc', NULL, NULL, 0, 5),
('Zn(ClO3)2', 'Clorato cíncico', 'Clorato de zinc', 'Trioxoclorato (V) de zinc', NULL, NULL, 0, 5),
('Zn(ClO4)2', 'Perclorato cíncico', 'Perclorato de zinc', 'Tetraoxoclorato (VII) de zinc', NULL, NULL, 0, 5),
('Zn(BrO3)2', 'Bromato cíncico', 'Bromato de zinc', 'Trioxobromato (V) de zinc', NULL, NULL, 0, 5),
('Zn(IO3)2', 'Yodato cíncico', 'Yodato de zinc', 'Trioxoyodato (V) de zinc', NULL, NULL, 0, 5),
('AlCl3', 'Cloruro alumínico', 'Cloruro de aluminio', 'Tricloruro de aluminio', NULL, NULL, 0, 5),
('AlBr3', 'Bromuro alumínico', 'Bromuro de aluminio', 'Tribromuro de aluminio', NULL, NULL, 0, 5),
('AlI3', 'Yoduro alumínico', 'Yoduro de aluminio', 'Triyoduro de aluminio', NULL, NULL, 0, 5),
('AlF3', 'Fluoruro alumínico', 'Fluoruro de aluminio', 'Trifluoruro de aluminio', NULL, NULL, 0, 5),
('Al2S3', 'Sulfuro alumínico', 'Sulfuro de aluminio', 'Trisulfuro de dialuminio', NULL, NULL, 0, 5),
('Al(NO3)3', 'Nitrato alumínico', 'Nitrato de aluminio', 'Trioxonitrato (V) de aluminio', NULL, NULL, 0, 5),
('Al(NO2)3', 'Nitrito alumínico', 'Nitrito de aluminio', 'Dioxonitrato (III) de aluminio', NULL, NULL, 0, 5),
('Al2(SO4)3', 'Sulfato alumínico', 'Sulfato de aluminio', 'Tetraoxosulfato (VI) de aluminio', NULL, NULL, 0, 5),
('Al2(SO3)3', 'Sulfito alumínico', 'Sulfito de aluminio', 'Trioxosulfato (IV) de aluminio', NULL, NULL, 0, 5),
('Al2(CO3)3', 'Carbonato alumínico', 'Carbonato de aluminio', 'Trioxocarbonato (IV) de aluminio', NULL, NULL, 0, 5),
('AlPO4', 'Fosfato alumínico', 'Fosfato de aluminio', 'Tetraoxofosfato (V) de aluminio', NULL, NULL, 0, 5),
('Al(ClO)3', 'Hipoclorito alumínico', 'Hipoclorito de aluminio', 'Oxoclorato (I) de aluminio', NULL, NULL, 0, 5),
('Al(ClO3)3', 'Clorato alumínico', 'Clorato de aluminio', 'Trioxoclorato (V) de aluminio', NULL, NULL, 0, 5),
('Al(ClO4)3', 'Perclorato alumínico', 'Perclorato de aluminio', 'Tetraoxoclorato (VII) de aluminio', NULL, NULL, 0, 5),
('Al(BrO3)3', 'Bromato alumínico', 'Bromato de aluminio', 'Trioxobromato (V) de aluminio', NULL, NULL, 0, 5),
('Al(IO3)3', 'Yodato alumínico', 'Yodato de aluminio', 'Trioxoyodato (V) de aluminio', NULL, NULL, 0, 5),
('CuCl', 'Cloruro cuproso', 'Cloruro de cobre (I)', 'Cloruro de cobre', NULL, NULL, 0, 5),
('CuBr', 'Bromuro cuproso', 'Bromuro de cobre (I)', 'Bromuro de cobre', NULL, NULL, 0, 5),
('CuI', 'Yoduro cuproso', 'Yoduro de cobre (I)', 'Yoduro de cobre', NULL, NULL, 0, 5),
('CuF', 'Fluoruro cuproso', 'Fluoruro de cobre (I)', 'Fluoruro de cobre', NULL, NULL, 0, 5),
('Cu2S', 'Sulfuro cuproso', 'Sulfuro de cobre (I)', 'Monosulfuro de dicobre', NULL, NULL, 0, 5),
('CuNO3', 'Nitrato cuproso', 'Nitrato de cobre (I)', 'Trioxonitrato (V) de cobre (I)', NULL, NULL, 0, 5),
('CuNO2', 'Nitrito cuproso', 'Nitrito de cobre (I)', 'Dioxonitrato (III) de cobre (I)', NULL, NULL, 0, 5),
('Cu2SO4', 'Sulfato cuproso', 'Sulfato de cobre (I)', 'Tetraoxosulfato (VI) de cobre (I)', NULL, NULL, 0, 5),
('Cu2SO3', 'Sulfito cuproso', 'Sulfito de cobre (I)', 'Trioxosulfato (IV) de cobre (I)', NULL, NULL, 0, 5),
('Cu2CO3', 'Carbonato cuproso', 'Carbonato de cobre (I)', 'Trioxocarbonato (IV) de cobre (I)', NULL, NULL, 0, 5),
('Cu3PO4', 'Fosfato cuproso', 'Fosfato de cobre (I)', 'Tetraoxofosfato (V) de cobre (I)', NULL, NULL, 0, 5),
('CuClO', 'Hipoclorito cuproso', 'Hipoclorito de cobre (I)', 'Oxoclorato (I) de cobre (I)', NULL, NULL, 0, 5),
('CuClO3', 'Clorato cuproso', 'Clorato de cobre (I)', 'Trioxoclorato (V) de cobre (I)', NULL, NULL, 0, 5),
('CuClO4', 'Perclorato cuproso', 'Perclorato de cobre (I)', 'Tetraoxoclorato (VII) de cobre (I)', NULL, NULL, 0, 5),
('CuBrO3', 'Bromato cuproso', 'Bromato de cobre (I)', 'Trioxobromato (V) de cobre (I)', NULL, NULL, 0, 5),
('CuIO3', 'Yodato cuproso', 'Yodato de cobre (I)', 'Trioxoyodato (V) de cobre (I)', NULL, NULL, 0, 5),
('CuCl2', 'Cloruro cúprico', 'Cloruro de cobre (II)', 'Dicloruro de cobre', NULL, NULL, 0, 5),
('CuBr2', 'Bromuro cúprico', 'Bromuro de cobre (II)', 'Dibromuro de cobre', NULL, NULL, 0, 5),
('CuI2', 'Yoduro cúprico', 'Yoduro de cobre (II)', 'Diyoduro de cobre', NULL, NULL, 0, 5),
('CuF2', 'Fluoruro cúprico', 'Fluoruro de cobre (II)', 'Difluoruro de cobre', NULL, NULL, 0, 5),
('CuS', 'Sulfuro cúprico', 'Sulfuro de cobre (II)', 'Sulfuro de cobre', NULL, NULL, 0, 5),
('Cu(NO3)2', 'Nitrato cúprico', 'Nitrato de cobre (II)', 'Trioxonitrato (V) de cobre (II)', NULL, NULL, 0, 5),
('Cu(NO2)2', 'Nitrito cúprico', 'Nitrito de cobre (II)', 'Dioxonitrato (III) de cobre (II)', NULL, NULL, 0, 5),
('CuSO3', 'Sulfito cúprico', 'Sulfito de cobre (II)', 'Trioxosulfato (IV) de cobre (II)', NULL, NULL, 0, 5),
('CuCO3', 'Carbonato cúprico', 'Carbonato de cobre (II)', 'Trioxocarbonato (IV) de cobre (II)', NULL, NULL, 0, 5),
('Cu3(PO4)2', 'Fosfato cúprico', 'Fosfato de cobre (II)', 'Tetraoxofosfato (V) de cobre (II)', NULL, NULL, 0, 5),
('Cu(ClO)2', 'Hipoclorito cúprico', 'Hipoclorito de cobre (II)', 'Oxoclorato (I) de cobre (II)', NULL, NULL, 0, 5),
('Cu(ClO3)2', 'Clorato cúprico', 'Clorato de cobre (II)', 'Trioxoclorato (V) de cobre (II)', NULL, NULL, 0, 5),
('Cu(ClO4)2', 'Perclorato cúprico', 'Perclorato de cobre (II)', 'Tetraoxoclorato (VII) de cobre (II)', NULL, NULL, 0, 5),
('Cu(BrO3)2', 'Bromato cúprico', 'Bromato de cobre (II)', 'Trioxobromato (V) de cobre (II)', NULL, NULL, 0, 5),
('Cu(IO3)2', 'Yodato cúprico', 'Yodato de cobre (II)', 'Trioxoyodato (V) de cobre (II)', NULL, NULL, 0, 5),
('FeBr2', 'Bromuro ferroso', 'Bromuro de hierro (II)', 'Dibromuro de hierro', NULL, NULL, 0, 5),
('FeI2', 'Yoduro ferroso', 'Yoduro de hierro (II)', 'Diyoduro de hierro', NULL, NULL, 0, 5),
('FeF2', 'Fluoruro ferroso', 'Fluoruro de hierro (II)', 'Difluoruro de hierro', NULL, NULL, 0, 5),
('Fe(NO3)2', 'Nitrato ferroso', 'Nitrato de hierro (II)', 'Trioxonitrato (V) de hierro (II)', NULL, NULL, 0, 5),
('Fe(NO2)2', 'Nitrito ferroso', 'Nitrito de hierro (II)', 'Dioxonitrato (III) de hierro (II)', NULL, NULL, 0, 5),
('FeSO4', 'Sulfato ferroso', 'Sulfato de hierro (II)', 'Tetraoxosulfato (VI) de hierro (II)', NULL, NULL, 0, 5),
('FeSO3', 'Sulfito ferroso', 'Sulfito de hierro (II)', 'Trioxosulfato (IV) de hierro (II)', NULL, NULL, 0, 5),
('FeCO3', 'Carbonato ferroso', 'Carbonato de hierro (II)', 'Trioxocarbonato (IV) de hierro (II)', NULL, NULL, 0, 5),
('Fe3(PO4)2', 'Fosfato ferroso', 'Fosfato de hierro (II)', 'Tetraoxofosfato (V) de hierro (II)', NULL, NULL, 0, 5),
('Fe(ClO)2', 'Hipoclorito ferroso', 'Hipoclorito de hierro (II)', 'Oxoclorato (I) de hierro (II)', NULL, NULL, 0, 5),
('Fe(ClO3)2', 'Clorato ferroso', 'Clorato de hierro (II)', 'Trioxoclorato (V) de hierro (II)', NULL, NULL, 0, 5),
('Fe(ClO4)2', 'Perclorato ferroso', 'Perclorato de hierro (II)', 'Tetraoxoclorato (VII) de hierro (II)', NULL, NULL, 0, 5),
('Fe(BrO3)2', 'Bromato ferroso', 'Bromato de hierro (II)', 'Trioxobromato (V) de hierro (II)', NULL, NULL, 0, 5),
('Fe(IO3)2', 'Yodato ferroso', 'Yodato de hierro (II)', 'Trioxoyodato (V) de hierro (II)', NULL, NULL, 0, 5),
('FeBr3', 'Bromuro férrico', 'Bromuro de hierro (III)', 'Tribromuro de hierro', NULL, NULL, 0, 5),
('FeI3', 'Yoduro férrico', 'Yoduro de hierro (III)', 'Triyoduro de hierro', NULL, NULL, 0, 5),
('FeF3', 'Fluoruro férrico', 'Fluoruro de hierro (III)', 'Trifluoruro de hierro', NULL, NULL, 0, 5),
('Fe2S3', 'Sulfuro férrico', 'Sulfuro de hierro (III)', 'Trisulfuro de dihierro', NULL, NULL, 0, 5),
('Fe(NO3)3', 'Nitrato férrico', 'Nitrato de hierro (III)', 'Trioxonitrato (V) de hierro (III)', NULL, NULL, 0, 5),
('Fe(NO2)3', 'Nitrito férrico', 'Nitrito de hierro (III)', 'Dioxonitrato (III) de hierro (III)', NULL, NULL, 0, 5),
('Fe2(SO4)3', 'Sulfato férrico', 'Sulfato de hierro (III)', 'Tetraoxosulfato (VI) de hierro (III)', NULL, NULL, 0, 5),
('Fe2(SO3)3', 'Sulfito férrico', 'Sulfito de hierro (III)', 'Trioxosulfato (IV) de hierro (III)', NULL, NULL, 0, 5),
('Fe2(CO3)3', 'Carbonato férrico', 'Carbonato de hierro (III)', 'Trioxocarbonato (IV) de hierro (III)', NULL, NULL, 0, 5),
('FePO4', 'Fosfato férrico', 'Fosfato de hierro (III)', 'Tetraoxofosfato (V) de hierro (III)', NULL, NULL, 0, 5),
('Fe(ClO)3', 'Hipoclorito férrico', 'Hipoclorito de hierro (III)', 'Oxoclorato (I) de hierro (III)', NULL, NULL, 0, 5),
('Fe(ClO3)3', 'Clorato férrico', 'Clorato de hierro (III)', 'Trioxoclorato (V) de hierro (III)', NULL, NULL, 0, 5),
('Fe(ClO4)3', 'Perclorato férrico', 'Perclorato de hierro (III)', 'Tetraoxoclorato (VII) de hierro (III)', NULL, NULL, 0, 5),
('Fe(BrO3)3', 'Bromato férrico', 'Bromato de hierro (III)', 'Trioxobromato (V) de hierro (III)', NULL, NULL, 0, 5),
('Fe(IO3)3', 'Yodato férrico', 'Yodato de hierro (III)', 'Trioxoyodato (V) de hierro (III)', NULL, NULL, 0, 5),
('NiCl2', 'Cloruro niqueloso', 'Cloruro de níquel (II)', 'Dicloruro de níquel', NULL, NULL, 0, 5),
('NiBr2', 'Bromuro niqueloso', 'Bromuro de níquel (II)', 'Dibromuro de níquel', NULL, NULL, 0, 5),
('NiI2', 'Yoduro niqueloso', 'Yoduro de níquel (II)', 'Diyoduro de níquel', NULL, NULL, 0, 5),
('NiF2', 'Fluoruro niqueloso', 'Fluoruro de níquel (II)', 'Difluoruro de níquel', NULL, NULL, 0, 5),
('NiS', 'Sulfuro niqueloso', 'Sulfuro de níquel (II)', 'Sulfuro de níquel', NULL, NULL, 0, 5),
('Ni(NO3)2', 'Nitrato niqueloso', 'Nitrato de níquel (II)', 'Trioxonitrato (V) de níquel (II)', NULL, NULL, 0, 5),
('Ni(NO2)2', 'Nitrito niqueloso', 'Nitrito de níquel (II)', 'Dioxonitrato (III) de níquel (II)', NULL, NULL, 0, 5),
('NiSO4', 'Sulfato niqueloso', 'Sulfato de níquel (II)', 'Tetraoxosulfato (VI) de níquel (II)', NULL, NULL, 0, 5),
('NiSO3', 'Sulfito niqueloso', 'Sulfito de níquel (II)', 'Trioxosulfato (IV) de níquel (II)', NULL, NULL, 0, 5),
('NiCO3', 'Carbonato niqueloso', 'Carbonato de níquel (II)', 'Trioxocarbonato (IV) de níquel (II)', NULL, NULL, 0, 5),
('Ni3(PO4)2', 'Fosfato niqueloso', 'Fosfato de níquel (II)', 'Tetraoxofosfato (V) de níquel (II)', NULL, NULL, 0, 5),
('Ni(ClO)2', 'Hipoclorito niqueloso', 'Hipoclorito de níquel (II)', 'Oxoclorato (I) de níquel (II)', NULL, NULL, 0, 5),
('Ni(ClO3)2', 'Clorato niqueloso', 'Clorato de níquel (II)', 'Trioxoclorato (V) de níquel (II)', NULL, NULL, 0, 5),
('Ni(ClO4)2', 'Perclorato niqueloso', 'Perclorato de níquel (II)', 'Tetraoxoclorato (VII) de níquel (II)', NULL, NULL, 0, 5),
('Ni(BrO3)2', 'Bromato niqueloso', 'Bromato de níquel (II)', 'Trioxobromato (V) de níquel (II)', NULL, NULL, 0, 5),
('Ni(IO3)2', 'Yodato niqueloso', 'Yodato de níquel (II)', 'Trioxoyodato (V) de níquel (II)', NULL, NULL, 0, 5),
('PbCl2', 'Cloruro plumboso', 'Cloruro de plomo (II)', 'Dicloruro de plomo', NULL, NULL, 0, 5),
('PbBr2', 'Bromuro plumboso', 'Bromuro de plomo (II)', 'Dibromuro de plomo', NULL, NULL, 0, 5),
('PbI2', 'Yoduro plumboso', 'Yoduro de plomo (II)', 'Diyoduro de plomo', NULL, NULL, 0, 5),
('PbF2', 'Fluoruro plumboso', 'Fluoruro de plomo (II)', 'Difluoruro de plomo', NULL, NULL, 0, 5),
('PbS', 'Sulfuro plumboso', 'Sulfuro de plomo (II)', 'Sulfuro de plomo', NULL, NULL, 0, 5),
('Pb(NO3)2', 'Nitrato plumboso', 'Nitrato de plomo (II)', 'Trioxonitrato (V) de plomo (II)', NULL, NULL, 0, 5),
('Pb(NO2)2', 'Nitrito plumboso', 'Nitrito de plomo (II)', 'Dioxonitrato (III) de plomo (II)', NULL, NULL, 0, 5),
('PbSO4', 'Sulfato plumboso', 'Sulfato de plomo (II)', 'Tetraoxosulfato (VI) de plomo (II)', NULL, NULL, 0, 5),
('PbSO3', 'Sulfito plumboso', 'Sulfito de plomo (II)', 'Trioxosulfato (IV) de plomo (II)', NULL, NULL, 0, 5),
('PbCO3', 'Carbonato plumboso', 'Carbonato de plomo (II)', 'Trioxocarbonato (IV) de plomo (II)', NULL, NULL, 0, 5),
('Pb3(PO4)2', 'Fosfato plumboso', 'Fosfato de plomo (II)', 'Tetraoxofosfato (V) de plomo (II)', NULL, NULL, 0, 5),
('Pb(ClO)2', 'Hipoclorito plumboso', 'Hipoclorito de plomo (II)', 'Oxoclorato (I) de plomo (II)', NULL, NULL, 0, 5),
('Pb(ClO3)2', 'Clorato plumboso', 'Clorato de plomo (II)', 'Trioxoclorato (V) de plomo (II)', NULL, NULL, 0, 5),
('Pb(ClO4)2', 'Perclorato plumboso', 'Perclorato de plomo (II)', 'Tetraoxoclorato (VII) de plomo (II)', NULL, NULL, 0, 5),
('Pb(BrO3)2', 'Bromato plumboso', 'Bromato de plomo (II)', 'Trioxobromato (V) de plomo (II)', NULL, NULL, 0, 5),
('Pb(IO3)2', 'Yodato plumboso', 'Yodato de plomo (II)', 'Trioxoyodato (V) de plomo (II)', NULL, NULL, 0, 5);

-- ---------------------------------------------------------------------
-- Banco ampliado de preguntas (40 por nivel, generadas a partir del catálogo).
-- ---------------------------------------------------------------------
INSERT INTO preguntas
(id_nivel, id_tipo, enunciado, opcion_a, opcion_b, opcion_c, opcion_d, respuesta_correcta) VALUES
(3, 5, '¿Cuál es la fórmula del fosfato de zinc?', 'ZnSO4', 'ZnBr2', 'Zn3(PO4)2', 'ZnS', 'C'),
(3, 5, '¿Cuál es el nombre Stock del Zn3(PO4)2?', 'Sulfuro de zinc', 'Yoduro de zinc', 'Fosfato de zinc', 'Clorato de zinc', 'C'),
(1, 1, '¿Qué tipo de compuesto es el Cu2O?', 'Óxido', 'Hidróxido', 'Ácido', 'Hidruro', 'A'),
(2, 1, '¿Cuál es la fórmula del óxido de cobre (I)?', 'Cu2O', 'CuI', 'CuOH', 'CuH', 'A'),
(2, 1, '¿Con qué valencia actúa el cobre en el Cu2O?', '1', '5', '3', '4', 'A'),
(3, 5, '¿Cuál es la fórmula del sulfato de níquel (II)?', 'Ni(NO2)2', 'NiF2', 'NiSO4', 'NiBr2', 'C'),
(3, 5, '¿Cuál es el nombre Stock del NiSO4?', 'Sulfato de níquel (II)', 'Nitrato de níquel (II)', 'Nitrito de níquel (II)', 'Bromuro de níquel (II)', 'A'),
(3, 5, '¿Cuál es la fórmula del nitrito de calcio?', 'Ca(ClO)2', 'CaBr2', 'Ca(NO2)2', 'CaSO4', 'C'),
(3, 5, '¿Cuál es el nombre Stock del Ca(NO2)2?', 'Nitrito de calcio', 'Yoduro de calcio', 'Sulfato de calcio', 'Cloruro de calcio', 'A'),
(2, 4, '¿Cuál es la fórmula del ácido hipobromoso?', 'HBrO2', 'HBrO4', 'HBr', 'HBrO', 'D'),
(3, 4, '¿Cuál es el nombre sistemático del HBrO?', 'Heptóxido de dibromo', 'Oxobromato (I) de hidrógeno', 'Tetraoxobromato (VII) de hidrógeno', 'Dioxobromato (III) de hidrógeno', 'B'),
(3, 5, '¿Cuál es la fórmula del bromato de potasio?', 'KBrO3', 'KF', 'K2SO4', 'KNO2', 'A'),
(3, 5, '¿Cuál es el nombre Stock del KBrO3?', 'Bromato de potasio', 'Sulfito de potasio', 'Cloruro de potasio', 'Yoduro de potasio', 'A'),
(3, 5, '¿Cuál es la fórmula del fluoruro de sodio?', 'NaF', 'Na3PO4', 'NaClO', 'Na2SO3', 'A'),
(3, 5, '¿Cuál es el nombre Stock del NaF?', 'Carbonato de sodio', 'Sulfito de sodio', 'Cloruro de sodio', 'Fluoruro de sodio', 'D'),
(2, 4, '¿Cuál es la fórmula del ácido brómico?', 'Br2O', 'Br2O3', 'Br2O7', 'HBrO3', 'D'),
(3, 4, '¿Cuál es el nombre sistemático del HBrO3?', 'Tetraoxobromato (VII) de hidrógeno', 'Trioxobromato (V) de hidrógeno', 'Monóxido de dibromo', 'Oxobromato (I) de hidrógeno', 'B'),
(3, 5, '¿Cuál es la fórmula del yoduro de magnesio?', 'MgBr2', 'MgI2', 'MgS', 'Mg(NO3)2', 'B'),
(3, 5, '¿Cuál es el nombre Stock del MgI2?', 'Sulfito de magnesio', 'Nitrato de magnesio', 'Fosfato de magnesio', 'Yoduro de magnesio', 'D'),
(2, 4, '¿Cuál es la fórmula del ácido cloroso?', 'HClO4', 'HClO2', 'HClO', 'HClO3', 'B'),
(3, 4, '¿Cuál es el nombre sistemático del HClO2?', 'Pentóxido de dicloro', 'Monóxido de dicloro', 'Heptóxido de dicloro', 'Dioxoclorato (III) de hidrógeno', 'D'),
(2, 4, '¿Cuál es la fórmula del ácido telúrico?', 'H2TeO4', 'TeO2', 'TeO3', 'H2Te', 'A'),
(3, 4, '¿Cuál es el nombre sistemático del H2TeO4?', 'Dióxido de teluro', 'Tetraoxotelurato (VI) de hidrógeno', 'Trioxotelurato (IV) de hidrógeno', 'Trióxido de teluro', 'B'),
(3, 5, '¿Cuál es la fórmula del bromuro de zinc?', 'ZnBr2', 'Zn(ClO)2', 'ZnI2', 'Zn3(PO4)2', 'A'),
(3, 5, '¿Cuál es el nombre Stock del ZnBr2?', 'Hipoclorito de zinc', 'Bromuro de zinc', 'Nitrato de zinc', 'Fosfato de zinc', 'B'),
(1, 2, '¿Cuál es la fórmula del hidruro de bario?', 'BaO', 'BaH2', 'BaCO3', 'Ba(NO3)2', 'B'),
(1, 2, '¿Cuál es el nombre Stock del BaH2?', 'Sulfuro de bario', 'Cloruro de bario', 'Hidruro de bario', 'Carbonato de bario', 'C'),
(1, 2, '¿Qué tipo de compuesto es el BaH2?', 'Sal neutra', 'Hidruro', 'Óxido', 'Hidróxido', 'B'),
(3, 5, '¿Cuál es la fórmula del clorato de hierro (III)?', 'Fe(NO3)2', 'FeCl2', 'Fe(ClO3)3', 'Fe(ClO)2', 'C'),
(3, 5, '¿Cuál es el nombre Stock del Fe(ClO3)3?', 'Carbonato de hierro (II)', 'Sulfato de hierro (II)', 'Clorato de hierro (III)', 'Bromuro de hierro (II)', 'C'),
(3, 5, '¿Cuál es la fórmula del hipoclorito de magnesio?', 'MgCO3', 'Mg(ClO)2', 'MgCl2', 'MgSO3', 'B'),
(3, 5, '¿Cuál es el nombre Stock del Mg(ClO)2?', 'Hipoclorito de magnesio', 'Bromuro de magnesio', 'Sulfato de magnesio', 'Fluoruro de magnesio', 'A'),
(3, 5, '¿Cuál es la fórmula del clorato de bario?', 'BaS', 'BaI2', 'Ba(ClO3)2', 'BaCl2', 'C'),
(3, 5, '¿Cuál es el nombre Stock del Ba(ClO3)2?', 'Sulfato de bario', 'Hipoclorito de bario', 'Nitrato de bario', 'Clorato de bario', 'D'),
(3, 5, '¿Cuál es la fórmula del bromuro de sodio?', 'NaNO2', 'NaBr', 'Na2SO4', 'Na2CO3', 'B'),
(3, 5, '¿Cuál es el nombre Stock del NaBr?', 'Sulfato de sodio', 'Bromuro de sodio', 'Fluoruro de sodio', 'Cloruro de sodio', 'B'),
(3, 5, '¿Cuál es la fórmula del yodato de plomo (II)?', 'Pb(IO3)2', 'PbS', 'PbCl2', 'Pb(NO3)2', 'A'),
(3, 5, '¿Cuál es el nombre Stock del Pb(IO3)2?', 'Cloruro de plomo (II)', 'Sulfito de plomo (II)', 'Fluoruro de plomo (II)', 'Yodato de plomo (II)', 'D'),
(3, 5, '¿Cuál es la fórmula del yoduro de zinc?', 'Zn3(PO4)2', 'ZnI2', 'ZnS', 'Zn(NO3)2', 'B'),
(3, 5, '¿Cuál es el nombre Stock del ZnI2?', 'Cloruro de zinc', 'Yoduro de zinc', 'Nitrato de zinc', 'Clorato de zinc', 'B'),
(1, 1, '¿Cuál es la fórmula del óxido de plata?', 'Ag3PO4', 'AgBr', 'Ag2O', 'AgNO2', 'C'),
(1, 1, '¿Cuál es el nombre Stock del Ag2O?', 'Fluoruro de plata', 'Carbonato de plata', 'Hidróxido de plata', 'Óxido de plata', 'D'),
(1, 1, '¿Qué tipo de compuesto es el Ag2O?', 'Hidruro', 'Óxido', 'Hidróxido', 'Sal neutra', 'B'),
(2, 2, '¿Cuál es el nombre tradicional del CuH?', 'Hidróxido cuproso', 'Óxido cuproso', 'Sulfuro cuproso', 'Hidruro cuproso', 'D'),
(2, 2, '¿Cuál es la fórmula del hidruro de cobre (I)?', 'CuH2', 'CuH', 'CuOH', 'CuCl', 'B'),
(2, 2, '¿Con qué valencia actúa el cobre en el CuH?', '5', '6', '2', '1', 'D'),
(1, 3, '¿Cuál es el nombre Stock del KOH?', 'Yoduro de potasio', 'Óxido de potasio', 'Hidróxido de potasio', 'Sulfuro de potasio', 'C'),
(1, 1, '¿Qué tipo de compuesto es el Ni2O3?', 'Óxido', 'Hidruro', 'Ácido', 'Sal neutra', 'A'),
(2, 1, '¿Cuál es el nombre tradicional del Ni2O3?', 'Nitrito niqueloso', 'Fluoruro niqueloso', 'Hidróxido niquélico', 'Óxido niquélico', 'D'),
(2, 1, '¿Cuál es la fórmula del óxido de níquel (III)?', 'NiO', 'NiCl2', 'NiH3', 'Ni2O3', 'D'),
(2, 1, '¿Con qué valencia actúa el níquel en el Ni2O3?', '3', '5', '1', '2', 'A'),
(3, 5, '¿Cuál es la fórmula del sulfato de hierro (II)?', 'FeSO4', 'Fe(NO2)2', 'Fe(ClO)2', 'FeCO3', 'A'),
(3, 5, '¿Cuál es el nombre Stock del FeSO4?', 'Fluoruro de hierro (II)', 'Sulfato de hierro (II)', 'Sulfito de hierro (II)', 'Yoduro de hierro (II)', 'B'),
(3, 5, '¿Cuál es la fórmula del sulfato de calcio?', 'CaSO4', 'CaSO3', 'CaBr2', 'CaI2', 'A'),
(3, 5, '¿Cuál es el nombre Stock del CaSO4?', 'Sulfuro de calcio', 'Sulfato de calcio', 'Hipoclorito de calcio', 'Nitrito de calcio', 'B'),
(3, 5, '¿Cuál es la fórmula del nitrato de litio?', 'LiCl', 'LiNO3', 'LiClO', 'Li2SO4', 'B'),
(3, 5, '¿Cuál es el nombre Stock del LiNO3?', 'Sulfito de litio', 'Yoduro de litio', 'Nitrato de litio', 'Cloruro de litio', 'C'),
(1, 4, '¿Qué tipo de compuesto es el HIO4?', 'Hidróxido', 'Ácido', 'Óxido', 'Hidruro', 'B'),
(2, 4, '¿Cuál es la fórmula del ácido peryódico?', 'I2O3', 'HIO3', 'HIO2', 'HIO4', 'D'),
(3, 4, '¿Cuál es el nombre sistemático del HIO4?', 'Heptóxido de diyodo', 'Oxoyodato (I) de hidrógeno', 'Tetraoxoyodato (VII) de hidrógeno', 'Monóxido de diyodo', 'C'),
(3, 5, '¿Cuál es la fórmula del sulfito de sodio?', 'NaBr', 'Na2S', 'Na2SO3', 'NaClO3', 'C'),
(3, 5, '¿Cuál es el nombre Stock del Na2SO3?', 'Carbonato de sodio', 'Bromuro de sodio', 'Sulfito de sodio', 'Yoduro de sodio', 'C'),
(2, 3, '¿Cuál es el nombre tradicional del Sn(OH)2?', 'Óxido estannoso', 'Hidróxido estánnico', 'Hidróxido estannoso', 'Óxido estánnico', 'C'),
(2, 3, '¿Cuál es la fórmula del hidróxido de estaño (II)?', 'Sn(OH)2', 'SnO', 'SnO2', 'Sn(OH)4', 'A'),
(2, 3, '¿Con qué valencia actúa el estaño en el Sn(OH)2?', '1', '5', '6', '2', 'D'),
(3, 5, '¿Cuál es la fórmula del nitrito de aluminio?', 'AlBr3', 'AlPO4', 'Al(NO2)3', 'Al(NO3)3', 'C'),
(2, 4, '¿Cuál es la fórmula del ácido teluroso?', 'H2Te', 'TeO2', 'H2TeO3', 'H2TeO4', 'C'),
(2, 1, '¿Cuál es el nombre tradicional del SeO3?', 'Ácido selenhídrico', 'Ácido selénico', 'Anhídrido selénico', 'Anhídrido selenoso', 'C'),
(2, 1, '¿Cuál es el nombre tradicional del P2O5?', 'Ácido fosforoso', 'Anhídrido fosforoso', 'Anhídrido fosfórico', 'Ácido fosfórico', 'C'),
(2, 3, '¿Cuál es el nombre tradicional del Ni(OH)3?', 'Hidróxido niquélico', 'Hidruro niqueloso', 'Fluoruro niqueloso', 'Óxido niquélico', 'A'),
(2, 3, '¿Cuál es la fórmula del hidróxido de níquel (III)?', 'Ni(OH)2', 'Ni2O3', 'NiS', 'Ni(OH)3', 'D'),
(2, 3, '¿Con qué valencia actúa el níquel en el Ni(OH)3?', '1', '4', '3', '6', 'C'),
(2, 1, '¿Cuál es el nombre tradicional del FeO?', 'Hidruro ferroso', 'Óxido ferroso', 'Óxido férrico', 'Yoduro ferroso', 'B'),
(2, 1, '¿Cuál es la fórmula del óxido de hierro (II)?', 'Fe(OH)3', 'FeI2', 'FeO', 'FeCl2', 'C'),
(2, 1, '¿Con qué valencia actúa el hierro en el FeO?', '5', '2', '1', '4', 'B'),
(1, 4, '¿Qué tipo de compuesto es el H2SO4?', 'Hidruro', 'Hidróxido', 'Sal neutra', 'Ácido', 'D'),
(1, 1, '¿Qué tipo de compuesto es el Br2O?', 'Ácido', 'Óxido', 'Hidróxido', 'Sal neutra', 'B'),
(2, 1, '¿Cuál es el nombre tradicional del Br2O?', 'Anhídrido hipobromoso', 'Anhídrido perbrómico', 'Ácido bromhídrico', 'Anhídrido bromoso', 'A'),
(2, 4, '¿Cuál es la fórmula del ácido bromoso?', 'Br2O5', 'HBrO4', 'HBrO2', 'HBrO3', 'C'),
(1, 4, '¿Qué tipo de compuesto es el HNO3?', 'Hidruro', 'Óxido', 'Ácido', 'Sal neutra', 'C'),
(2, 4, '¿Cuál es la fórmula del ácido nítrico?', 'HNO2', 'N2O3', 'N2O5', 'HNO3', 'D'),
(2, 4, '¿Cuál es la fórmula del ácido selénico?', 'H2SeO4', 'SeO2', 'SeO3', 'H2SeO3', 'A'),
(2, 3, '¿Cuál es el nombre tradicional del Pb(OH)2?', 'Sulfito plumboso', 'Hidróxido plumboso', 'Óxido plumboso', 'Sulfato plumboso', 'B'),
(2, 3, '¿Cuál es la fórmula del hidróxido de plomo (II)?', 'Pb(NO3)2', 'Pb(OH)2', 'Pb(OH)4', 'Pb(NO2)2', 'B'),
(2, 3, '¿Con qué valencia actúa el plomo en el Pb(OH)2?', '4', '5', '3', '2', 'D'),
(2, 1, '¿Cuál es el nombre tradicional del Cl2O3?', 'Ácido cloroso', 'Anhídrido cloroso', 'Anhídrido clórico', 'Ácido clorhídrico', 'B'),
(1, 4, '¿Qué tipo de compuesto es el H2SO3?', 'Hidruro', 'Hidróxido', 'Óxido', 'Ácido', 'D'),
(2, 4, '¿Cuál es la fórmula del ácido sulfuroso?', 'H2SO3', 'H2S', 'SO3', 'H2SO4', 'A'),
(1, 1, '¿Cuál es el nombre Stock del CaO?', 'Nitrito de calcio', 'Sulfato de calcio', 'Óxido de calcio', 'Cloruro de calcio', 'C'),
(2, 4, '¿Cuál es la fórmula del ácido hipoyodoso?', 'HIO', 'HIO2', 'I2O', 'HIO4', 'A'),
(2, 1, '¿Cuál es el nombre tradicional del N2O3?', 'Anhídrido nítrico', 'Anhídrido nitroso', 'Ácido nitroso', 'Ácido nítrico', 'B'),
(2, 3, '¿Cuál es el nombre tradicional del Co(OH)3?', 'Óxido cobáltico', 'Hidruro cobaltoso', 'Hidróxido cobáltico', 'Óxido cobaltoso', 'C'),
(2, 3, '¿Cuál es la fórmula del hidróxido de cobalto (III)?', 'Co(OH)2', 'CoH3', 'CoO', 'Co(OH)3', 'D'),
(2, 3, '¿Con qué valencia actúa el cobalto en el Co(OH)3?', '3', '4', '6', '1', 'A'),
(1, 1, '¿Qué tipo de compuesto es el Au2O?', 'Hidruro', 'Óxido', 'Hidróxido', 'Sal neutra', 'B'),
(2, 1, '¿Cuál es el nombre tradicional del Au2O?', 'Óxido auroso', 'Óxido áurico', 'Hidróxido áurico', 'Hidróxido auroso', 'A'),
(1, 3, '¿Cuál es la fórmula del hidróxido de calcio?', 'Ca(OH)2', 'Ca(NO3)2', 'CaBr2', 'CaCl2', 'A'),
(1, 3, '¿Cuál es el nombre Stock del Ca(OH)2?', 'Nitrito de calcio', 'Sulfuro de calcio', 'Hidróxido de calcio', 'Fluoruro de calcio', 'C'),
(1, 2, '¿Cuál es la fórmula del hidruro de zinc?', 'ZnH2', 'ZnCl2', 'ZnBr2', 'ZnI2', 'A'),
(1, 2, '¿Cuál es el nombre Stock del ZnH2?', 'Hidruro de zinc', 'Fluoruro de zinc', 'Yoduro de zinc', 'Carbonato de zinc', 'A'),
(1, 3, '¿Cuál es la fórmula del hidróxido de plata?', 'AgI', 'Ag2O', 'AgOH', 'AgNO2', 'C'),
(1, 3, '¿Cuál es el nombre Stock del AgOH?', 'Nitrato de plata', 'Hidróxido de plata', 'Óxido de plata', 'Bromuro de plata', 'B'),
(1, 3, '¿Qué tipo de compuesto es el Sn(OH)4?', 'Ácido', 'Sal neutra', 'Hidruro', 'Hidróxido', 'D'),
(1, 1, '¿Qué tipo de compuesto es el PbO?', 'Hidruro', 'Ácido', 'Óxido', 'Sal neutra', 'C'),
(1, 1, '¿Cuál es la fórmula del óxido de zinc?', 'ZnF2', 'Zn(NO3)2', 'ZnO', 'ZnI2', 'C'),
(1, 1, '¿Cuál es el nombre Stock del ZnO?', 'Óxido de zinc', 'Carbonato de zinc', 'Hidróxido de zinc', 'Hidruro de zinc', 'A'),
(1, 2, '¿Cuál es la fórmula del hidruro de litio?', 'LiH', 'LiCl', 'Li2SO3', 'LiNO2', 'A'),
(1, 2, '¿Cuál es el nombre Stock del LiH?', 'Nitrito de litio', 'Bromuro de litio', 'Cloruro de litio', 'Hidruro de litio', 'D'),
(1, 1, '¿Cuál es la fórmula del óxido de bario?', 'BaO', 'BaSO3', 'BaBr2', 'BaCl2', 'A'),
(1, 1, '¿Cuál es el nombre Stock del BaO?', 'Fluoruro de bario', 'Óxido de bario', 'Hidruro de bario', 'Nitrato de bario', 'B'),
(1, 2, '¿Qué tipo de compuesto es el CoH3?', 'Ácido', 'Hidruro', 'Óxido', 'Sal neutra', 'B'),
(1, 2, '¿Qué tipo de compuesto es el CuH2?', 'Óxido', 'Ácido', 'Hidróxido', 'Hidruro', 'D'),
(1, 4, '¿Qué tipo de compuesto es el HIO3?', 'Óxido', 'Sal neutra', 'Ácido', 'Hidruro', 'C'),
(1, 1, '¿Cuál es la fórmula del óxido de potasio?', 'K2O', 'K2SO4', 'KBr', 'KF', 'A'),
(1, 1, '¿Cuál es el nombre Stock del K2O?', 'Óxido de potasio', 'Hidróxido de potasio', 'Nitrato de potasio', 'Sulfito de potasio', 'A'),
(1, 1, '¿Qué tipo de compuesto es el SO3?', 'Hidruro', 'Óxido', 'Hidróxido', 'Ácido', 'B'),
(1, 2, '¿Cuál es la fórmula del hidruro de magnesio?', 'Mg(NO2)2', 'MgCl2', 'Mg(NO3)2', 'MgH2', 'D'),
(1, 2, '¿Cuál es el nombre Stock del MgH2?', 'Sulfuro de magnesio', 'Hidruro de magnesio', 'Nitrito de magnesio', 'Hidróxido de magnesio', 'B'),
(1, 2, '¿Qué tipo de compuesto es el MgH2?', 'Ácido', 'Hidruro', 'Óxido', 'Hidróxido', 'B'),
(1, 3, '¿Cuál es la fórmula del hidróxido de bario?', 'BaBr2', 'BaO', 'Ba(NO2)2', 'Ba(OH)2', 'D');
