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
$query = "SELECT * FROM personal
";
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
            <a href="agregarususarios.php" class="nav-link"><i class="fas fa-users"></i> <span>Usuarios</span></a>
            <a href="#" class="active"><i class="fas fa-users"></i> <span>Personal</span></a>
            <a href="categorias.php"><i class="fas fa-users"></i> <span>categorias</span></a>
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

                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalNuevoUsuario">
                            <i class="fas fa-plus"></i> Nuevo
                        </button>

                        <a href="personal/exportar_excel.php" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Exportar a Excel
                        </a>
                    </div>

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
                        <th>Telefono</th>
                        <th>DNI</th>
                        <th>Legajo</th>
                        <th>Fecha de nacimiento</th>
                        <th>carga</th>
                        <th>N° Orden</th>
                        <th>Modalidad</th>
                        <th>Monto</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultado->fetch_assoc()): ?>
                        <tr>

                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['Telefono']; ?></td>
                            <td><?php echo $row['dni']; ?></td>
                            <td><?php echo $row['legajo']; ?></td>
                            <td><?php echo $row['fecha']; ?></td>
                            <td><?php echo $row['cargahoraria']; ?></td>
                            <td><?php echo $row['nro_orden']; ?></td>
                            <td><?php echo $row['modalidad']; ?></td>
                            <td><?php echo $row['monto']; ?></td>
                            <td class="text-center">


                                <button class="btn btn-sm btn-info text-white btn-editar"
                                    data-id="<?php echo $row['id_personal']; ?>" data-nombre="<?php echo $row['nombre']; ?>"
                                    data-telefono="<?php echo $row['Telefono']; ?>" data-dni="<?php echo $row['dni']; ?>"
                                    data-legajo="<?php echo $row['legajo']; ?>" data-fecha="<?php echo $row['fecha']; ?>"
                                    data-carga="<?php echo $row['cargahoraria']; ?>"
                                    data-orden="<?php echo $row['nro_orden']; ?>"
                                    data-modalidad="<?php echo $row['modalidad']; ?>"
                                    data-modalidad="<?php echo $row['monto']; ?>" data-bs-toggle="modal"
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
                    <h5 class="modal-title" id="exampleModalLabel">Registrar Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="personal/guardar.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Juan Perez" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">DNI</label>
                            <input type="text" name="DNI" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Numero de telefono</label>
                            <input type="text" name="telefono" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">legajo</label>
                            <input type="text" name="legajo" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Fecha</label>
                            <input type="date" id="edit_fecha" name="fecha" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">carga horaria</label>
                            <input type="text" name="carga" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">numero de orden</label>
                            <input type="text" name="orden" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Modalidad</label>
                            <select name="modalidad" class="form-select">
                                <option value="Planta">Planta</option>
                                <option value="Programa">Programa</option>
                                <option value="Contrato">Contrato</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Monto</label>
                            <input type="text" name="Monto" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Editar Personal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="personal/editar.php" method="POST">

                    <div class="modal-body">

                        <input type="hidden" id="edit_id" name="id">

                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" id="edit_nombre" name="nombre" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Teléfono</label>
                            <input type="text" id="edit_telefono" name="telefono" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>DNI</label>
                            <input type="text" id="edit_dni" name="dni" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Legajo</label>
                            <input type="text" id="edit_legajo" name="legajo" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Fecha</label>
                            <input type="date" id="edit_fecha" name="fecha" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Carga Horaria</label>
                            <input type="text" id="edit_carga" name="carga" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>N° Orden</label>
                            <input type="text" id="edit_orden" name="orden" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Modalidad</label>
                            <select id="edit_modalidad" name="modalidad" class="form-select">
                                <option value="Planta">Planta</option>
                                <option value="Programa">Programa</option>
                                <option value="Contrato">Contrato</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Monto</label>
                            <input type="text" id="edit_monto" name="monto" class="form-control">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</body>

</html>



<!-- cosas de javascript no borrar, mary si es que lo lees esto no lo borres o si no va a funcionar los botones -->

<script>
    // --- Lógica para Editar ---
    document.addEventListener("DOMContentLoaded", function () {

        document.querySelectorAll('.btn-editar').forEach(boton => {

            boton.addEventListener('click', function () {

                document.getElementById('edit_id').value = this.dataset.id;
                document.getElementById('edit_nombre').value = this.dataset.nombre;
                document.getElementById('edit_telefono').value = this.dataset.telefono;
                document.getElementById('edit_dni').value = this.dataset.dni;
                document.getElementById('edit_legajo').value = this.dataset.legajo;
                document.getElementById('edit_fecha').value = this.dataset.fecha;
                document.getElementById('edit_carga').value = this.dataset.carga;
                document.getElementById('edit_orden').value = this.dataset.orden;
                document.getElementById('edit_modalidad').value = this.dataset.modalidad;
                document.getElementById('edit_monto').value = this.dataset.monto;

            });

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