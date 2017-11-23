<?php
    require_once "../lib/nusoap.php";
    $cliente = new nusoap_client("http://localhost/vmserversms/web-service/server-sms.php");
    echo "iniciado ...";
    $error = $cliente->getError();
    if ($error) {
        echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
    }


	$result = $cliente->call("enviarSMS",array("593983528439","mensaje pruebass"));

    if($result)
    {
        switch ($result) {
            case "success":
                echo "el mesaje se envio correctamente";
                break;
            case "noservicesms":
                echo "el servidor de sockets esta fuera de servicio";
                break;
            case "notconnection":
                echo "el servidor de socket no permite conectar por el socket";
                break;
            case "unknown":
                echo "error desconocido";
                break;
            default:
                echo "error sin clasificar";
        }

        echo "";
    }
    else
    {
        echo "</br>Error de conexion";
    }


	

?>