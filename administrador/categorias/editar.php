<?php
$conn = new mysqli("localhost", "root", "", "punto");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cat = $_POST['id_cat'];
    $nuevo_nombre = $_POST['nombre'];
    $dnis = $_POST['rol'];

    // Sentencia preparada para evitar Inyección SQL
    $sql = $conn->prepare("UPDATE categorias SET categoria = ?, prioridad = ? WHERE id_cat = ?");
    $sql->bind_param("ssi", $nuevo_nombre,$dnis, $id_cat);

    if ($sql->execute()) {
        header("Location: ../categorias.php?success=editado");
    } else {
        echo "Error al actualizar: " . $conn->error;
    }

    $sql->close();
    $conn->close();
}
?>