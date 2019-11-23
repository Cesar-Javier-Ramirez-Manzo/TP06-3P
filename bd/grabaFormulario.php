<?php 
$base= $_POST['bd'];     /*   recuperar cierta info con POST - */ 
$tabla= $_POST['tabla'];
$num_campos = $_POST['num_campos'];
?>
<html>
  <!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<head>
<title>Datos de Formulario Dinamico </title>
</head>
<link rel="stylesheet" href="./css/style.css">
<body>

<?php   
   echo "<h3>Datos de Formulario Dinamico de " . $tabla."</h3>";
   $listaCampos[]="";  /*  vector para Nombre campos */
   $listaValores[]="";  /*  vector para valores campos */
   
   $i=0;
   foreach($_POST as $nombre_campo => $valor)   /* barrido a todos los objetos cachados con POST */
   {
      if(strpos($nombre_campo, "campo_") === 0) {   /* de interes solo con prefijo "campo_"  */
			$listaCampos[$i]=substr($nombre_campo,6);   /* obtner nombre del campo*/
			$listaValores[$i]=$valor;
			$i++;
	  }
	  else {
     	  echo "> ". $nombre_campo . "=" . $valor . "<br/>";  /* solo se imprime en pantalla, de aqui se rescataria 
		                                                         a los que tienen prefijo "TipoDato_Campo"  */
	  }
	}
	
	for ($j=0;$j<$i;$j++)   /* para formar la cadena SQL para insertar el registro  */
	{
		if ($j>0) 
		{
			  $strCampos= $strCampos . "," . $listaCampos[$j];   /* lista de campos, separada por ","   */
			  $strValores=$strValores . ",'" . $listaValores[$j] . "'";  /* lista de valores */
		}
		else {
				$strCampos= $listaCampos[$j];
				$strValores="'" . $listaValores[$j] . "'";
		}
		 /* aun faltaria procesar la listaValores para que dependiendo del tipodeDato del campo, 
		    se le agregue o no el respectivo Delimitador   */
   }  

    $insertSQL = "INSERT INTO ".$tabla. " (" . $strCampos . ") VALUES (" . $strValores . ");";
	echo "<h3>SQL resultante:</h3>"; 
	
	/* se insertaria el registro, notificando exito o mensaje de error  */	
	$conexion = mysqli_connect("127.0.0.1:3306",'root','',$base);
	$result = $conexion->query($insertSQL);
	if($result){
		echo "<div class='alert alert-success'>SQL Success: ". $insertSQL."</div>";
	}else{
		echo "<div class='alert alert-danger'>SQL Error: ". $insertSQL."</div>";
	}
    echo "<br/><a href='##' onClick='history.go(-1); return false;'>Regresar</a>";
?> 
 </body>
</html>  

