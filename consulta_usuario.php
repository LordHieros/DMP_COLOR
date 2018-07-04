<?php
    include_once('php/header.php');
    include_once('php/Comprobaciones.php');
    include_once("php/AccesoBD.php");

    Comprobaciones::compruebaNombre();
	if(isset($_SESSION[CampoSession::NOMBRE])){ header("location: consulta_paciente.php"); }
	
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Usuarios de la plataforma';
	//Creamos el cuerpo
	$html['body'] = '';
	if(isset($_SESSION[CampoSession::ERROR])){
		$html['body'] = $html['body'] . '<h4 class="error">' . $_SESSION[CampoSession::ERROR] . '</h4>';
		unset($_SESSION[CampoSession::ERROR]);
	}
	//Mostrar usuarios
	$datos['resultado'] = AccesoBD::getUsuarios();
	$datos['titulo'] = 'Usuarios:';
	$datos['clave'] = 'nombre';
	$datos['destino'] = 'consulta_paciente.php';
	$datos['vacio'] = 'No hay usuarios (avisar al servicio técnico)';
	$html['body'] = $html['body'] . make_eleccion($datos);
	unset($datos);
	//Crear las opciones
	$datos[0]['tipo'] = 'redirección';
	$datos[0]['objetivo'] = 'usuario.php';
	$datos[0]['nombre'] = 'Crear usuario';
	$html['body'] = $html['body'] . make_navbar($datos);	
	unset($datos);
	echo make_html($html);
?>