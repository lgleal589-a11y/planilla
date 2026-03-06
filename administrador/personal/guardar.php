<?php

$conn = new mysqli("localhost","root","","punto");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$nombre = $_POST['nombre'];
$dni = $_POST['DNI'];
$telefono = $_POST['telefono'];
$legajo = $_POST['legajo'];
$fecha = $_POST['fecha'];
$carga = $_POST['carga'];
$orden = $_POST['orden'];
$modalidad = $_POST['modalidad'];
$monto = $_POST['Monto'];

// Verificar si el DNI ya existe
$verificar = $conn->query("SELECT * FROM personal WHERE dni='$dni'");

if ($verificar->num_rows > 0) {
    header("Location: ../personal.php?error=dni");
    exit();
}

$sql = "INSERT INTO personal 
(nombre, Telefono, dni, legajo, fecha, cargahoraria, nro_orden, modalidad, monto) 
VALUES 
('$nombre','$telefono','$dni',$fecha, '$legajo','$carga','$orden','$modalidad','$monto')";

$conn->query($sql);

header("Location: ../personal.php");
exit();

?>