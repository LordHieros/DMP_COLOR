<?php
	include('header.php');
	comprueba_diagnostico();
	require 'Conexion.php';
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM Intervenciones WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND fecha=:fecha;');
	$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fecha' => $_POST["fecha"]]);
	$resultado = $stmt->fetchAll();
	if(count($resultado) > 0){
		$_SESSION[CampoSession::ERROR] = 'Ya existe una intervencion programada para el dia ' . $_POST["fecha"] . ' para este diagnóstico';
		header("location: ../consultaDiagnostico.php");
	}
	else{
		$stmt = Conexion::getpdo()->prepare('INSERT INTO Intervenciones(Filiaciones_NASI, Diagnosticos_fecha, fecha, cerrado) VALUES (:nasi, :fechaDiagnostico, :fecha, :cerrado);');
		$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fecha' => $_POST["fecha"], 'cerrado' => 0]);
		$_SESSION[CampoSession::FECHA_INTERVENCION] = $_POST["fecha"];
		header("location: ../consulta_intervencion.php");
	}
?>