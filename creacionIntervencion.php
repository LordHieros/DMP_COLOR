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
        Comprobaciones::checkCreaIntervencion();
    } catch (Exception $e){
        Utils::manageException($e);
    }
    if(isset($_SESSION[CampoSession::FECHA_INTERVENCION])){
        Utils::headTo('consultaIntervencion.php', null);
    }
    //EMPIEZA EL HTML
    //Ponemos el título de la página
    $html['titulo'] = 'Crear intervención para el diagnóstico del ' . $_SESSION[CampoSession::FECHA_DIAGNOSTICO] . ' del paciente de NASI ' . $_SESSION[CampoSession::NASI];
    //Creamos el cuerpo
    try {
        $form = Formulario::formCreaIntervencion()->makeForm();
    } catch (Exception $e){
        Utils::manageException($e);
    }
    $html['body'] = Utils::getBasicBody() . $form;
    //Crear las opciones
    $datos[0]['tipo'] = 'redirección';
    $datos[0]['objetivo'] = 'consultaDiagnostico.php';
    $datos[0]['nombre'] = 'Volver';
    $html['body'] = $html['body'] . make_navbar($datos);
    unset($datos);
    echo make_html($html);
