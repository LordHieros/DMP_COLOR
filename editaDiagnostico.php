<?php
	include_once('php/header.php');
	include_once('php/Formulario.php');
	include_once('php/Comprobaciones.php');
	include_once("php/AccesoBD.php");
	include_once("php/Utils.php");

    try{
        Comprobaciones::compruebaDiagnostico(false);
    } catch (Exception $e){
        Utils::manageException($e);
    }
	try{
		if(Comprobaciones::editaDiagnostico()){
		    Utils::headTo('consultaDiagnostico.php', null);
        }
	} catch (Exception $e){
		Utils::manageException($e);
	}
	//EMPIEZA EL HTML
    //Añadimos opciones de cabecera
     $html['head'] = '<script src="./js/Mostrar.js"> </script>';
    //Ponemos el título de la página
    $html['titulo'] = 'Datos del diagnóstico del ' . $_SESSION[CampoSession::FECHA_DIAGNOSTICO] . ' de la filiación ' . $_SESSION[CampoSession::NASI];
    //Creamos el cuerpo
    try {
        $form = Formulario::formDiagnostico()->makeForm();
    } catch (Exception $e){
        Utils::manageException($e);
    }
	$html['body'] = Utils::getBasicBody() . $form;
	//Crear las opciones
	$datos[0]['tipo'] = 'redirección';
	$datos[0]['objetivo'] = 'consultaDiagnostico.php';
	$datos[0]['nombre'] = 'Volver';
	$html['body'] = $html['body'] . make_navbar($datos);
	echo make_html($html);
