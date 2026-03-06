<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Conexión
$conn = new mysqli("localhost", "root", "", "punto");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

/*
RELACIONES:
planilla.id_usuario → usuarios.id_usuario
usuarios.id_personal → personal.id_personal
planilla.id_act → actividades.id_act
actividades.id_cat → categorias.id_cat
*/

// Consulta principal
$query = "SELECT 
            c.categoria,
            a.Tarea,
            per.nombre AS personal,
            per.modalidad,
            p.dni,
            p.fecha,
            SEC_TO_TIME(p.tiempo) AS tiempo,
            p.tiempo AS tiempo_segundos
          FROM planilla p
          INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
          INNER JOIN personal per ON u.id_personal = per.id_personal
          INNER JOIN actividades a ON p.id_act = a.id_act
          INNER JOIN categorias c ON a.id_cat = c.id_cat
          ORDER BY p.fecha DESC";

$resultado = $conn->query($query);

// Calcular total general en segundos
$totalQuery = "SELECT SUM(tiempo) AS total FROM planilla";
$totalResultado = $conn->query($totalQuery);
$totalFila = $totalResultado->fetch_assoc();
$totalSegundos = (int)$totalFila['total'];

// Convertir total a HH:MM:SS
$totalTiempo = gmdate("H:i:s", $totalSegundos);

// Headers Excel
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=planillas.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "\xEF\xBB\xBF"; // UTF-8

// Tabla
echo "<table border='1'>";

echo "<tr>
        <th style='background-color:#4CAF50;color:white;'>Categoría</th>
        <th style='background-color:#4CAF50;color:white;'>Tarea</th>
        <th style='background-color:#4CAF50;color:white;'>Personal</th>
        <th style='background-color:#4CAF50;color:white;'>Modalidad</th>
        <th style='background-color:#4CAF50;color:white;'>DNI</th>
        <th style='background-color:#4CAF50;color:white;'>Fecha</th>
        <th style='background-color:#4CAF50;color:white;'>Tiempo</th>
      </tr>";

if ($resultado->num_rows > 0) {

    while ($fila = $resultado->fetch_assoc()) {

        echo "<tr>";
        echo "<td>" . htmlspecialchars($fila['categoria']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['Tarea']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['personal']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['modalidad']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['dni']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['fecha']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['tiempo']) . "</td>";
        echo "</tr>";
    }

} else {
    echo "<tr><td colspan='7'>No hay datos disponibles</td></tr>";
}

// Fila de total general
echo "<tr style='font-weight:bold;background-color:#f2f2f2;'>
        <td colspan='6' align='right'>TOTAL GENERAL:</td>
        <td>{$totalTiempo}</td>
      </tr>";

echo "</table>";

$conn->close();
exit();
?>