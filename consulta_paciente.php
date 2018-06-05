<?php
	include('php/header.php');
	
	comprueba_nombre();
	
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Pacientes de ' . $_SESSION[CampoSession::NOMBRE];
	//Creamos el cuerpo
	$html['body'] = basic_body();
	//Mostrar pacientes
	require 'php/Conexion.php';
	$stmt = Conexion::getpdo()->prepare('SELECT NASI FROM Filiaciones WHERE Usuarios_nombre=:nombre;');
	$res=$stmt->execute(['nombre' => $_SESSION[CampoSession::NOMBRE]]);
	$datos['resultado'] = $stmt->fetchAll();
	$datos['titulo'] = 'Pacientes:';
	$datos['clave'] = 'Claves_NASI';
	$datos['destino'] = 'consulta_filiacion.php';
	$datos['vacio'] = 'No hay pacientes a nombre de este usuario';
	$html['body'] = $html['body'] . make_eleccion($datos);
	unset($datos);
	//Crear las opciones
	$i=0;
	if($_SESSION[CampoSession::ADMINISTRADOR]){
		$datos[$i]['tipo'] = 'redirección';
		$datos[$i]['objetivo'] = 'consulta_usuario.php';
		$datos[$i]['nombre'] = 'Volver';
		$i++;
	}
	$datos[$i]['tipo'] = 'redirección';
	$datos[$i]['objetivo'] = 'paciente.php';
	$datos[$i]['nombre'] = 'Consultar NHC';
	$html['body'] = $html['body'] . make_navbar($datos);	
	unset($datos);
	echo make_html($html);
?>