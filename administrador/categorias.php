<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit();
}

// 1. Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "punto");
if ($conn->connect_error)
    die("Error: " . $conn->connect_error);

// 2. Obtener categorías
$query = "SELECT * FROM categorias";
$resultado = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>SIS Planillas - Categorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../estilos/paginas.css">
</head>

<body>
    <div class="sidebar">
        <nav class="sidebar-nav">
            <small class="nav-title">MENÚ PRINCIPAL</small>
            <a href="index.php"><i class="fas fa-home me-2"></i> Inicio</a>
            <a href="agregarususarios.php"><i class="fas fa-users me-2"></i> Usuarios</a>
            <a href="personal.php"><i class="fas fa-users me-2"></i> Personal</a>
            <a href="#" class="active"><i class="fas fa-tags me-2"></i> Categorías</a>
            <a href="actividades.php"><i class="fas fa-building me-2"></i> Actividades</a>
            <a href="cargar.php"><i class="fas fa-building"></i> <span>Planilla</span></a>
            <small class="nav-title">CUENTA</small>
            <a href="cerrar.php" class="nav-link logout"><i class="fas fa-door-open"></i> <span>Cerrar sesión</span></a>
        </nav>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Gestión de Categorías</h2>
            <nav>
                <small class="text-muted">Home / categorías</small>
            </nav>
        </div>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'categoria'): ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-exclamation-triangle"></i> Ya existe esta categoría.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-check-circle"></i> Operación realizada con éxito.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modalNuevoUsuario">
                        <i class="fas fa-plus"></i> Nueva Categoría
                    </button>
                    <div class="input-group w-50">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="buscadorUsuarios" class="form-control" placeholder="Buscar categoría...">
                    </div>
                </div>

                <table id="tablaUsuarios" class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Categoría</th>
                            <th>Prioridad de DNI</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id_cat']; ?></td>
                                <td><?php echo $row['categoria']; ?></td>
                                <td>
                                    <span
                                        class="badge <?php echo ($row['prioridad'] == 1) ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo ($row['prioridad'] == 1) ? 'SI' : 'NO' ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info text-white btn-editar"
                                        data-id="<?php echo $row['id_cat']; ?>"
                                        data-nombre="<?php echo $row['categoria']; ?>" data-bs-toggle="modal"
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

    <div class="modal fade" id="modalNuevoUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="categorias/guardar.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nombre de la Categoría</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Administración"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">prioridad de pedir DNI?</label>
                            <select name="rol" class="form-select">
                                <option value="1">SI</option>
                                <option value="0">NO</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Editar Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="categorias/editar.php" method="POST">
                        <input type="hidden" name="id_cat" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Nombre de la Categoría</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">prioridad de pedir DNI?</label>
                            <select name="rol" class="form-select">
                                <option value="1">SI</option>
                                <option value="0">NO</option>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Lógica para pasar datos al modal de editar
        document.querySelectorAll('.btn-editar').forEach(boton => {
            boton.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre');
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_nombre').value = nombre;
            });
        });

        // Buscador en tiempo real
        document.getElementById("buscadorUsuarios").addEventListener("keyup", function () {
            let filtro = this.value.toLowerCase();
            let filas = document.querySelectorAll("#tablaUsuarios tbody tr");
            filas.forEach(fila => {
                let textoFila = fila.textContent.toLowerCase();
                fila.style.display = textoFila.includes(filtro) ? "" : "none";
            });
        });
    </script>
</body>

</html>