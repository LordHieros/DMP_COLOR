<?php
	include('php/header.php');
	include('php/make_form.php');
    include_once('php/Comprobaciones.php');
    include_once("php/AccesoBD.php");

    Comprobaciones::compruebaNombre();
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Crear NASI de paciente para ' . $_SESSION[CampoSession::NOMBRE];
	//Creamos el cuerpo
	$html['body'] = '';
	//Introducimos los datos del formulario
	$form['action'] = 'php/paciente_datos.php';
	$form['legend'] = 'Paciente';
	//EMPIEZAN LOS INPUTS DEL FORMULARIO
	//Crear el contenedor de los inputs
	$form['inputs'] = array();
	//NHC
	$datos['tipo'] = 'text';
	$datos['nombre'] = 'NHC';
	$datos['required'] = true;
	$form['inputs'][] = $datos;
	unset($datos);
	//Botones de "submit"
	$form['submit']['name'] = 'enviar';
	$form['submit']['value'] = '';
	$html['body'] = $html['body'] . make_form($form, false, ''); 
	 //Crear las opciones
	$datos[0]['tipo'] = 'redirección';
	$datos[0]['objetivo'] = 'consulta_paciente.php';
	$datos[0]['nombre'] = 'Volver';
	$html['body'] = $html['body'] . make_navbar($datos);
	unset($datos);
	echo make_html($html);
?>