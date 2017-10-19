<?php

class Constants 
{
  const CODE_SUCESS = "success";
  const CODE_NOSERVICE= "noservicesms";
  const CODE_NOTCONNECTION= "notconnection";
  const CODE_UNKNOWN="unknown";
} 


/**
 * Escribe lo que le pasen a un archivo de logs
 * @param string $cadena texto a escribir en el log
 * @param string $tipo texto que indica el tipo de mensaje. Los valores normales son Info, Error,  
 *                                       Warn Debug, Critical
 */
function escribir_log($cadena,$tipo)
{
	$arch = fopen(getRootPath(1)."/logs/logsms".date("Y-m-d").".log", "a+"); 
	$message="[".date("Y-m-d H:i:s")." - $tipo ] ".$cadena."\n";
	//Imprimir los mensajes en la consola
	echo $message; 
	fwrite($arch,$message);
	fclose($arch);
}

 
function escribir_log_normal($cadena,$tipo)
{
	$arch = fopen("../logs/logwebservice".date("Y-m-d").".log", "a+"); 
	$message="[".date("Y-m-d H:i:s")." - $tipo ] ".$cadena."\n";
	//Imprimir los mensajes en la consola
	//echo $message; 
	fwrite($arch,$message);
	fclose($arch);
}


?>