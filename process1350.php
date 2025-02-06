<?php
$conexion = new mysqli("localhost", "root", "", "pistola");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valor = $_POST["valor"];
    $centro = $_POST["centro"];
    $fechaCreacion = date("Y-m-d H:i:s");

    // Separar el valor por la primera letra que encuentre
    preg_match_all('/([A-Z]\d+)/', $valor, $matches);
    $valores = $matches[0];

    foreach ($valores as $v) {
        $v = trim($v); // Eliminar espacios en blanco
        if (!empty($v)) {
            // Verificar si ya existe un registro con el mismo valor en la misma fecha
            $sqlVerificar = "SELECT COUNT(*) as count FROM datos WHERE valor = '$v' AND tipo != '2' AND centro LIKE '1350' AND DATE(fecha_creacion) = CURDATE()";
            $resultadoVerificar = $conexion->query($sqlVerificar);

            if ($resultadoVerificar) {
                $fila = $resultadoVerificar->fetch_assoc();
                $existenRegistros = $fila["count"];

                if ($existenRegistros == 0) {
                    // No existe un registro con el mismo valor en la misma fecha, proceder con la inserción
                    $sql = "INSERT INTO datos (valor, centro, tipo, fecha_creacion) VALUES ('$v', '$centro', 1, '$fechaCreacion')";
                    $conexion->query($sql);
                }
            }
        }
    }

    // Determinar la fecha del archivo y el rango de tiempo para filtrar registros
    $currentHour = date('H');
    $currentDate = date('Y-m-d');

    if ($currentHour < 16) {
        // Si es antes de las 16:00, la fecha del archivo es la fecha actual
        $fechaArchivo = date('d-m-Y');
        // Filtrar los registros desde las 16:00 de ayer hasta las 15:59 de hoy
        $startDateTime = date('Y-m-d 16:00:00', strtotime('-1 day'));
        $endDateTime = date('Y-m-d 15:59:59');
    } else {
        // Si es después de las 16:00, la fecha del archivo es la fecha de mañana
        $fechaArchivo = date('d-m-Y', strtotime('+1 day'));
        // Filtrar los registros desde las 16:00 de hoy hasta las 15:59 de mañana
        $startDateTime = date('Y-m-d 16:00:00');
        $endDateTime = date('Y-m-d 15:59:59', strtotime('+1 day'));
    }

    // Generar archivo CSV con registros dentro del rango de tiempo
    $sqlExport = "SELECT * FROM datos WHERE centro LIKE '1350' AND tipo != '2' AND fecha_creacion BETWEEN '$startDateTime' AND '$endDateTime'";
    $resultadoExport = $conexion->query($sqlExport);

    if ($resultadoExport && $resultadoExport->num_rows > 0) {
        $filename = "inv_1350_" . $fechaArchivo . "_p1.csv"; // Nombre del archivo con la fecha determinada
        $filepath = "C:/INV-SAP/S4P/I/$filename"; // Ruta completa del archivo en Windows
        
        // Abrir archivo en modo de añadir si ya existe para mantener los registros anteriores
        $csvFile = fopen($filepath, 'a');
        // Escribir datos en el archivo CSV
        while ($fila = $resultadoExport->fetch_assoc()) {
            fwrite($csvFile, $fila["valor"] . ';' . $fila["centro"] . ';' . $fila["fecha_creacion"] . "\n");
        }

        fclose($csvFile);
    } else {
        echo "No hay registros para exportar en el rango de tiempo seleccionado.";
    }

    // Redireccionar al formulario después del registro exitoso
    header("Location: 1350.php");
    exit();
}

$conexion->close();
?>
