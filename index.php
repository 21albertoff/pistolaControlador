<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registro de Datos</title>
    <link rel="stylesheet" href="styles.css">

</head>

<body>
    <div class="container mt-5">
        <br>
        <h2 style="padding: 33px;text-align: center;      margin-left: -7px;">Lectura con pistola</h2>
        <?php
        // Mostrar el último valor leído desde la base de datos
        $conexion = new mysqli("localhost", "root", "", "pistola");

        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        $sql = "SELECT valor FROM datos ORDER BY fecha_creacion DESC LIMIT 1";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            $ultimoValor = $fila["valor"];
            echo "<p style='margin-left: -7px;'>Último valor registrado: $ultimoValor</p>";
        }

        $conexion->close();
        ?>
        <form action="process.php" method="post">
            <input type="text" id="valor" name="valor" required>
            <button type="submit">Guardar</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        // Colocar automáticamente el foco en el campo de entrada al cargar la página
        document.getElementById('valor').focus();
    </script>

</body>

</html>