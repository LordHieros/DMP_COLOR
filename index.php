<?php
	include('php/header.php');
    include('php/MakeForm.php');
    include('php/Formulario.php');

	if(isset($_SESSION[CampoSession::USUARIO])){ header("location: panel.php"); } //Para ir al panel de usuario si ya se ha iniciado sesión
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'DMP-Color';
	$html['encabezado'] = '<h4> Plataforma para a xestión de datos nunha unidade de cirurxía colorrectal </h4>';
	//Creamos el cuerpo
	$html['body'] = '';
	$html['body'] = $html['body'] . MakeForm::make(Formulario::formLogin());
	if(isset($_SESSION[CampoSession::ERROR])){ //En caso de que exista un mensaje de error, imprimirlo y borrarlo
		$html['body'] = $html['body'] . '<p class="error">' . $_SESSION[CampoSession::ERROR] . '</p>';
		unset($_SESSION[CampoSession::ERROR]);
	}
	echo make_html($html);
?>