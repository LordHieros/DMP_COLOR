<?php
    include_once('php/header.php');
    include_once('php/Comprobaciones.php');
    include_once("php/AccesoBD.php");

    Comprobaciones::compruebaNombre();
	
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Pacientes de ' . $_SESSION[CampoSession::NOMBRE];
	//Creamos el cuerpo
	$html['body'] = basic_body();
	//Mostrar pacientes
    $datos['resultado'] = AccesoBD::getFiliaciones();
	$datos['titulo'] = 'Pacientes:';
	$datos['clave'] = 'Claves_NASI';
	$datos['destino'] = 'consulta_filiacion.php';
	$datos['vacio'] = 'No hay pacientes a nombre de este usuario';
	$html['body'] = $html['body'] . make_eleccion($datos);
	unset($datos);
	//Crear las opciones
	$i=0;
	if($_SESSION[CampoSession::ADMINISTRADOR]){
		$datos[$i]['tipo'] = 'redirección';
		$datos[$i]['objetivo'] = 'consulta_usuario.php';
		$datos[$i]['nombre'] = 'Volver';
		$i++;
	}
	$datos[$i]['tipo'] = 'redirección';
	$datos[$i]['objetivo'] = 'paciente.php';
	$datos[$i]['nombre'] = 'Consultar NHC';
	$html['body'] = $html['body'] . make_navbar($datos);	
	unset($datos);
	echo make_html($html);
?>