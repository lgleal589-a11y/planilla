<?php
$conn = new mysqli("localhost", "root", "", "punto");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre  = $_POST['categoria'];
    $user    = $_POST['tarea'];
    $rol     = 1;

    // Verificar DNI repetido


    if ($resultado->num_rows > 0) {
        header("Location: ../actividades.php?error=dni");
        exit();
    } else {

        $sql = "INSERT INTO actividades (id_cat, Tarea, estado) 
                VALUES ('$nombre','$user','$rol')";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../actividades.php?success=ok");
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>
