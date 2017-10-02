<?php
	$properties = parse_ini_file('../config/configWebService.properties');
	$host=$properties["host"];
	$puerto=$properties["puerto"];
	echo "host".$host." , puerto  ".$puerto ." ";
	//var_dump($properties);
?>