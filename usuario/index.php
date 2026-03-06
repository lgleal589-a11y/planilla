<?php
session_start();

if(!isset($_SESSION['id_usuario'])){
    header("Location: ../");
    exit();
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SIS PLanillas - Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <link rel="stylesheet" href="../estilos/paginas.css">
</head>
<body>

    <div class="sidebar">
        <h4 class="text-center">SIS Planillas</h4>
        <hr>
        <p class="small text-muted text-center"><i class="fas fa-user"></i> Gabriel Leal</p>
        <a href="#"  class="active"><i class="fas fa-home me-2"></i> Inicio</a>
        <a href="cargar.php"><i class="fas fa-building me-2"></i>circulacion</a>
        <a href="../administrador/cerrar.php" class="nav-link logout"><i class="fas fa-door-open"></i> <span>Cerrar sesión</span></a>
    </div>
</body>