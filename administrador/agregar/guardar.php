<?php
$conn = new mysqli("localhost", "root", "", "punto");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre  = $_POST['id_p'];
    $user    = $_POST['usuario'];
    $pass    = password_hash($_POST['password'], PASSWORD_DEFAULT); // 🔐 ENCRIPTADA
    $rol     = $_POST['rol'];

    // Verificar DNI repetido
    $verificar = "SELECT id_usuario FROM usuarios WHERE dni = '$dnis'";
    $resultado = $conn->query($verificar);

    if ($resultado->num_rows > 0) {
        header("Location: ../agregarususarios.php?error=dni");
        exit();
    } else {

        $sql = "INSERT INTO usuarios (id_personal, usuario, contrasena, rol) 
                VALUES ('$nombre', '$user', '$pass', '$rol')";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../agregarususarios.php?success=ok");
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>
