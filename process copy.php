<?php
$conexion = new mysqli("localhost", "root", "", "pistola");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valor = $_POST["valor"];
    $fechaCreacion = date("Y-m-d H:i:s");

    // Verificar si ya existe un registro con el mismo valor en la misma fecha
    $sqlVerificar = "SELECT COUNT(*) as count FROM datos WHERE valor = '$valor' AND DATE(fecha_creacion) = CURDATE()";
    $resultadoVerificar = $conexion->query($sqlVerificar);

    if ($resultadoVerificar) {
        $fila = $resultadoVerificar->fetch_assoc();
        $existenRegistros = $fila["count"];

        if ($existenRegistros > 0) {
            // Redireccionar al formulario después del registro exitoso
            header("Location: index.php");
            exit();
        } else {
            // No existe un registro con el mismo valor en la misma fecha, proceder con la inserción
            $sql = "INSERT INTO datos (valor, fecha_creacion) VALUES ('$valor', '$fechaCreacion')";

            if ($conexion->query($sql) === TRUE) {
                // Redireccionar al formulario después del registro exitoso
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conexion->error;
            }
        }
    } else {
        // Redireccionar al formulario después del registro exitoso
        header("Location: index.php");
        exit();
    }
}

$conexion->close();
