<?php 
/* -- Codigo para generar formulario dinamico de BD con PHP   */ 

$base= "cpremier";         /* lectura de datos via GET/queryString/URL  */
$tabla= "Trabajador";     /*   http://localhost/directorio/generaFormulario.php?bd=cpremier&tabla=asignacion   */

if (!isset($tabla))
   {echo "<h3>IMPORTANTE:  Se espera recibir 2 parametros via GET. Uno es la 'bd' y otro es la 'tabla'...</h3>";
    die();
};	
?>

<html>

<head>
  <!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<title>Formulario Dinamico de <?php echo $tabla ?></title>
</head>
<body class="center-align">

<?php   
   echo "<h3>Formulario Dinamico de ".$tabla."</h3>";
   /*  ejemplo de SQL para extraer los metadatos de los campos de una tabla especifica  */
   $sql="SELECT column_name,data_type,is_nullable,character_maximum_length,column_key 
         FROM   information_schema.columns 
         WHERE  Table_schema='".$base."' AND table_name='".$tabla."'";
		 
   echo "<p>Comando SQL para recuperar la estructura de la tabla '<b>".$tabla."</b>'</p>";
   echo "<pre>".$sql."</pre>";
   
   $conexion = mysqli_connect("127.0.0.1:3306",'root','',$base);  /* conexion a BD  */

   if(!$result = mysqli_query($conexion, $sql)) {    /* ejecucion del query  */
     echo "<h5>error en SQL, no regreso resultados!</h3>";
     die();
   }
   $rawdata = array();   
   $i=0;
   
  /* recuperacion fila x fila del resulset */
   while($row = mysqli_fetch_array($result))  
   {   $rawdata[$i] = $row;  /* almacen en vector de cada registro  */
       $i++;
   }
   
   $columnas = count($rawdata[0])/2;
   $filas = count($rawdata);
   echo "<h5>Filas: ".$filas.", Columnas: ".$columnas."</h3>";
   
   echo '<table class="striped">';   /* inicio de la tabla  */
   echo "<th><b>#</b></th>";   /* primer ciclo para imprimir cabeceras   */
   for($i=1;$i<count($rawdata[0]);$i=$i+2){
      next($rawdata[0]);
      echo "<th><b>".key($rawdata[0])."</b></th>";
      next($rawdata[0]);
   }
   for($i=0;$i<$filas;$i++){   /* segundo ciclo para enviar los datos de cada registro */
      echo "<tr><td>".($i+1)."</td>";   /* inicio del renglon  */
      for($j=0;$j<$columnas;$j++){
         echo "<td>".$rawdata[$i][$j]."</td>";  /* columna x columna   matriz[fila i,columna j] */
      }
      echo "</tr>\n";   /* fin del renglon  */
   }
   echo "</table>";   /*fin de la tabla   */
   
   
   /* ********** parte de generacion del formulario dinamico  *************************   */
   echo "<div class='container'>";

   echo "<h3>Formulario de captura - ".$tabla."</h3>";
   echo "<FORM name='formulario' action='grabaFormulario.php' method='POST'>\n";
   
   /* por cada campo se genera una etiqeuta y textBox, ademas de un campo oculto para heredar el dataType */ 
   for($i=0;$i<$filas;$i++){
        
       echo "<div class='row'>";
       echo "<div class='input-field col s12'>";
       echo "<input type='text' name='campo_".$rawdata[$i][0]."' id='".$rawdata[$i][0]."' class='validate' required/>";
       echo "<label for='".$rawdata[$i][0]."'>".$rawdata[$i][0]."</label>";
       echo "</div>";
   }
   /*algunos campos ocultos para enviar datos via POST */
   echo "<tr><td/><td><input type='hidden' name='bd' value='".$base."'/></td></tr>";  
   echo "<tr><td/><td><input type='hidden' name='tabla' value='".$tabla."'/></td></tr>";
   echo "<tr><td/><td><input type='hidden' name='num_campos' value='".$filas."'/></td></tr>";
   echo "</div></FORM>";   /* cierre de tabla y del formulario  */
   echo "<button type='submit' class='waves-effect waves-light btn center-align'>Grabar registro</button></td></tr>";   /*boton del form*/
   echo "<br/><a href='##' onClick='history.go(-1); return false;'>Regresar</a>";
   echo "</div>";

    $close = mysqli_close($conexion);  /* cierre de conexion a mySQL  */
?> 
 </body>
</html>



<!--   
Ejemplos de Sentencias para extraer de INFORMATION_SCHEMA la informacion sobre las Claves Primarias y Foraneas de 
cada tabla de una cierta BD seleccionada en MySQLServer


para las PKs

SELECT TC.TABLE_SCHEMA as BD, TC.Table_name as TablaBASE, TC.constraint_name as CName,  
       TC.CONSTRAINT_TYPE as TipoC, 
	   KC.COLUMN_NAME as CampoBASE, 
       NULL as TablaREF, NULL as CampoREF  
FROM information_schema.table_constraints as TC INNER JOIN 
     information_schema.KEY_COLUMN_USAGE as KC
       ON (KC.TABLE_SCHEMA=TC.TABLE_SCHEMA  
	       AND TC.constraint_name=KC.constraint_name 
		   AND KC.Table_name=TC.Table_name)
WHERE TC.TABLE_SCHEMA='cpremier' AND TC.CONSTRAINT_NAME='PRIMARY';


para los FKs

SELECT RC.CONSTRAINT_SCHEMA as BD, RC.Table_Name as TablaBASE, RC.constraint_name as CName,
       'FOREIGN KEY' as TipoC,
	   KC.COLUMN_NAME as CampoBASE,  
       KC.REFERENCED_TABLE_NAME as TablaREF, 
	   KC.REFERENCED_COLUMN_NAME as CampoREF
FROM information_schema.REFERENTIAL_CONSTRAINTS as RC INNER JOIN  
     information_schema.KEY_COLUMN_USAGE as KC
      ON (KC.TABLE_SCHEMA=RC.CONSTRAINT_SCHEMA 
          AND RC.Table_Name=KC.TABLE_NAME 
          AND RC.constraint_name=KC.constraint_name )
WHERE RC.CONSTRAINT_SCHEMA='cpremier' AND KC.CONSTRAINT_NAME<>'PRIMARY';

->



