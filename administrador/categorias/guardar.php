<?php
$conn = new mysqli("localhost", "root", "", "punto");

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['nombre'])) {
    $nombre = $_POST['nombre'];
    $dnis = $_POST['rol'];

    // 1. Preparamos una consulta para ver si ya existe el nombre
    $stmt_check = $conn->prepare("SELECT id_cat FROM categorias WHERE categoria = ?");
    $stmt_check->bind_param("s", $nombre);
    $stmt_check->execute();
    $resultado = $stmt_check->get_result();

    if ($resultado->num_rows > 0) {
        // La categoría ya existe
        header("Location: ../categorias.php?error=existe");
    } else {
        // 2. Si no existe, la insertamos de forma segura
        $stmt_insert = $conn->prepare("INSERT INTO categorias (categoria, prioridad) VALUES (?, ?)");
        $stmt_insert->bind_param("ss", $nombre, $dnis);

        if ($stmt_insert->execute()) {
            header("Location: ../categorias.php?success=ok");
            exit();
        } else {
            echo "Error al insertar: " . $conn->error;
        }
        $stmt_insert->close();
    }
    $stmt_check->close();
}

$conn->close();
?>