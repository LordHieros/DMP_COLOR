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
	try{
		if(Comprobaciones::editaHospital()){
			Utils::headTo('panel.php', null);
		}
	} catch (Exception $e){
		Utils::manageException($e);
	}
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Editar datos de hospital';
	//Creamos el cuerpo
	try {
		$form = Formulario::formHospital()->makeForm();
	} catch (Exception $e){
		Utils::manageException($e);
	}
	$html['body'] = Utils::getBasicBody() . $form;
	//Crear las opciones
	$datos[0]['tipo'] = 'redirección';
	$datos[0]['objetivo'] = 'panel.php';
	$datos[0]['nombre'] = 'Volver';
	$html['body'] = $html['body'] . make_navbar($datos);
	echo make_html($html);