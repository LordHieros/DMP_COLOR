<?php
	include('php/header.php');
	include('php/make_form.php');
	
	comprueba_nasi();
	
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Programar diagnóstico para el paciente ' . $_SESSION[CampoSession::NASI];
	//Creamos el cuerpo
	$html['body'] = '';
	//Introducimos los datos del formulario
	$form['action'] = 'php/programar_diagnostico_datos.php';
	$form['legend'] = 'Diagnóstico';
	//EMPIEZAN LOS INPUTS DEL FORMULARIO
	//Crear el contenedor de los inputs
	$form['inputs'] = array();
	//Fecha
	$datos['tipo'] = 'date';
	$datos['nombre'] = 'fecha';
	$datos['required'] = true;
	$form['inputs'][] = $datos;
	unset($datos);
	//Botones de "submit"
	$form['submit']['name'] = 'editar';
	$form['submit']['value'] = 'false';
	$form['submit']['etiqueta'] = 'Programar';
	$html['body'] = $html['body'] . make_form($form, false, ''); 
	//Crear las opciones
	$datos[0]['tipo'] = 'redirección';
	$datos[0]['objetivo'] = 'consulta_filiacion.php';
	$datos[0]['nombre'] = 'Volver';
	$html['body'] = $html['body'] . make_navbar($datos);
	unset($datos);
	echo make_html($html);
?>
