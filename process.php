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
            $sqlVerificar = "SELECT COUNT(*) as count FROM datos WHERE valor = '$v' AND DATE(fecha_creacion) = CURDATE()";
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

    // Redireccionar al formulario después del registro exitoso
    header("Location: index.php");
    exit();
}

$conexion->close();
?>
