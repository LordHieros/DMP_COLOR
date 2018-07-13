<?php
	include_once('php/header.php');
    include_once('php/Formulario.php');
    include_once("php/Comprobaciones.php");
    include_once("php/Utils.php");

    try{
        Comprobaciones::checkLogin();
    }catch (Exception $e){
        Utils::manageException($e);
    }

	if(isset($_SESSION[CampoSession::USUARIO])){ Utils::headTo('panel.php', null); } //Para ir al panel de usuario si ya se ha iniciado sesión
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'DMP-Color';
	$html['encabezado'] = '<h4> Plataforma para a xestión de datos nunha unidade de cirurxía colorrectal </h4>';
	//Creamos el cuerpo
    try {
        $form = Formulario::formLogin()->makeForm();
    } catch (Exception $e){
        Utils::manageException($e);
    }
	$html['body'] = Utils::getBasicBody() . $form;
	echo make_html($html);