<?php
	include('php/header.php');
	include('php/make_form.php');
	
	if(!$_SESSION[CampoSession::ADMINISTRADOR]){
		$_SESSION[CampoSession::ERROR] = 'Solo los administradores pueden crear usuarios';
		header('location: panel.php');
	}
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Crear usuario';
	//Creamos el cuerpo
	$html['body'] = '';
	//Introducimos los datos del formulario
	$form['action'] = 'php/usuario_datos.php';
	$form['legend'] = 'Usuario';
	//EMPIEZAN LOS INPUTS DEL FORMULARIO
	//Crear el contenedor de los inputs
	$form['inputs'] = array();
	//Nombre
	$datos['tipo'] = 'text';
	$datos['nombre'] = 'nombre';
	$datos['required'] = true;
	$form['inputs'][] = $datos;
	unset($datos);
	//Contraseña
	$datos['tipo'] = 'password';
	$datos['etiqueta'] = 'Contraseña';
	$datos['nombre'] = 'contraseña1';
	$datos['required'] = true;
	$form['inputs'][] = $datos;
	unset($datos);
	//Contraseña repetida
	$datos['tipo'] = 'password';
	$datos['etiqueta'] = 'Repetir contraseña';
	$datos['nombre'] = 'contraseña2';
	$datos['required'] = true;
	$form['inputs'][] = $datos;
	unset($datos);
	//Botones de "submit"
	$form['submit']['name'] = 'enviar';
	$form['submit']['value'] = '';
	$html['body'] = $html['body'] . make_form($form, false, ''); 
	 //Crear las opciones
	$datos[0]['tipo'] = 'redirección';
	$datos[0]['objetivo'] = 'consulta_usuario.php';
	$datos[0]['nombre'] = 'Volver';
	$html['body'] = $html['body'] . make_navbar($datos);
	unset($datos);
	echo make_html($html);
?>
