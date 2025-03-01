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
            $sqlVerificar = "SELECT COUNT(*) as count FROM datos WHERE valor = '$v' AND centro LIKE '1400' AND DATE(fecha_creacion) = CURDATE()";
            $resultadoVerificar = $conexion->query($sqlVerificar);

            if ($resultadoVerificar) {
                $fila = $resultadoVerificar->fetch_assoc();
                $existenRegistros = $fila["count"];

                if ($existenRegistros == 0) {
                    // No existe un registro con el mismo valor en la misma fecha, proceder con la inserción
                    $sql = "INSERT INTO datos (valor, centro, fecha_creacion) VALUES ('$v', '$centro', '$fechaCreacion')";
                    $conexion->query($sql);
                }
            }
        }
    }

    // Generar archivo CSV con registros de la fecha actual
   $sqlExportToday = "SELECT * FROM datos WHERE DATE(fecha_creacion) = CURDATE() AND centro LIKE '1400'";
   $resultadoExportToday = $conexion->query($sqlExportToday);

   if ($resultadoExportToday && $resultadoExportToday->num_rows > 0) {
       $filename = "inv_1450_" . date('d-m-Y') . ".csv"; // Nombre del archivo con la fecha actual
       $filepath = "C:/INV-SAP/S4P/I/$filename"; // Ruta completa del archivo en Windows
       
       $csvFile = fopen($filepath, 'w');
       // Escribir datos en el archivo CSV
       while ($fila = $resultadoExportToday->fetch_assoc()) {
           fwrite($csvFile, $fila["valor"] . ';' . $fila["centro"] . ';' . $fila["fecha_creacion"] . "\n");
       }

       fclose($csvFile);
   } else {
       echo "No hay registros para exportar hoy.";
   }


    // Redireccionar al formulario después del registro exitoso
    header("Location: index.php");
    exit();
}

$conexion->close();
?>
