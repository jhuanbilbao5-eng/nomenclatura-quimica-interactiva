<?php
// Enrutador: recibe c (controlador) y a (acción) y ejecuta el método correspondiente.
// Ejemplo: index.php?c=formular&a=index  ->  FormularController::index()

class Router
{
    public static function despachar(): void
    {
        $c = strtolower((string) ($_GET['c'] ?? ''));
        $a = (string) ($_GET['a'] ?? 'index');

        // Todo formulario enviado por POST debe traer el token CSRF de la sesión
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = (string) ($_POST['csrf'] ?? '');
            if (!hash_equals(csrf_token(), $token)) {
                $_SESSION['flash'] = ['tipo' => 'warning', 'mensaje' => 'El formulario expiró. Vuelva a intentarlo.'];
                $volver = ['c' => $c ?: 'auth', 'a' => $a];
                if (isset($_GET['id'])) {
                    $volver['id'] = (int) $_GET['id'];
                }
                header('Location: ' . BASE_URL . 'index.php?' . http_build_query($volver));
                exit;
            }
        }

        // Sin parámetros: enviar a la pantalla que corresponde
        if ($c === '') {
            if (Auth::estaLogueado()) {
                redirigir('home', Auth::esAdmin() ? 'panel' : 'index');
            }
            redirigir('auth', 'login');
        }

        if (!preg_match('/^[a-z]+$/', $c) || !preg_match('/^[a-zA-Z]+$/', $a)) {
            self::noEncontrado();
        }

        $clase = ucfirst($c) . 'Controller';
        if (!class_exists($clase)) {
            self::noEncontrado();
        }

        $controlador = new $clase();
        if (!method_exists($controlador, $a)) {
            self::noEncontrado();
        }

        $metodo = new ReflectionMethod($controlador, $a);
        if (!$metodo->isPublic() || $metodo->getDeclaringClass()->getName() !== $clase) {
            self::noEncontrado();
        }

        $controlador->$a();
    }

    private static function noEncontrado(): void
    {
        http_response_code(404);
        echo '<h1>404</h1><p>La página solicitada no existe.</p>';
        exit;
    }
}
