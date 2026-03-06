<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
  header("Location: ../index.php");
  exit();
}

// 1. Conexión a la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$db = "punto";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error)
  die("Error: " . $conn->connect_error);

// 2. Obtener usuarios

$query = "SELECT a.*, c.nombre, c.dni FROM usuarios a INNER JOIN personal c ON a.id_personal = c.id_personal;";
$resultado = $conn->query($query);
?>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


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
    <nav class="sidebar-nav">
      <small class="nav-title">MENÚ PRINCIPAL</small>
      <a href="index.php" class="nav-link"><i class="fas fa-home"></i> <span>Inicio</span></a>
      <a href="#.php" class="active" class="nav-link"><i class="fas fa-users"></i> <span>Usuarios</span></a>
      <a href="personal.php"><i class="fas fa-users"></i> <span>Personal</span></a>
      <a href="categorias.php"><i class="fas fa-building"></i> <span>categorias</span></a>
      <a href="actividades.php"><i class="fas fa-building"></i> <span>Actividades</span></a>
      <a href="cargar.php"><i class="fas fa-building"></i> <span>Planilla</span></a>

      <small class="nav-title">CUENTA</small>
      <a href="cerrar.php" class="nav-link logout"><i class="fas fa-door-open"></i> <span>Cerrar sesión</span></a>
    </nav>
  </div>
  <!-- tabla de usuarios -->
  <div class="main-content">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Lista de Usuarios</h2>
      <?php if (isset($_GET['error']) && $_GET['error'] == 'dni'): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
          <i class="fas fa-exclamation-triangle"></i>
          Ya existe un usuario con ese DNI.
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <nav>
        <small class="text-muted">Home / usuarios</small>
      </nav>
    </div>


    <div class="card shadow-sm mt-3">
      <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
            <i class="fas fa-plus"></i> Nuevo
          </button>

          <!-- Buscador -->
          <div class="input-group w-50">
            <span class="input-group-text bg-white">
              <i class="fas fa-search text-muted"></i>
            </span>
            <input type="text" id="buscadorUsuarios" class="form-control"
              placeholder="Buscar por nombre, usuario, DNI o rol...">
          </div>

        </div>

        <table id="tablaUsuarios" class="table table-bordered table-hover">
          <thead class="table-dark">
            <tr>

              <th>Nombre</th>
              <th>Usuario</th>
              <th>DNI</th>
              <th>Rol</th>
              <th class="text-center">Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $resultado->fetch_assoc()): ?>
              <tr>

                <td><?php echo $row['nombre']; ?></td>
                <td><?php echo $row['usuario']; ?></td>
                <td><?php echo $row['dni']; ?></td>
                <td>
                  <span class="badge <?php echo ($row['rol'] == 'Administrador') ? 'badge-admin' : 'badge-user'; ?>">
                    <?php echo $row['rol']; ?>
                  </span>
                </td>
                <td class="text-center">

                  <?php
                  // No mostrar botones si:
                  // - Es Administrador
                  // - Es el usuario logueado
                  if ($row['rol'] != 'Administrador' && $row['id_usuario'] != $_SESSION['id_usuario']):
                    ?>

                    <!-- Botón Editar -->
                    <button class="btn btn-sm btn-info text-white btn-editar" data-id="<?php echo $row['id_usuario']; ?>"
                      data-nombre="<?php echo $row['nombre']; ?>" data-dni="<?php echo $row['dni']; ?>"
                      data-usuario="<?php echo $row['usuario']; ?>" data-rol="<?php echo $row['rol']; ?>"
                      data-bs-toggle="modal" data-bs-target="#modalEditarUsuario">
                      <i class="fas fa-edit"></i>
                    </button>

                    <!-- Botón Eliminar -->
                    <button class="btn btn-sm btn-danger btn-eliminar" data-id="<?php echo $row['id_usuario']; ?>"
                      data-nombre="<?php echo $row['nombre']; ?>" data-bs-toggle="modal"
                      data-bs-target="#modalEliminarUsuario">
                      <i class="fas fa-trash"></i>
                    </button>

                  <?php else: ?>

                    <span class="text-muted">Protegido</span>

                  <?php endif; ?>

                </td>

              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- agregar un nuevo usuario en si -->

  <div class="modal fade" id="modalNuevoUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Registrar Nuevo Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="agregar/guardar.php" method="POST">

            <div class="mb-3">
              <label class="form-label">Nombre del personal</label>

              <select name="id_p" class="form-select" required>

                <option value="">Seleccione un personal</option>

                <?php
                $conn = new mysqli("localhost", "root", "", "punto");

                $query = $conn->query("
SELECT p.id_personal, p.nombre
FROM personal p
LEFT JOIN usuarios u ON p.id_personal = u.id_personal
WHERE u.id_personal IS NULL
");

                while ($row = $query->fetch_assoc()) {
                  echo "<option value='{$row['id_personal']}'>{$row['nombre']}</option>";
                }
                ?>

              </select>

            </div>

            <div class="mb-3">
              <label class="form-label">Nombre de Usuario</label>
              <input type="text" name="usuario" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Contraseña</label>
              <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Rol</label>
              <select name="rol" class="form-select">
                <option value="Usuario">Usuario</option>
                <option value="Administrador">Administrador</option>
              </select>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Usuario</button>

          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- modificar un  usuario en si -->
  <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title">Editar Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="agregar/editar.php" method="POST">
            <input type="hidden" name="id" id="edit_id">

            <div class="mb-3">
              <label class="form-label">Nombre Completo</label>
              <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">DNI</label>
              <input type="text" name="DNI" id="edit_dni" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Usuario</label>
              <input type="text" name="usuario" id="edit_usuario" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Rol</label>
              <select name="rol" id="edit_rol" class="form-select">
                <option value="Usuario">Usuario</option>
                <option value="Administrador">Administrador</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Contraseña</label>
              <input type="text" name="contrasena" id="edit_contrasena" class="form-control">
              <small class="text-muted">Dejar vacío si no desea cambiar la contraseña</small>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-info text-white">Actualizar Datos</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- boton para eleminar el dicho usuario -->
  <div class="modal fade" id="modalEliminarUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Confirmar Eliminación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>¿Estás seguro de que deseas eliminar al usuario <b id="nombre_usuario_eliminar"></b>?</p>
          <p class="text-danger small">Esta acción no se puede deshacer.</p>
        </div>
        <div class="modal-footer">
          <form action="agregar/eleminar.php" method="POST">
            <input type="hidden" name="id" id="eliminar_id">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-danger">Eliminar Definitivamente</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</body>

</html>



<!-- cosas de javascript no borrar, mary si es que lo lees esto no lo borres o si no va a funcionar los botones -->

<script>
  // --- Lógica para Editar ---
  document.querySelectorAll('.btn-editar').forEach(boton => {
    boton.addEventListener('click', function () {
      // Obtenemos los datos del botón
      const id = this.getAttribute('data-id');
      const nombre = this.getAttribute('data-nombre');
      const dnis = this.getAttribute('data-dni');
      const usuario = this.getAttribute('data-usuario');
      const rol = this.getAttribute('data-rol');

      // Los ponemos en los inputs del modal
      document.getElementById('edit_id').value = id;
      document.getElementById('edit_nombre').value = nombre;
      document.getElementById('edit_dni').value = dnis;
      document.getElementById('edit_usuario').value = usuario;
      document.getElementById('edit_rol').value = rol;

      // NOTA: No es recomendable pasar la contraseña actual al modal por seguridad.
      // Si quieres que puedan cambiarla, deja el input de password vacío.
    });
  });

  // --- Lógica para Eliminar ---

  document.querySelectorAll('.btn-eliminar').forEach(boton => {
    boton.addEventListener('click', function () {
      const id = this.getAttribute('data-id');
      const nombre = this.getAttribute('data-nombre');

      document.getElementById('eliminar_id').value = id;
      document.getElementById('nombre_usuario_eliminar').innerText = nombre;
    });
  });
  document.getElementById("buscadorUsuarios").addEventListener("keyup", function () {

    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll("#tablaUsuarios tbody tr");

    filas.forEach(function (fila) {
      let textoFila = fila.textContent.toLowerCase();

      if (textoFila.includes(filtro)) {
        fila.style.display = "";
      } else {
        fila.style.display = "none";
      }
    });
  });
</script>