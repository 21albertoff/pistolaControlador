<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registro de Datos</title>
    <link rel="stylesheet" href="styles.css">
	 <style>
        #valor {
            width: 9ch; /* Aproximadamente 10 caracteres */
            height: 100px; /* Ajusta la altura según tus necesidades */
            overflow-wrap: break-word; /* Rompe palabras largas si es necesario */
            white-space: pre-wrap; /* Mantiene saltos de línea y espacios */
            resize: none; /* Desactiva la capacidad de redimensionar el textarea */
			 width: 88%;
    padding: 12px; /* Aumenta el tamaño del padding */
    margin-bottom: 20px; /* Aumenta el tamaño del margen inferior */
    box-sizing: border-box;
    font-size: 50px;
    outline: none; 
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <br>
        <h2 style="padding: 33px;text-align: center; margin-left: -7px;">Lectura con pistola</h2>
        <?php
        //Mostrar el valor de la ultima 
        $conexion = new mysqli("localhost", "root", "", "pistola");

        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        $sql = "SELECT valor FROM datos WHERE centro LIKE '1350' AND tipo = '2' ORDER BY fecha_creacion DESC LIMIT 1";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            $ultimoValor = $fila["valor"];
            echo "<p style='margin-left: -7px;'>Último valor registrado: $ultimoValor</p>";
        }

        $conexion->close();
        ?>
        <form action="process13502.php" method="post">
			<textarea id="valor" name="valor" required></textarea>
            <input type="hidden" id="centro" name="centro" value="1350">
            <button type="submit">Guardar</button>
        </form>
    </div>
</body>

</html>
