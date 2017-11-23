<?php

   	$properties = parse_ini_file('../config/configWebService.properties');
   	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
   	$host = $properties["host"];
	$puerto =$properties["puerto"];
	$connect_socket=socket_connect($socket, $host, $puerto);


	$numero="+593997426212";
	$mensaje="mensaje pruebass";

	if ($connect_socket)
		{	
			echo("Conectado correctamente con el servidorsms ..."."Info");
			socket_write($socket,$numero."\n");
			//socket_read($socket, 2048);
			socket_write($socket,$mensaje."\n");

			//Leer el estado del envio
			$respuesta=socket_read($socket, 1024);
			if(strcmp (trim($respuesta),"success")  != 0)
			{
				return "No hay conexion con el celular";
			}			
			socket_close($socket);
			echo ("enviado mensaje al servidor {".$numero.":".$mensaje."}"."Info");

		}
		else
		{
			$error='Error : '.socket_strerror(socket_last_error());
		    echo ($error."Error");
			socket_close($socket);
		}

?>