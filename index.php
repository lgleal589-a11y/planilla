<?php if(isset($_GET['error'])): ?>
    <div class="alert">
        El usuario y/o contraseña es incorrecta.
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>inicio de sesion</title>
    <link rel="stylesheet" href="estilos/inicios.css">
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <div class="header-blue"></div>
            <h1>inicio de sesion</h1>
            <p>Iniciar sesión</p>
            
            <form action="validar.php" method="POST">
                <div class="input-group">
                    <input type="text" name="usuario" placeholder="Usuario" required>
                    <span class="icon"><i class="fas fa-user"></i></span>
                </div>
                
                <div class="input-group">
                    <input type="password" name="contrasena" placeholder="Contraseña" required>
                    <span class="icon"><i class="fas fa-lock"></i></span>
                </div>
                
                <button type="submit" class="btn-ingresar">Ingresar</button>
            </form>
        </div>
    </div>

</body>
</html>