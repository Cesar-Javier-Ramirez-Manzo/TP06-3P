<HTML>
<HEAD><title>Hola mundo interactivo</title></HEAD>
<BODY>
<H3>Saludo interactivo</H3>
<?php 
// se verifica si viene el nombre por parametro por _GET
if (isset($_GET['nombre'])) {
    // si, entonces se saluda
    $nombre= $_GET['nombre']; 
	echo '<h4>Hola mundo!! ' . $nombre . '</h4>';
} else {
    //sino, entonces se muestra un textbox para poder generar el saludo
    echo '<p>Escribe tu nombre para enviarte un saludo</p>';
    echo "<FORM action='' method='GET'>\n<input type='text' name='nombre' value=''>\n";
	echo "<input type='submit' name='botonEnviar' value='Enviar saludo'>\n</FORM>\n";
}
?>
</BODY>
</HTML>