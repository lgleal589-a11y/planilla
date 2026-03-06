<?php
$conn = new mysqli("localhost", "root", "", "punto");

if ($conn->connect_error) {
    die("Error de conexión");
}

$result = $conn->query("SELECT id_cat, categoria, prioridad FROM categorias");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de Actividades con Tiempo</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* ===== RESET GENERAL ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
        }

        .contenedor {
            background: #ffffff;
            width: 450px;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .cronometro {
            font-size: 2.2rem;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            background: #f4f6f9;
            color: #e67e22;
            margin-bottom: 25px;
            letter-spacing: 2px;
        }

        label {
            font-weight: 600;
            color: #34495e;
            display: block;
            margin-bottom: 6px;
        }

        select,
        input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            margin-bottom: 15px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #2a5298, #1e3c72);
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body>

    <div class="contenedor">

        <h2>Registro de Tarea</h2>

        <div class="cronometro" id="display">00:00:00</div>

        <form action="guardar.php" method="POST" id="mainForm">

            <input type="hidden" name="tiempo_segundos" id="tiempo_segundos">

            <label>Categoría</label>

            <select name="id_cat" id="categoria" required>

                <option value="">Seleccione categoría</option>

                <?php while ($row = $result->fetch_assoc()): ?>

                    <option value="<?= $row['id_cat']; ?>" data-prioridad="<?= $row['prioridad']; ?>">

                        <?= $row['categoria']; ?>

                    </option>

                <?php endwhile; ?>

            </select>

            <div id="dni_container" style="display:none;">

                <label>DNI del Ciudadano</label>

                <input type="number" name="dni_ciudadano" id="dni_ciudadano">

            </div>

            <label>Tarea</label>

            <select name="id_act" id="id_act" required>

                <option value="">Seleccione primero una categoría</option>

            </select>

            <button type="submit">Finalizar y Guardar</button>

        </form>

    </div>

    <script>

        // ==========================
        // CRONOMETRO
        // ==========================

        let inicio = Date.now();
        let display = document.getElementById("display");

        setInterval(function () {

            let transcurrido = Math.floor((Date.now() - inicio) / 1000);

            let h = Math.floor(transcurrido / 3600);
            let m = Math.floor((transcurrido % 3600) / 60);
            let s = transcurrido % 60;

            display.innerText =
                h.toString().padStart(2, '0') + ":" +
                m.toString().padStart(2, '0') + ":" +
                s.toString().padStart(2, '0');

        }, 1000);


        // ==========================
        // JQUERY
        // ==========================

        $(document).ready(function () {

            $('#categoria').change(function () {

                var catID = $(this).val();
                var prioridad = $("#categoria option:selected").data("prioridad");


                // mostrar DNI
                if (prioridad == 1) {

                    $('#dni_container').slideDown();
                    $('#dni_ciudadano').attr('required', true);

                } else {

                    $('#dni_container').slideUp();
                    $('#dni_ciudadano').removeAttr('required');
                    $('#dni_ciudadano').val('');

                }


                // cargar tareas
                if (catID) {

                    $.ajax({

                        type: 'POST',
                        url: 'obtener_tareas.php',
                        data: { id_cat: catID },

                        success: function (html) {

                            $('#id_act').html(html);

                        }

                    });

                } else {

                    $('#id_act').html('<option value="">Seleccione primero una categoría</option>');

                }

            });


            // guardar tiempo
            $('#mainForm').submit(function () {

                let segundosTotales = Math.floor((Date.now() - inicio) / 1000);

                $('#tiempo_segundos').val(segundosTotales);

            });

        });

    </script>

</body>

</html>