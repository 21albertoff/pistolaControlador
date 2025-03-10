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
    $centro = $_POST["centro"];
   
    $sqlExport = "SELECT * FROM datos WHERE centro LIKE '$centro' AND fecha_creacion BETWEEN '$fechaInicio 00:00:00' AND '$fechaFin 23:59:59'";

    if ($centro === "Todos"){
        $sqlExport = "SELECT * FROM datos WHERE fecha_creacion BETWEEN '$fechaInicio 00:00:00' AND '$fechaFin 23:59:59'";
    }

    $resultadoExport = $conexion->query($sqlExport);

    if ($resultadoExport) {
        // Crear archivo CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="pistola_datos.csv"');
        
        // Cabecera del archivo CSV
        echo "valor\n";
        
        while ($fila = $resultadoExport->fetch_assoc()) {
            echo '"' . $fila["valor"] . '"' . "\n";
        }

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
    <title>Exportar a CSV</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Exportar Datos a CSV</h2>
        <form action="export.php" method="post">
            <div class="form-group">
                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label for="fecha_fin">Fecha de Fin:</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label for="centro">Centro:</label>
                <select class="form-control" id="centro" name="centro" required>
                    <option value="Todos" selected>Todos</option>
                    <option value="1450">Adra</option>
                    <option value="1500">Primores</option>
                    <option value="1550">Nice</option>
                    <option value="1300">La Redonda</option>
                    <option value="1350">Merpo</option>
                    <!-- Puedes añadir más opciones aquí -->
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Exportar a CSV</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
