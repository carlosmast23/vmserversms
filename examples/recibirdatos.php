<?php // Ejemplo aprenderaprogramar.com
// Escribimos una primera línea en fichero.txt
// fichero.txt tienen que estar en la misma carpeta que el fichero php
 $numero = $_GET['numero'];
 $mensaje = $_GET['mensaje'];
 echo $numero;
 echo $mensaje;
 
 $mensaje="numero".$numero." mensaje= ".$mensaje;
echo "pagina de ejemplo";
$fp = fopen("fichero.txt", "w");
fputs($fp,$mensaje);
fclose($fp);
?>