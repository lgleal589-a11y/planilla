<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "punto";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error)
    die("Error: " . $conn->connect_error);

// 2. Consulta con JOIN para traer el nombre de la categoría
$query = "SELECT a.*, c.categoria 
FROM actividades a
INNER JOIN categorias c ON a.id_cat = c.id_cat
";
$resultado = $conn->query($query);

// 3. Obtener categorías para el select del modal
$cat = "SELECT * FROM categorias";
$resultado1 = $conn->query($cat);
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
            <a href="agregarususarios.php" class="nav-link"><i class="fas fa-users"></i> <span>Usuarios</span></a>
            <a href="Personal.php"><i class="fas fa-users"></i> <span>Personal</span></a>
            <a href="categorias.php"><i class="fas fa-tags me-2"></i> Categorías</a>
            <a href="#" class="active"><i class="fas fa-building"></i> <span>Actividades</span></a>
            <a href="cargar.php"><i class="fas fa-building"></i> <span>Planilla</span></a>

            <small class="nav-title">CUENTA</small>
            <a href="cerrar.php" class="nav-link logout"><i class="fas fa-door-open"></i> <span>Cerrar sesión</span></a>
        </nav>
    </div>
    <!-- tabla de usuarios -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Lista de tareas</h2>
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modalNuevoUsuario">
                        <i class="fas fa-plus"></i> Nuevo
                    </button>

                    <!-- Buscador -->
                    <div class="input-group w-50">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="buscadorUsuarios" class="form-control"
                            placeholder="Buscar por categoria, tarea...">
                    </div>

                </div>

                <table id="tablaUsuarios" class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>

                            <th>Categoria</th>
                            <th>Tarea</th>
                            <th>Estado</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['categoria']; ?></td>
                                <td><?php echo $row['Tarea']; ?></td>




                                <td>
                                    <span class="badge <?php echo ($row['estado'] == 1) ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo ($row['estado'] == 1) ? 'alta' : 'baja'; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info text-white btn-editar"
                                        data-id="<?php echo $row['id_act']; ?>"
                                        data-categoria="<?php echo $row['id_cat']; ?>"
                                        data-tarea="<?php echo $row['Tarea']; ?>"
                                        data-estado="<?php echo $row['estado']; ?>" data-bs-toggle="modal"
                                        data-bs-target="#modalEditarUsuario">
                                        <i class="fas fa-edit"></i>
                                    </button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Registrar nueva tarea</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="agregarcategoria/guardar.php" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Categoría</label>
                            <select name="categoria" class="form-select" required>
                                <option value="">Seleccionar categoría</option>
                                <?php
                                // Reiniciamos el puntero si es necesario o usamos una nueva variable
                                $resultado1->data_seek(0);
                                while ($rows = $resultado1->fetch_assoc()): ?>
                                    <option value="<?php echo $rows['id_cat']; ?>"> <?php echo $rows['categoria']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>



                </div>
                <div class="mb-3">
                    <label class="form-label">Tarea</label>
                    <input type="text" name="tarea" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar actividad</button>
                </div>
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
                    <h5 class="modal-title">Editar Tarea</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="agregarcategoria/editar.php" method="POST">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Categoria</label>
                            <input type="text" name="categoria" id="edit_categoria" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tarea</label>
                            <input type="text" name="tarea" id="edit_tarea" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol</label>
                            <select name="estado" id="edit_estado" class="form-select">
                                <option value="0">baja</option>
                                <option value="1">alta</option>
                            </select>
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



</body>

</html>



<!-- cosas de javascript no borrar, mary si es que lo lees esto no lo borres o si no va a funcionar los botones -->
<script>
    document.querySelectorAll('.btn-editar').forEach(boton => {
        boton.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const categoria = this.getAttribute('data-categoria');
            const tarea = this.getAttribute('data-tarea');
            const estado = this.getAttribute('data-estado');

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_categoria').value = categoria;
            document.getElementById('edit_tarea').value = tarea;
            // Corregido: el ID en el HTML es edit_estado
            document.getElementById('edit_estado').value = estado;
        });
    });

    // Buscador (Filtro)
    document.getElementById("buscadorUsuarios").addEventListener("keyup", function () {
        let filtro = this.value.toLowerCase();
        let filas = document.querySelectorAll("#tablaUsuarios tbody tr");

        filas.forEach(fila => {
            let textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(filtro) ? "" : "none";
        });
    });
</script>