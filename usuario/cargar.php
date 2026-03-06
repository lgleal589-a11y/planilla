<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
  header("Location: ../index.php");
  exit();
}

$conn = new mysqli("localhost", "root", "", "punto");
if ($conn->connect_error)
  die("Error conexión");

// REGISTROS
$query = "SELECT 
p.id_p,
c.id_cat,
c.categoria,
c.prioridad,
a.id_act,
a.Tarea,
per.nombre,
p.dni,
p.fecha,
SEC_TO_TIME(p.tiempo) AS tiempo
FROM planilla p
INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
INNER JOIN personal per ON u.id_personal = per.id_personal
INNER JOIN actividades a ON p.id_act = a.id_act
INNER JOIN categorias c ON a.id_cat = c.id_cat
ORDER BY p.fecha DESC";

$resultado = $conn->query($query);

// CATEGORIAS
$categorias = $conn->query("SELECT * FROM categorias");
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>SIS Planillas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="../estilos/paginas.css">

</head>

<body>
  <div class="sidebar">
    <h4 class="text-center">SIS Planillas</h4>
    <hr>
    <p class="small text-muted text-center"><i class="fas fa-user"></i> Gabriel Leal</p>
    <a href="index.php"><i class="fas fa-home me-2"></i> Inicio</a>
    <a href="#" class="active"><i class="fas fa-users me-2"></i> Usuarios</a>
    <a href="../index.php"><i class="fas fa-clock me-2"></i>Cerrar session</a>
  </div>
  <div class="container mt-5">

    <h3 class="mb-4">Registros</h3>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario"
        onclick="window.location.href='cargar/agregar.php'">
        <i class="fas fa-plus"></i> Nuevo
      </button>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario"
        onclick="window.location.href='cargar/descargar.php'">
        <i class="fas fa-plus"></i> Excel
      </button>

      <!-- Buscador -->
      <div class="input-group w-50">
        <span class="input-group-text bg-white">
          <i class="fas fa-search text-muted"></i>
        </span>
        <input type="text" id="buscadorUsuarios" class="form-control"
          placeholder="Buscar por nombre, categoria, tarea o fecha...">
      </div>

    </div>

    <table class="table table-bordered table-hover" id="tablaUsuarios">

      <thead class="table-dark">
        <tr>
          <th>Categoría</th>
          <th>Tarea</th>
          <th>DNI</th>
          <th>Fecha</th>
          <th>Personal</th>
          <th>Tiempo</th>
          <th>Acción</th>
        </tr>
      </thead>

      <tbody>

        <?php while ($row = $resultado->fetch_assoc()): ?>

          <tr>

            <td><?= $row['categoria'] ?></td>
            <td><?= $row['Tarea'] ?></td>
            <td><?= $row['dni'] ?></td>
            <td><?= $row['fecha'] ?></td>
            <td><?= $row['nombre'] ?></td>
            <td><?= $row['tiempo'] ?></td>

            <td>

              <button class="btn btn-info btn-sm btn-editar" data-id="<?= $row['id_p'] ?>"
                data-cat="<?= $row['id_cat'] ?>" data-act="<?= $row['id_act'] ?>" data-dni="<?= $row['dni'] ?>"
                data-prioridad="<?= $row['prioridad'] ?>" data-bs-toggle="modal" data-bs-target="#modalEditar">

                Editar

              </button>

            </td>

          </tr>

        <?php endwhile; ?>

      </tbody>
    </table>

  </div>


  <!-- MODAL EDITAR -->

  <div class="modal fade" id="modalEditar">

    <div class="modal-dialog">

      <div class="modal-content">

        <div class="modal-header bg-info text-white">
          <h5 class="modal-title">Editar Registro</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form action="cargar/editar.php" method="POST">

          <input type="hidden" name="id" id="edit_id">

          <div class="modal-body">

            <label>Categoría</label>

            <select name="id_cat" id="edit_categoria" class="form-control">

              <option value="">Seleccione</option>

              <?php while ($c = $categorias->fetch_assoc()): ?>

                <option value="<?= $c['id_cat'] ?>" data-prioridad="<?= $c['prioridad'] ?>">

                  <?= $c['categoria'] ?>

                </option>

              <?php endwhile; ?>

            </select>

            <br>

            <label>Tarea</label>

            <select name="id_act" id="edit_tarea" class="form-control">

              <option value="">Seleccione tarea</option>

            </select>

            <br>

            <div id="dni_container" style="display:none;">

              <label>DNI</label>

              <input type="number" name="dni" id="edit_dni" class="form-control">

            </div>

          </div>

          <div class="modal-footer">

            <button type="submit" class="btn btn-info text-white">Guardar</button>

          </div>

        </form>

      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


  <script>

    // BOTON EDITAR

    document.querySelectorAll(".btn-editar").forEach(btn => {

      btn.addEventListener("click", function () {

        let id = this.dataset.id
        let cat = this.dataset.cat
        let act = this.dataset.act
        let dni = this.dataset.dni
        let prioridad = this.dataset.prioridad

        document.getElementById("edit_id").value = id
        document.getElementById("edit_categoria").value = cat
        document.getElementById("edit_dni").value = dni


        // mostrar dni si prioridad

        if (prioridad == 1) {

          $("#dni_container").show()

        } else {

          $("#dni_container").hide()

        }


        // cargar tareas

        $.post("cargar/obtener_tareas.php", { id_cat: cat }, function (data) {

          $("#edit_tarea").html(data)
          $("#edit_tarea").val(act)

        })

      })

    })


    // CAMBIO DE CATEGORIA

    $("#edit_categoria").change(function () {

      let prioridad = $("#edit_categoria option:selected").data("prioridad")

      let cat = $(this).val()

      if (prioridad == 1) {

        $("#dni_container").slideDown()

      } else {

        $("#dni_container").slideUp()
        $("#edit_dni").val("")

      }


      // cargar tareas

      $.post("cargar/obtener_tareas.php", { id_cat: cat }, function (data) {

        $("#edit_tarea").html(data)

      })

    })




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


</body>

</html>