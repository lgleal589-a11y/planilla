<?php
// Conexión
$conn = new mysqli("localhost", "root", "", "punto");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];


    //verificar el rol
    $checkRol = $conn->prepare("SELECT rol FROM usuarios WHERE id_usuario=?");
    $checkRol->bind_param("i", $id);
    $checkRol->execute();
    $resultRol = $checkRol->get_result()->fetch_assoc();

    if ($resultRol && $resultRol['rol'] == 'Administrador') {
        header("Location: ../agregarususarios.php?error=protegido");
        exit();
    }

    
    // Consulta para borrar
    $sql = "DELETE FROM usuarios WHERE id_usuario = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../agregarususarios.php");
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
}
?>