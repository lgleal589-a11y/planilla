<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../cargar.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "punto");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$id_usuario = $_SESSION['id_usuario'];

$id = $_POST['id'];
$id_act = $_POST['id_act'];
$dni = isset($_POST['dni']) ? $_POST['dni'] : null;


// verificar que el registro pertenezca al usuario
$verificar = $conn->query("SELECT id_usuario FROM planilla WHERE id_p='$id'");

if ($verificar->num_rows == 0) {
    die("Registro no encontrado");
}

$fila = $verificar->fetch_assoc();

if ($fila['id_usuario'] != $id_usuario) {
    die("No tienes permiso para editar este registro");
}


// actualizar registro
if ($dni == "") {

    $sql = "UPDATE planilla 
            SET id_act='$id_act', dni=NULL
            WHERE id_p='$id'";

} else {

    $sql = "UPDATE planilla 
            SET id_act='$id_act', dni='$dni'
            WHERE id_p='$id'";

}

if ($conn->query($sql)) {
    header("Location: ../cargar.php");
} else {
    echo "Error al actualizar: " . $conn->error;
}

$conn->close();
?>