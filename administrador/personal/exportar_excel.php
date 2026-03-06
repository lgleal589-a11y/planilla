<?php

$conn = new mysqli("localhost","root","","punto");

if ($conn->connect_error) {
    die("Error de conexión");
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=personal.xls");
header("Pragma: no-cache");
header("Expires: 0");

$sql = "SELECT * FROM personal";
$result = $conn->query($sql);

echo "<table border='1'>";

echo "<tr>
<th>Nombre</th>
<th>Telefono</th>
<th>DNI</th>
<th>Legajo</th>
<th>Fecha</th>
<th>Carga Horaria</th>
<th>N° Orden</th>
<th>Modalidad</th>
<th>Monto</th>
</tr>";

while($row = $result->fetch_assoc()){

echo "<tr>
<td>".$row['nombre']."</td>
<td>".$row['Telefono']."</td>
<td>".$row['dni']."</td>
<td>".$row['legajo']."</td>
<td>".$row['fecha']."</td>
<td>".$row['cargahoraria']."</td>
<td>".$row['nro_orden']."</td>
<td>".$row['modalidad']."</td>
<td>".$row['monto']."</td>
</tr>";

}

echo "</table>";

?>