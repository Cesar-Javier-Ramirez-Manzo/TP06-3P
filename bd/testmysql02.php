<HTML>
<HEAD><title>Pagina de acceso a BD</title>
<BODY>
<?php
error_reporting(0);
$mysqli = new mysqli("127.0.0.1:3306", "root", "", "test");
if ($mysqli->connect_errno) {
    echo "<h4>Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " 
	      . $mysqli->connect_error . "</h4>";
}
// pruebas de ejecucion de queries SQL de accion
if (!$mysqli->query("DROP TABLE IF EXISTS test2") ||
    !$mysqli->query("CREATE TABLE test2(id INT)") ||
    !$mysqli->query("INSERT INTO test2(id) VALUES (100)")) {
    echo "<h4>Falló la creación de la tabla: (" . $mysqli->errno . ") " . $mysqli->error . "</h4>";
} 
else
{
    echo "<H1>Operaciones exitosas a la BD test!</H1>";
	echo "<H5>..las operaciones fueron Borrado de tabla previa TEST2, nueva creacion e insertar el registro 100</H5>";
}
?>
</BODY>
</HTML>


