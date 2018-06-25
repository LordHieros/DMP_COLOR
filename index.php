<?php
	include('php/header.php');
	include('php/login.php'); // Includes Login Script
    include('php/MakeForm.php');
    include('php/Formulario.php');

	if(isset($_SESSION[CampoSession::USUARIO])){ header("location: panel.php"); } //Para ir al panel de usuario si ya se ha iniciado sesión
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'DMP-Color';
	$html['encabezado'] = '<h4> Plataforma para a xestión de datos nunha unidade de cirurxía colorrectal </h4>';
	//Creamos el cuerpo
	$html['body'] = '';
	//Introducimos los datos del formulario
	$form['action'] = '';
	$form['legend'] = 'Iniciar sesión';
	//EMPIEZAN LOS INPUTS DEL FORMULARIO
	//Crear el contenedor de los inputs
	$form['inputs'] = array();
	$datos['etiqueta'] = 'Login';
	//Nombre de usuario
	$datos['nest'][0]['tipo'] = 'text';
	$datos['nest'][0]['etiqueta'] = 'Nombre de usuario';
	$datos['nest'][0]['nombre'] = 'nombre';
	$datos['nest'][0]['required'] = true;
	//Contraseña
	$datos['nest'][1]['tipo'] = 'password';
	$datos['nest'][1]['nombre'] = 'contraseña';
	$datos['nest'][1]['required'] = true;
	$form['inputs'][] = $datos;
	unset($datos);
	//Botones de "submit"
	$form['submit']['name'] = 'Iniciar';
	$form['submit']['value'] = '';
	$html['body'] = $html['body'] . MakeForm::make(Formulario::formLogin()); //make_form($form, false, '');
	if(isset($_SESSION[CampoSession::ERROR])){ //En caso de que exista un mensaje de error, imprimirlo y borrarlo
		$html['body'] = $html['body'] . '<p class="error">' . $_SESSION[CampoSession::ERROR] . '</p>';
		unset($_SESSION[CampoSession::ERROR]);
	}
	echo make_html($html);
?>