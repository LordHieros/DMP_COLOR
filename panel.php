<?php
    include_once('php/header.php');
    include_once('php/Comprobaciones.php');
    include_once('php/Utils.php');

    Utils::flushSession();

	Comprobaciones::compruebaUsuario();
	
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Panel del ';
	if($_SESSION[CampoSession::ADMINISTRADOR]){
		$html['titulo'] = $html['titulo'] . 'administrador ' . $_SESSION[CampoSession::USUARIO];
	}
	else{
		$html['titulo'] = $html['titulo'] . $_SESSION[CampoSession::USUARIO] ;
	}
	$html['encabezado'] = '<h4> DMP-Color: Plataforma para a xestión de datos nunha unidade de cirurxía colorrectal </h4>' . "\n";
	//Creamos el cuerpo
    try {
        $view = Formulario::formHospital()->makeView();
    } catch (Exception $e){
        Utils::manageException($e);
    }
    if(empty($view)){
        $view = '<h4>No hay datos de hospital, consultar con un administrador</h4>';
    }
    $html['body'] = Utils::getBasicBody() . $view;
	 //Crear las opciones
	$datos[0]['tipo'] = 'redirección';
    $datos[0]['objetivo'] = 'seleccionUsuario.php';
	if($_SESSION[CampoSession::ADMINISTRADOR]){ //Si administrador manda a consulta_usuario
        $datos[0]['nombre'] = 'Consultar usuarios';
        $datos[1]['nombre'] = 'Editar Hospital';
        $datos[1]['tipo'] = 'redirección';
        $datos[1]['objetivo'] = 'editaHospital.php';
	}
	else{ //Si no directamente a consulta_paciente
		$datos[0]['nombre'] = 'Consultar pacientes';
	}
	$html['body'] = $html['body'] . make_navbar($datos);	
	unset($datos);
	echo make_html($html);