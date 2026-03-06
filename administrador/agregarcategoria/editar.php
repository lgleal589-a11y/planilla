<?php
$conn = new mysqli("localhost", "root", "", "punto");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id      = $_POST['id'];
    $nombre  = $_POST['categoria'];
    $usuario = $_POST['tarea'];
    $rol     = $_POST['estado'];

        // Si no se cambia la contraseña
    $sql = $conn->prepare("UPDATE actividades 
                           SET id_cat=?, Tarea=?, estado=?
                            WHERE id_act=?");

    $sql->bind_param("sssi", $nombre, $usuario, $rol, $id);

    if ($sql->execute()) {
        header("Location: ../actividades.php?success=editado");
        exit();
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}

$conn->close();
?>
