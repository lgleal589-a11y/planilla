<?php

$conn = new mysqli("localhost","root","","punto");

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$dni = $_POST['dni'];
$legajo = $_POST['legajo'];
$fecha = $_POST['fecha'];
$carga = $_POST['carga'];
$orden = $_POST['orden'];
$modalidad = $_POST['modalidad'];
$monto = $_POST['monto'];

$sql = "UPDATE personal SET
nombre='$nombre',
Telefono='$telefono',
dni='$dni',
legajo='$legajo',
fecha='$fecha',
cargahoraria='$carga',
nro_orden='$orden',
modalidad='$modalidad',
monto='$monto'
WHERE id_personal='$id'";

$conn->query($sql);

header("Location: ../personal.php");
?>