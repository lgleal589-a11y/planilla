<?php
// 1. Estableces la conexión como $conn
$conn = new mysqli("localhost", "root", "", "punto");

// Verificar si la conexión falló
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (isset($_POST['id_cat']) && !empty($_POST['id_cat'])) {
    $id_cat = $_POST['id_cat'];
    
    // ERROR CORREGIDO: Cambié $conexion->prepare por $conn->prepare
    $stmt = $conn->prepare("SELECT id_act, Tarea FROM actividades WHERE id_cat = ?");
    $stmt->bind_param("i", $id_cat);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<option value="">Seleccione una tarea</option>';
        while ($row = $result->fetch_assoc()) {
            // Es buena práctica usar htmlspecialchars si la tarea tiene caracteres especiales
            echo '<option value="'.$row['id_act'].'">'.htmlspecialchars($row['Tarea']).'</option>';
        }
    } else {
        echo '<option value="">No hay tareas disponibles para esta categoría</option>';
    }
    $stmt->close();
}
$conn->close();
?>