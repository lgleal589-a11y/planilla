<?php
$conn = new mysqli("localhost", "root", "", "punto");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id      = $_POST['id'];
    $usuario = $_POST['usuario'];
    $rol     = $_POST['rol'];
    $contra  = $_POST['contrasena'];

    // 🔎 Validar que no exista otro usuario con el mismo DNI


    // Verificar si el usuario es Administrador
    $checkRol = $conn->prepare("SELECT rol FROM usuarios WHERE id_usuario=?");
    $checkRol->bind_param("i", $id);
    $checkRol->execute();
    $resultRol = $checkRol->get_result()->fetch_assoc();

    if ($resultRol['rol'] == 'Administrador') {
        header("Location: ../agregarususarios.php?error=protegido");
        exit();
    }

    if ($resultado->num_rows > 0) {
        header("Location: ../agregarususarios.php?error=dni");
        exit();
    }

    // 🔐 Si se ingresó nueva contraseña → la encriptamos
    if (!empty($contra)) {

        $hash = password_hash($contra, PASSWORD_DEFAULT);

        $sql = $conn->prepare("UPDATE usuarios 
                               SET usuario=?, contrasena=?, rol=? 
                               WHERE id_usuario=?");

        $sql->bind_param("sssssi", $nombre, $usuario, $hash, $rol, $id);

    } else {

        // Si no se cambia la contraseña
        $sql = $conn->prepare("UPDATE usuarios 
                               SET nombre=?, dni=?, usuario=?, rol=? 
                               WHERE id_usuario=?");

        $sql->bind_param("ssssi", $nombre, $dnis, $usuario, $rol, $id);
    }

    if ($sql->execute()) {
        header("Location: ../agregarususarios.php?success=editado");
        exit();
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}

$conn->close();
?>
