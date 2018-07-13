<?php
	include_once('php/header.php');
	include_once('php/Formulario.php');
	include_once('php/Comprobaciones.php');
	include_once("php/AccesoBD.php");
	include_once("php/Utils.php");

	try{
		Comprobaciones::compruebaUsuario();
	} catch (Exception $e){
		Utils::manageException($e);
	}
    if(!$_SESSION[CampoSession::ADMINISTRADOR]){ //
        $error='Solo los administradores pueden crear usuarios';
        Utils::headTo('panel.php', $error);
    }
	try{
		if(Comprobaciones::checkCreaUsuario()){
		    Utils::headTo('seleccionUsuario.php', null);
        }
	} catch (Exception $e){
		Utils::manageException($e);
	}
	if(isset($_SESSION[CampoSession::NOMBRE_USUARIO])){
		Utils::headTo('seleccionUsuario.php', null);
	}
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Crear usuario';
	//Creamos el cuerpo
	try {
		$form = Formulario::formCreaUsuario()->makeForm();
	} catch (Exception $e){
		Utils::manageException($e);
	}
	$html['body'] = Utils::getBasicBody() . $form;
	//Crear las opciones
	$datos[0]['tipo'] = 'redirección';
	$datos[0]['objetivo'] = 'seleccionUsuario.php';
	$datos[0]['nombre'] = 'Volver';
	$html['body'] = $html['body'] . make_navbar($datos);
	unset($datos);
	echo make_html($html);
