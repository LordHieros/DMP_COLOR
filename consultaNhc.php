<?php
	include_once('php/header.php');
    include_once('php/Formulario.php');
    include_once('php/Comprobaciones.php');
    include_once("php/AccesoBD.php");
    include_once("php/Utils.php");

    Comprobaciones::compruebaNombre(false);
    try{
        Comprobaciones::checkNHC();
    } catch (Exception $e){
        Utils::manageException($e);
    }
    if(isset($_SESSION[CampoSession::NASI])){
        Utils::headTo('consultaFiliacion.php', null);
    } //Para ir al panel de usuario si ya se ha iniciado sesión
    //EMPIEZA EL HTML
    //Ponemos el título de la página
    $html['titulo'] = 'Crear NASI de paciente para ' . $_SESSION[CampoSession::NOMBRE_USUARIO];
    //Creamos el cuerpo
    try {
        $form = Formulario::formNHC()->makeForm();
    } catch (Exception $e){
        Utils::manageException($e);
    }
    $html['body'] = Utils::getBasicBody() . $form;
    //Crear las opciones
    $datos[0]['tipo'] = 'redirección';
    $datos[0]['objetivo'] = 'seleccionPaciente.php';
    $datos[0]['nombre'] = 'Volver';
    $html['body'] = $html['body'] . make_navbar($datos);
    unset($datos);
    echo make_html($html);