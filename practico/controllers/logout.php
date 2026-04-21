<?php 
    session_start();

    // Limpiar todas las variables de sesión
    $_SESSION = array();

    // Destruir la cookie de sesión (si existe)
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Destruir la sesión actual
    session_destroy();

    // Iniciar sesión nueva
    session_start();

    // Regenerar nuevo ID de sesión para evitar conflictos
    session_regenerate_id(true);

    // Guardar mensaje en la nueva sesión
    $_SESSION['despedida'] = 'Sesión cerrada correctamente.';

    // Redirigir
    header("Location: ../pages/login.php");
    exit();
?>
