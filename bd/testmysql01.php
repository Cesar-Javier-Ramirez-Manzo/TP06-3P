<?php 

$conexion = @new mysqli("127.0.0.1:3306",'root','');
if ($conexion->connect_errno) { 
	die('Could not connect to MySQL: ' 
	.$mysqli->connect_error); 
} 
else
{
	echo '<html><body><h4>La conexion al server MySQL OK!! </h4></body></html>';
	$conexion->close(); 
}
?> 



