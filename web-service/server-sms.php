<?php

	//////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////  WEB SERVICE SMS ////////////////////////////////////////
	///Archivo de los web service que permite establecer una comunicacion para el servicio sms
	//////////////////////////////////////////////////////////////////////////////////////////
	/*
	Libreria necesaria para implementar el web service
	 */
	require_once "../lib/nusoap.php";
	include_once "../utils/util.php";

	
	/**
	 * Metodo que permite registrar el mensaje y el numero a enviar
	 * @param  string $numero numero de celular incluido el codigo de pais para enviar
	 * @param  string $mensaje cuerpo del mensaje a enviar tiene que tener 150 caracteres
	 * @return string letras "true" o "false" dependiendo si el proceso finaliza correctamente
	 */
	function enviarSMS($numero,$mensaje)
	{
		/**
		 * Inicializar variablez que lee archivos de configuracion del archivo property
		 * @var string $host  define la direccion o dominio para establecer la comunicacion
		 * @var string $puerto puerto por el cual se va a establecer la comunicacion
		 */
		escribir_log_normal("Iniciando funcion web service ...","Info");
		$properties = parse_ini_file('../config/configWebService.properties');
		$host = $properties["host"];
		$puerto =$properties["puerto"];
		escribir_log_normal("Cargandando propiedades para la conexion ".$host.":".$puerto,"Info");
		
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		$connect_socket=socket_connect($socket, $host, $puerto);

		if ($connect_socket)
		{	
			escribir_log_normal("Conectado correctamente con el servidorsms ...","Info");
			socket_write($socket,$numero."\n");
			//socket_read($socket, 2048);
			socket_write($socket,$mensaje."\n");
			socket_close($socket);
			escribir_log_normal("enviado mensaje al servidor {".$numero.":".$mensaje."}","Info");
			return Constants::CODE_SUCESS;
		}
		else
		{
			$error='Error : '.socket_strerror(socket_last_error());
		    escribir_log_normal($error,"Error");
			socket_close($socket);
			return Constants::CODE_NOTCONNECTION;
			

		}
		echo "2";
		escribir_log_normal("4","Error");
		return Constants::CODE_UNKNOWN;
	}
      
	/**
	 * Registrar e inicialar los metodos disponibles
	 */
	$server = new soap_server();
    $server->register("enviarSMS");
	$server->service($HTTP_RAW_POST_DATA);
?>