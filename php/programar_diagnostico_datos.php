<?php
	include('header.php');
	comprueba_nasi();
	require 'Conexion.php';
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM Diagnosticos WHERE Filiaciones_NASI=:nasi AND fecha=:fecha;');
	$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_POST["fecha"]]);
	$resultado = $stmt->fetchAll();
	if(count($resultado) > 0){
		$_SESSION[CampoSession::ERROR] = 'Ya existe un diagnóstico programado para el dia ' . $_POST["fecha"];
		header("location: ../consulta_filiacion.php");
	}
	else{
		$stmt = Conexion::getpdo()->prepare('INSERT INTO Diagnosticos(Filiaciones_NASI, fecha, cerrado) VALUES (:nasi, :fechaDiagnostico, :fecha, :cerrado);');
		$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_POST["fecha"], 'cerrado' => 0]);
		$_SESSION[CampoSession::FECHA_INTERVENCION] = $_POST["fecha"];
		header("location: ../consulta_diagnostico.php");
	}
?>