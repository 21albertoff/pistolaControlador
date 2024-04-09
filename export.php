<?php
// export.php

$conexion = new mysqli("localhost", "root", "", "pistola");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener fechas desde el formulario
    $fechaInicio = $_POST["fecha_inicio"];
    $fechaFin = $_POST["fecha_fin"];

    // Consultar datos en el rango de fechas
    $sqlExport = "SELECT * FROM datos WHERE fecha_creacion BETWEEN '$fechaInicio 00:00:00' AND '$fechaFin 23:59:59'";
    $resultadoExport = $conexion->query($sqlExport);

    if ($resultadoExport) {
        // Crear archivo Excel
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="pistola_datos.xls"');
        echo '<table border="1">';

        while ($fila = $resultadoExport->fetch_assoc()) {
            echo '<tr>
                    <td>' . $fila["valor"] . '</td>
                </tr>';
        }

        echo '</table>';
        exit();
    } else {
        echo "Error al exportar datos: " . $conexion->error;
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Exportar a Excel</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Exportar Datos a Excel</h2>
        <form action="export.php" method="post">
            <div class="form-group">
                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label for="fecha_fin">Fecha de Fin:</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= date('Y-m-d'); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Exportar a Excel</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
