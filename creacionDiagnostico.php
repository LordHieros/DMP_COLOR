<?php
    include_once('php/header.php');
    include_once('php/Formulario.php');
    include_once('php/Comprobaciones.php');
    include_once("php/AccesoBD.php");
    include_once("php/Utils.php");

    try{
        Comprobaciones::compruebaNasi(false);
    } catch (Exception $e){
        Utils::manageException($e);
    }
    try{
        Comprobaciones::checkCreaDiagnostico();
    } catch (Exception $e){
        Utils::manageException($e);
    }
    if(isset($_SESSION[CampoSession::FECHA_DIAGNOSTICO])){
        Utils::headTo('consultaDiagnostico.php', null);
    }
    //EMPIEZA EL HTML
    //Ponemos el título de la página
    $html['titulo'] = 'Crear diagnóstico para la filiación de NASI ' . $_SESSION[CampoSession::NASI];
    //Creamos el cuerpo
    try {
        $form = Formulario::formCreaDiagnostico()->makeForm();
    } catch (Exception $e){
        Utils::manageException($e);
    }
    $html['body'] = Utils::getBasicBody() . $form;
    //Crear las opciones
    $datos[0]['tipo'] = 'redirección';
    $datos[0]['objetivo'] = 'consultaFiliacion.php';
    $datos[0]['nombre'] = 'Volver';
    $html['body'] = $html['body'] . make_navbar($datos);
    unset($datos);
    echo make_html($html);
