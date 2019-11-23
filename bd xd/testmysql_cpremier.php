<?php
// Vamos a pasar una variable $_GET a nuestro ejemplo, en este caso es
// 'idt' para 'id_trabajador' de la BD Cpremier. Le vamos a asignar un
// valor predeterminado de 1, y a amoldarla a un integer para evitar inyecciones
// de SQL y/o problemas de seguridad relacionados. El manejo de todo esto va más
// allá del alcance de este sencillo ejemplo:
//   http://localhost:4000/testmysql_cpremier.php?idt=1311
if (isset($_GET['idt']) && is_numeric($_GET['idt'])) {
    $idt = (int) $_GET['idt'];
} else {
    $idt = 1311;
}

echo "<html><body>";
// Conectarse a y seleccionar una base de datos de MySQL llamada cpremier
// Nombre de host: 127.0.0.1:4000, nombre de usuario: tu_usuario, contraseña: tu_contraseña, bd: cpremier
$mysqli = new mysqli('localhost:3307', 'root', '', 'cpremier');

// ¡Oh, no! Existe un error 'connect_errno', fallando así el intento de conexión
if ($mysqli->connect_errno) {
    // La conexión falló. 
    // Probemos esto:
    echo "Lo sentimos, este sitio web está experimentando problemas.";

    // Algo que no se debería de hacer en un sitio público, aunque este ejemplo lo mostrará
    // de todas formas, es imprimir información relacionada con errores de MySQL -- se podría registrar
    echo "Error: Fallo al conectarse a MySQL debido a: \n";
    echo "Errno: " . $mysqli->connect_errno . "\n";
    echo "Error: " . $mysqli->connect_error . "\n";
	echo "</body></html>";
    
    // Podría ser conveniente mostrar algo interesante, aunque nosotros simplemente saldremos
    exit;
} 
else
{
	echo '<h4>La conexion a BD Cpremier ... OK!! </h4>';
}

// Realizar una consulta SQL
$sql = "SELECT id_trabajador, nombre, tarifa_Hr, tipo_de_oficio, id_supv FROM trabajador WHERE id_trabajador = $idt";
if (!$resultado = $mysqli->query($sql)) {
    // ¡Oh, no! La consulta falló. 
    echo "Lo sentimos, este sitio web está experimentando problemas.";

    // De nuevo, no hacer esto en un sitio público, aunque nosotros mostraremos
    // cómo obtener información del error
    echo "Error: La ejecución de la consulta falló debido a: \n";
    echo "Query: " . $sql . "\n";
    echo "Errno: " . $mysqli->errno . "\n";
    echo "Error: " . $mysqli->error . "\n";
	echo "</body></html>";
    exit;
}

// ¡Uf, lo conseguimos!. Sabemos que nuestra conexión a MySQL y nuestra consulta
// tuvieron éxito, pero ¿tenemos un resultado?
if ($resultado->num_rows === 0) {
    // ¡Oh, no ha filas! Unas veces es lo previsto, pero otras
    echo "Lo sentimos. No se pudo encontrar una coincidencia para el ID $idt. Inténtelo de nuevo.";
	echo "</body></html>";
		
    exit;
}

// Ahora, sabemos que existe solamente un único resultado en este ejemplo, por lo
// que vamos a colocarlo en un array asociativo donde las claves del mismo son los
// nombres de las columnas de la tabla
$trabajador = $resultado->fetch_assoc();
echo " mostrando datos particulares del registro:  <strong>" . 
		$trabajador['id_trabajador'] . " - "  .
        $trabajador['nombre'] . " - " . $trabajador['tipo_de_oficio'] . " - " .
		$trabajador['id_supv'] . "</strong>";

// Ahora, vamor a obtener los registros y a imprimir sus nombres en una lista.
// El manejo de errores va a ser menor aquí, aunque ya sabemos como hacerlo
$sql = "SELECT id_trabajador, nombre FROM trabajador ORDER BY 1;";
if (!$resultado = $mysqli->query($sql)) {
    echo "Lo sentimos, este sitio web está experimentando problemas.";
	echo "</body></html>";
    exit;
}

// Imprimir nuestros trabajadores aleatorios en una lista, y enlazar cada uno
echo "<ul>\n";
while ($trabajador = $resultado->fetch_assoc()) {
    echo "<li><a href='./clase.php?idt=" . $trabajador['id_trabajador'] . "'>\n";
    echo $trabajador['id_trabajador'] . ' - ' . $trabajador['nombre'];
    echo "</a></li>\n";
}
echo "</ul>\n";
echo "</body></html>";
// El script automáticamente liberará el resultado y cerrará la conexión
// a MySQL cuando finalice, aunque aquí lo vamos a hacer nostros mismos
$resultado->free();
$mysqli->close();
?>