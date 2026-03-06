<?php
session_start();

/* Vaciar variables */
$_SESSION = array();

/* Si querés eliminar la cookie de sesión también (más seguro) */
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

/* Destruir sesión */
session_destroy();

/* Redirigir al login */
header("Location: ../index.php");
exit();
?>
