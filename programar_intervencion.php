<?php
	include('php/header.php');
	include('php/make_form.php');
	
	comprueba_diagnostico();
	
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Programar intervención para el paciente ' . $_SESSION[CampoSession::NASI] . ' para el diagnóstico del ' .$_SESSION[CampoSession::FECHA_DIAGNOSTICO];
	//Creamos el cuerpo
	$html['body'] = '';
	//Introducimos los datos del formulario
	$form['action'] = 'php/programar_intervencion_datos.php';
	$form['legend'] = 'Intervencion';
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
	$datos[0]['objetivo'] = 'consulta_diagnostico.php';
	$datos[0]['nombre'] = 'Volver';
	$html['body'] = $html['body'] . make_navbar($datos);
	unset($datos);
	echo make_html($html);
?>
