<?php
	include('php/header.php');
	
	comprueba_nasi();
	
	//Borramos fechaDiagnostico, de haber una elegida
	unset($_SESSION[CampoSession::FECHA_DIAGNOSTICO]);
	
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Datos del paciente';
	//Creamos el cuerpo
	$html['body'] = basic_body();
	//Buscamos el paciente en la base de datos
	require 'php/Conexion.php';
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM Filiaciones WHERE NASI=:nasi;');
	$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI]]);
	$resultado = $stmt->fetchAll();
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM EXITUS WHERE Filiaciones_NASI=:nasi;');
	$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI]]);
	$resultado_exitus = $stmt->fetchAll();
	$exitus = (count($resultado_exitus) > 0);
	if (count($resultado) > 0) { //Comprobar si existe paciente
		$consultas = array();
		//NASI
		$datos['nombre'] = 'NASI';
		$datos['resultado'] = $_SESSION[CampoSession::NASI];
		$consultas[] = $datos;
		unset($datos);
	}
	//Mostrar diagnósticos
	$stmt = Conexion::getpdo()->prepare('SELECT fecha FROM Diagnosticos WHERE Filiaciones_NASI=:nasi;');
	$res=$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI]]);
	$datos['resultado'] = $stmt->fetchAll();
	$datos['titulo'] = 'Diagnósticos:';
	$datos['clave'] = 'fecha';
	$datos['nombre'] = 'fechaDiagnostico';
	$datos['destino'] = 'consulta_diagnostico.php';
	$datos['vacio'] = 'Este paciente no tiene diagnósticos';
	$html['body'] = $html['body'] . make_eleccion($datos);
	unset($datos);
	//Crear las opciones
	$opcion['tipo'] = 'redirección';
	$opcion['objetivo'] = 'consulta_paciente.php';
	$opcion['nombre'] = 'Volver';
	$datos[] = $opcion;
	unset($opcion);
	if($_SESSION[CampoSession::ADMINISTRADOR]){ //Si no es editable pero se es administrador se puede abrir el expediente
		$opcion['tipo'] = 'post';
		$opcion['objetivo'] = 'php/filiacion_datos.php';
		$opcion['valor'] = 'false';
		$opcion['mensaje'] = 'Habilitar edición de filiación';
		$opcion['nombre'] = 'cerrar';
		$datos[] = $opcion;
		unset($opcion);
	}
	$opcion['tipo'] = 'redirección';
	$opcion['objetivo'] = 'diagnostico.php';
	$opcion['nombre'] = 'Crear diagnóstico';
	$datos[] = $opcion;
	unset($opcion);
	$html['body'] = $html['body'] . make_navbar($datos);	
	unset($datos);
	echo make_html($html);
?>
