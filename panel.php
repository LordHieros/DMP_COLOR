<?php
	include('php/header.php');
	$tmp1=$_SESSION[CampoSession::USUARIO]; //Cuando se entra a panel se borran los datos de sesión que no sean nombre de usuario, estado de administrador y mensaje de error
	$tmp2=$_SESSION[CampoSession::ADMINISTRADOR];
	if(isset($_SESSION[CampoSession::ERROR])) $tmp3=$_SESSION[CampoSession::ERROR];
	session_destroy();
	session_start();
	$_SESSION[CampoSession::USUARIO]=$tmp1;
	$_SESSION[CampoSession::ADMINISTRADOR]=$tmp2;
	if(isset($tmp3)){
		$_SESSION[CampoSession::ERROR]=$tmp3;
		unset($tmp3);
	}
	unset($tmp1);
	unset($tmp2);
	
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Panel del ';
	if($_SESSION[CampoSession::ADMINISTRADOR]){
		$html['titulo'] = $html['titulo'] . 'administrador ' . $_SESSION[CampoSession::USUARIO];
	}
	else{
		$html['titulo'] = $html['titulo'] . $_SESSION[CampoSession::USUARIO] ;
	}
	$html['encabezado'] = '<h4> DMP-Color: Plataforma para a xestión de datos nunha unidade de cirurxía colorrectal </h4>';
	//Creamos el cuerpo
	$html['body'] = '';
	if(isset($_SESSION[CampoSession::ERROR])){ //En caso de que exista un mensaje de error, imprimirlo y borrarlo
		$html['body'] = $html['body'] . '<p class="error">' . $_SESSION[CampoSession::ERROR] . '</p>';
		unset($_SESSION[CampoSession::ERROR]);
	}
	 //Crear las opciones
	$datos[0]['tipo'] = 'redirección';
	if($_SESSION[CampoSession::ADMINISTRADOR]){ //Si administrador manda a consulta_usuario
		$datos[0]['objetivo'] = 'consulta_usuario.php';
		$datos[0]['nombre'] = 'Consultar usuarios';
	}
	else{ //Si no directamente a consulta_paciente
		$datos[0]['objetivo'] = 'consulta_paciente.php';
		$datos[0]['nombre'] = 'Consultar pacientes';
	}
	$html['body'] = $html['body'] . make_navbar($datos);	
	unset($datos);
	echo make_html($html);
?>