<?php
	include_once('php/header.php');
	include_once('php/Comprobaciones.php');
	include_once("php/AccesoBD.php");
	include_once("php/Utils.php");
	include_once("php/CampoSession.php");

    try {
        Comprobaciones::compruebaDiagnostico(true);
    } catch (Exception $e){
        Utils::manageException($e);
    }
	if(isset($_SESSION[CampoSession::FECHA_DIAGNOSTICO])){ Utils::headTo('consultaDiagnostico.php', null); }
	
	//Borramos fechaIntervencion, de haber una elegida
    unset($_SESSION[CampoSession::FECHA_INTERVENCION]);
	
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Datos del paciente de NASI ' . $_SESSION[CampoSession::NASI];
	//Creamos el cuerpo
	try {
		$datos['resultado'] = AccesoBD::getDiagnosticos();
	} catch (Exception $e){
		Utils::manageException($e);
	}
	$html['body'] = Utils::getBasicBody();
	//Mostrar diagnósticos
	$datos['titulo'] = 'Diagnósticos:';
	$datos['clave'] = CampoSession::FECHA_DIAGNOSTICO;
	$datos['destino'] = '';
	$datos['vacio'] = 'Este paciente no tiene diagnósticos';
	$html['body'] = $html['body'] . make_eleccion($datos);
	unset($datos);
	//Crear las opciones
	$opcion['tipo'] = 'redirección';
	$opcion['objetivo'] = 'seleccionPaciente.php';
	$opcion['nombre'] = 'Volver';
	$datos[] = $opcion;
	unset($opcion);
	$opcion['tipo'] = 'redirección';
	$opcion['objetivo'] = 'creacionDiagnostico.php';
	$opcion['nombre'] = 'Crear diagnóstico';
	$datos[] = $opcion;
	unset($opcion);
	$html['body'] = $html['body'] . make_navbar($datos);	
	unset($datos);
	echo make_html($html);
