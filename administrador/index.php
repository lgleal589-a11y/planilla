<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
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
        <div class="sidebar-header">
            <h4 class="text-center">SIS Planillas</h4>
        </div>

        <div class="user-profile text-center">
            <div class="user-avatar">
                <i class="fas fa-user-circle fa-2x"></i>
            </div>
            <p class="user-name">Gabriel Leal</p>
            <span class="user-status"><i class="fas fa-circle"></i> En línea</span>
        </div>

        <nav class="sidebar-nav">
            <small class="nav-title">MENÚ PRINCIPAL</small>
            <a href="#" class="active" class="nav-link"><i class="fas fa-home"></i> <span>Inicio</span></a>
            <a href="agregarususarios.php" class="nav-link"><i class="fas fa-users"></i> <span>Usuarios</span></a>
            <a href="personal.php" ><i class="fas fa-users"></i> <span>Personal</span></a>
            <a href="categorias.php"><i class="fas fa-building"></i> <span>categorias</span></a>
            <a href="actividades.php"><i class="fas fa-building"></i> <span>Actividades</span></a>
            <a href="cargar.php"><i class="fas fa-building"></i> <span>Planilla</span></a>

            <small class="nav-title">CUENTA</small>
            <a href="cerrar.php" class="nav-link logout"><i class="fas fa-door-open"></i> <span>Cerrar sesión</span></a>
        </nav>
    </div>

</body>