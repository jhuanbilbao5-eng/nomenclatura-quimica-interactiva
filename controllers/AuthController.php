<?php
// Registro, inicio de sesión, cierre de sesión y cambio de contraseña (RF09, RNF08)

class AuthController extends Controller
{
    public function index(): void
    {
        redirigir('auth', 'login');
    }

    public function login(): void
    {
        if (Auth::estaLogueado()) {
            redirigir('home', Auth::esAdmin() ? 'panel' : 'index');
        }

        $errores = [];
        $correo = '';

        if ($this->esPost()) {
            $correo = $this->post('correo');
            $clave = $this->post('clave');

            if (!Validator::correo($correo) || $clave === '') {
                $errores[] = 'Ingrese un correo válido y su contraseña.';
            } else {
                $modelo = new Usuario();
                $cuenta = $modelo->estadoBloqueo($correo);

                if ($cuenta && $cuenta['bloqueado_hasta'] !== null && strtotime($cuenta['bloqueado_hasta']) > time()) {
                    // Cuenta bloqueada temporalmente por varios intentos fallidos
                    $minutos = (int) ceil((strtotime($cuenta['bloqueado_hasta']) - time()) / 60);
                    $errores[] = 'Demasiados intentos fallidos. Espere ' . $minutos . ' minuto(s) e intente nuevamente.';
                } else {
                    $usuario = $modelo->autenticar($correo, $clave);
                    if ($usuario === null) {
                        if ($cuenta) {
                            $modelo->registrarFallo((int) $cuenta['id_usuario'], (int) $cuenta['intentos_fallidos']);
                        }
                        // Mensaje genérico: no revela si el correo existe
                        $errores[] = 'Correo o contraseña incorrectos.';
                    } else {
                        $modelo->reiniciarIntentos((int) $usuario['id_usuario']);
                        Auth::iniciar($usuario);
                        redirigir('home', $usuario['rol'] === 'Administrador' ? 'panel' : 'index');
                    }
                }
            }
        }

        $this->vista('auth/login', ['titulo' => 'Iniciar sesión', 'errores' => $errores, 'correo' => $correo]);
    }

    public function registro(): void
    {
        if (Auth::estaLogueado()) {
            redirigir('home', Auth::esAdmin() ? 'panel' : 'index');
        }

        $errores = [];
        $datos = ['nombres' => '', 'apellidos' => '', 'correo' => ''];

        if ($this->esPost()) {
            $datos['nombres'] = $this->post('nombres');
            $datos['apellidos'] = $this->post('apellidos');
            $datos['correo'] = $this->post('correo');
            $clave = $this->post('clave');
            $confirmar = $this->post('confirmar');

            if (!Validator::requerido($datos['nombres']) || !Validator::soloLetras($datos['nombres'])
                || !Validator::longitudMaxima($datos['nombres'], 60)) {
                $errores[] = 'Los nombres son obligatorios y solo pueden tener letras.';
            }
            if (!Validator::requerido($datos['apellidos']) || !Validator::soloLetras($datos['apellidos'])
                || !Validator::longitudMaxima($datos['apellidos'], 60)) {
                $errores[] = 'Los apellidos son obligatorios y solo pueden tener letras.';
            }
            if (!Validator::correo($datos['correo']) || !Validator::longitudMaxima($datos['correo'], 100)) {
                $errores[] = 'Ingrese un correo válido.';
            }
            if (!Validator::claveSegura($clave)) {
                $errores[] = 'La contraseña debe tener al menos 8 caracteres, con letras y números.';
            }
            if ($clave !== $confirmar) {
                $errores[] = 'La confirmación de la contraseña no coincide.';
            }

            $modelo = new Usuario();
            if (count($errores) === 0 && $modelo->existeCorreo($datos['correo'])) {
                $errores[] = 'Ya existe una cuenta con ese correo.';
            }

            if (count($errores) === 0) {
                $modelo->registrar($datos['nombres'], $datos['apellidos'], $datos['correo'], $clave);
                $this->flash('success', 'Cuenta creada. Ya puede iniciar sesión.');
                redirigir('auth', 'login');
            }
        }

        $this->vista('auth/registro', ['titulo' => 'Crear cuenta', 'errores' => $errores, 'datos' => $datos]);
    }

    /** Mi cuenta: cambiar la contraseña (Usuario y Administrador). */
    public function cuenta(): void
    {
        Auth::requerirLogin();
        $errores = [];

        if ($this->esPost()) {
            $actual = $this->post('actual');
            $nueva = $this->post('nueva');
            $confirmar = $this->post('confirmar');
            $modelo = new Usuario();

            if (!$modelo->verificarClave((int) Auth::id(), $actual)) {
                $errores[] = 'La contraseña actual no es correcta.';
            }
            if (!Validator::claveSegura($nueva)) {
                $errores[] = 'La nueva contraseña debe tener al menos 8 caracteres, con letras y números.';
            }
            if ($nueva !== $confirmar) {
                $errores[] = 'La confirmación de la nueva contraseña no coincide.';
            }
            if (count($errores) === 0 && $nueva === $actual) {
                $errores[] = 'La nueva contraseña debe ser distinta de la actual.';
            }

            if (count($errores) === 0) {
                $modelo->cambiarClave((int) Auth::id(), $nueva);
                $this->flash('success', 'Contraseña actualizada correctamente.');
                redirigir('auth', 'cuenta');
            }
        }

        $this->vista('auth/cuenta', [
            'titulo'  => 'Mi cuenta',
            'errores' => $errores,
            'usuario' => (new Usuario())->obtener((int) Auth::id()),
        ]);
    }

    public function logout(): void
    {
        Auth::cerrar();
        $this->flash('success', 'Sesión cerrada.');
        redirigir('auth', 'login');
    }
}
