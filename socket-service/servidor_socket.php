<?php

	//////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////  SOCKET SMS  ////////////////////////////////////////////
	///Archivo que establece la comunicacion con el celular para enviar los mensajes /////////
	//////////////////////////////////////////////////////////////////////////////////////////

	require(getRootPath(1).'utils/util.php');
	
	/**
	 * Inicializar variablez que lee archivos de configuracion del archivo property
	 * @var string $host  define la direccion o dominio para establecer la comunicacion
	 * @var string $puerto puerto por el cual se va a establecer la comunicacion
	 */
	escribir_log("Info","servidor_sockets iniciando ...");
	$properties = parse_ini_file(getRootPath(1).'config/configSocketSms.properties');
	$address_phone = $properties["address_socket_phone"];
	$puerto_phone =$properties["puerto_socket_phone"];
	$address_webservice = $properties["address_socket_webservice"];
	$puerto_webservice =$properties["puerto_socket_webservice"];
	$max_read_byte =$properties["max_read_byte"];
	escribir_log("Info","Configuraciones cargadas ...");


/// Crea una referencia socket para poder comunicarse con el celular
$socketCelular = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
/// Establece las propiedades para rebir conexiones de una red y el puerto ejemplo:0.0.0.0 significa recibir conexiones de las redes
socket_bind($socketCelular,$address_phone,$puerto_phone);

escribir_log("Info","Esperando conexion Celular");
// Empezar a escuchar comunicaciones entrantes
socket_listen($socketCelular);


//) acepta matrices de sockets 
socket_select($rCelular = array($socketCelular), $wCelular = array($socketCelular), $eCelular = array($socketCelular),null);

//acepta una conexion con el socket
$connCelular = @socket_accept($socketCelular);

escribir_log("Info","Conexion Satisfactoria con el celular");

/// Crea una referencia socket para poder comunicarse con el web service
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
// Establece las propiedades de la conexion asuminedo que el servicio esta en la misma maquina
socket_bind($socket,$address_webservice,$puerto_webservice);
// Empezar a escuchar comunicaciones entrates del web service
socket_listen($socket);

while(true)
{
	escribir_log("Info","Esperando conexion webservice");
	$conn = false;
	switch(@socket_select($r = array($socket), $w = array($socket), $e = array($socket),null)) 
	{
		case 2:
			escribir_log("Warn","Conexion Rechazada webservice");
			break;
		case 1:

			try
			{
				escribir_log("Info","Conexion establecida con el Web Service");
				$conn = @socket_accept($socket);
				$numero= socket_read($conn, $max_read_byte, PHP_NORMAL_READ); //lee el numero al cual enviar el mensaje

				if(strcmp($numero,"exit")==0)
				{
					escribir_log("Info","Exit ...");
					return 0; //Terminar la aplicacion si el valor leido el exit
				}
				else
				{
					//socket_write($conn," ");	//enviar una valor centella		
					$mensaje= socket_read($conn, $max_read_byte, PHP_NORMAL_READ); //mensaje a enviar por el servidor de aplicaciones
					
					//Enivar el numero de celular
					 if(socket_write($connCelular,$numero,$max_read_byte)=== false)
					 {	
					 	$error=sprintf( "No se puede escribir %s", socket_strerror( socket_last_error() ) );
					 	//escribir_log("Warn",$error);
					 	throw new Exception( $error); 	
					 }
					 else
					 {
					 	//Enviar el mensaje del celular
					 	if(socket_write($connCelular,$mensaje,$max_read_byte)==false)
						{
							$error=sprintf( "No se puede escribir %s", socket_strerror( socket_last_error() ) );
							//escribir_log("Warn",$error);
						 	throw new Exception( $error); 	
						}

					 }

					//Se enviaron los 2 mensajes del numero y el texto correctamente
					escribir_log("Info","Enviando resupuesta al web service");
					socket_write($conn,"success"."\n",$max_read_byte);
					escribir_log("Info","Recibido resupuesta del web service");
					escribir_log("Info","Terminando enviar resupuesta al web service");
 

					escribir_log("Info","numero:".trim($numero).", mensaje:".trim($mensaje));
				}
			}catch(Exception $e)
			{
				$error=sprintf( "Error con la comunicacion: ", $e->getLine() ) . $e->getMessage();
				socket_write($conn,"error"."\n",$max_read_byte);
				socket_close($conn);
			}
		break;
		
		case 0:
			escribir_log("Warn","Tiempo de espera excedido");
			break;
	}


	if ($conn !== false) 
	{
		escribir_log("Info","Finalizo la comunicacion con el cliente web service");
	// communicate over $conn
	}
}

escribir_log("Info","Finalizo el proceso servidor_socket");
socket_close($conn); //terminar conexecion con el cliente

/**
 * Obtiene el path del directorio
 * Nota: Esta funcion esta creada porque en los demonios de php la funcion DOCUMENT_ROOT no funciona
 * @param  int $nivel numero de niveles que se quieren retroceder desde el directorio actual
 * @return string url nueva del directorio solicitado
 */
function getRootPath($nivel)
{
	$path="";
	$claves = preg_split ("/[ \/ ]+/",$_SERVER['PHP_SELF']); 
	for ( $i = 0 ; $i < count($claves)-($nivel+1) ; $i ++) { 
		$path=$path.$claves[$i]."/";
	} 
	return $path;
}


?>