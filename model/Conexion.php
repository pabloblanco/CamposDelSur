<?php
 //$conexion = new mysqli("localhost", "root", "", "hostalfa_bdcamposdelsur");

// $conexion = new mysqli("localhost", "root", "masf10400894", "cementerio");

// Local en servidor Levitico
//$conexion = new mysqli("localhost", "hostalfa_root", "SwuwEp_I@reNetHu1afU", "hostalfa_bdcamposdelsur");
$conexion = new mysqli("localhost", "aplicaci_camposd", "Campos2021", "aplicaci_camposdelsur");
// Remoto en Servidor SistemaIEC.com directorio Cementerios
//$conexion = new mysqli("localhost", "hostalfa", "SwuwEp_I@reNetHu1afU", "hostalfa_bdcementerios");

// Local en servidor Exodo
// $conexion = new mysqli("localhost", "root", "", "cementerio");

	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
