<?php
	include('php/header.php');
	include('php/make_form.php');
	
	comprueba_nasi();
	
	require 'php/Conexion.php';
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM Filiaciones WHERE NASI=:nasi;');
	$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI]]);
	$resultado = $stmt->fetchAll();
	if (count($resultado) > 0) { //Comprobar si existe paciente
		if($resultado[0]["cerrado"]==1){ //Si se llega a un expediente cerrado se redirige a la consulta
			header("location: consultaFiliacion.php");
			die;
		}
		else{
			$editar = true;
		}
	}
	else{
		$editar = false;
	}
	
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	if($editar){
		$html['titulo'] = 'Editar filiación del paciente ' . $_SESSION[CampoSession::NASI];
	}
	else{
		$html['titulo'] = 'Crear filiación del paciente ' . $_SESSION[CampoSession::NASI];
	}
	//Creamos el cuerpo
	$html['body'] = '';
	//Introducimos los datos del formulario
	$form['action'] = 'php/filiacion_datos.php';
	$form['legend'] = 'Filiacion';
	//EMPIEZAN LOS INPUTS DEL FORMULARIO
	//Crear el contenedor de los inputs
	$form['inputs'] = array();
	$datos['etiqueta'] = 'Filiacion';
	//Sexo
	$datos['nest'][0]['tipo'] = 'radio';
	$datos['nest'][0]['nombre'] = 'sexo';
	$datos['nest'][0]['valores'] = ['Varón', 'Mujer', 'Otro'];
	$datos['nest'][0]['required'] = true;
	//Edad
	$datos['nest'][1]['tipo'] = 'number';
	$datos['nest'][1]['nombre'] = 'edad';
	$datos['nest'][1]['opt'] = 'min="0" max="200"';
	$datos['nest'][1]['required'] = true;
	//Talla
	$datos['nest'][2]['tipo'] = 'number';
	$datos['nest'][2]['etiqueta'] = 'Talla (cm)';
	$datos['nest'][2]['nombre'] = 'talla';
	$datos['nest'][2]['opt'] = 'min="10" max="300"';
	$datos['nest'][2]['required'] = true;
	//Peso
	$datos['nest'][3]['tipo'] = 'number';
	$datos['nest'][3]['etiqueta'] = 'Peso (Kg)';
	$datos['nest'][3]['nombre'] = 'peso';
	$datos['nest'][3]['opt'] = 'min="1" max="1000"';
	$datos['nest'][3]['required'] = true;
	$form['inputs'][] = $datos;
	unset($datos);
	//Botones de "submit"
	$form['submit']['name'] = 'editar';
	if($editar){ //Si se está editando Actualizar, si no Enviar
		$form['submit']['value'] = 'true';
		$form['submit']['etiqueta'] = 'Actualizar';
	}
	else{
		$form['submit']['value'] = 'false';
		$form['submit']['etiqueta'] = 'Enviar';
	}
	$html['body'] = $html['body'] . make_form($form, $editar, $resultado); 
	//Crear las opciones
	$datos[0]['tipo'] = 'redirección';
	$datos[0]['objetivo'] = 'consultaFiliacion.php';
	$datos[0]['nombre'] = 'Volver';
	$html['body'] = $html['body'] . make_navbar($datos);
	unset($datos);
	echo make_html($html);
?>
