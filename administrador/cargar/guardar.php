<?php
session_start();
date_default_timezone_set("America/Argentina/Buenos_Aires");

$conn = new mysqli("localhost", "root", "", "punto");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $idact   = $_POST['id_act']; // corregido nombre
    $dnis    = isset($_POST['dni_ciudadano']) ? $_POST['dni_ciudadano'] : NULL;
    $fecha_hoy = date("Y-m-d");
    $user     = $_SESSION['id_usuario'];
    $tiempo   = $_POST['tiempo_segundos'];

    // Prepared Statement (más seguro)
    $stmt = $conn->prepare("INSERT INTO planilla (id_act, id_usuario, dni, fecha, tiempo) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $idact, $user, $dnis, $fecha_hoy, $tiempo);

    if ($stmt->execute()) {
        header("Location: ../cargar.php?success=ok");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>