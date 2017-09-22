<?php

//Establecer comunicacion con el socket del celular

//socket_bind($socketCelular,'127.0.0.1',5000);

$socketCelular = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_bind($socketCelular,'0.0.0.0',5000);
socket_listen($socketCelular);
echo "Esperando conexión Celular\n\n";
socket_select($rCelular = array($socketCelular), $wCelular = array($socketCelular), $eCelular = array($socketCelular),null);
$connCelular = @socket_accept($socketCelular);
echo "Conexion Satisfactoria con el celular \n\n";



/////////////////////////////////////////////////////
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_bind($socket,'127.0.0.1',65500);
socket_listen($socket);

while(true)
{
	echo "Esperando conexión\n\n";
	$conn = false;
	switch(@socket_select($r = array($socket), $w = array($socket), $e = array($socket),null)) {
		case 2:
			echo "Conexión rechazada!\n\n";
			break;
		case 1:
			echo "Conexión aceptada!\n\n";
			$conn = @socket_accept($socket);
			$numero= socket_read($conn, 2048); //lee el numero al cual enviar el mensaje
			//echo "numero leido \n";
			socket_write($conn," ");	//enviar una valor centella		
			//socket_write($conn,"");
			//echo "valor escrito \n";
			$mensaje= socket_read($conn, 2048); //mensaje a enviar por el servidor de aplicaciones
			//echo "valor leido \n";
			
			socket_write($connCelular,$numero);	
			socket_read($connCelular, 2048);			
			socket_write($connCelular,$mensaje);			
			echo "num: ".$numero." men: ".$mensaje."\n\n";
			break;
		case 0:
			echo "Tiempo de espera excedido!\n\n";
			break;
	}


	if ($conn !== false) {
	// communicate over $conn
	}
}

?>