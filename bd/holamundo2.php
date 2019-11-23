<?php 
// HOLA MUNDO mundo en PHP con saludo personalizado, obteniendo el nombre de la persona con
// un parametro desde el query string e.j.   http://localhost/holamundo1.php?nombre=valordelnombre 
$nombre= $_GET['nombre']; 
echo '<html><body><h4>Hola mundo!! ' . $nombre . '</h4></body></html>';
?> 