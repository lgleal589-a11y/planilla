<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "punto");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$usuario = $_POST['usuario'];
$contrasena_ingresada = $_POST['contrasena'];

$sql = "SELECT * FROM usuarios WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {

    $datos = $resultado->fetch_assoc();

    if (password_verify($contrasena_ingresada, $datos['contrasena'])) {

        session_regenerate_id(true);

        $_SESSION['id_usuario'] = $datos['id_usuario'];
        $_SESSION['nombre'] = $datos['nombre'];
        $_SESSION['usuario'] = $datos['usuario'];
        $_SESSION['rol'] = $datos['rol'];

        if ($datos['rol'] == "Administrador") {
            header("Location: administrador/index.php");
        } else {
            header("Location: usuario/index.php");
        }
        exit();

    } else {
        header("Location: index.php?error=clave");
        exit();
    }

} else {
    header("Location: index.php?error=usuario");
    exit();
}

$stmt->close();
$conn->close();
?>
