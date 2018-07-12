<?php
	include('header.php');
	comprueba_diagnostico();
	
	require 'Conexion.php';
	if(isset($_POST['borrar'])){ //En caso de estar borrando un seguimiento
		$stmt = Conexion::getpdo()->prepare('DELETE FROM Complicaciones where Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND Seguimientos_fecha=:fecha;');
		$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fecha' => $_POST["borrar"]]);
		$stmt = Conexion::getpdo()->prepare('DELETE FROM Seguimientos where Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND fecha=:fecha;');
		$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fecha' => $_POST["borrar"]]);
	}
	else{ //En caso de estar introduciendolo
		$stmt = Conexion::getpdo()->prepare('SELECT * FROM Seguimientos where Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND fecha=:fecha;');
		$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fecha' => $_POST["fecha"]]);
		$resultado = $stmt->fetchAll();
		if(count($resultado) > 0){
			$_SESSION[CampoSession::ERROR] = 'Ya existe un seguimiento con esa fecha para este diagnóstico de este paciente';
		}
		else{ //Inserta el seguimiento en la base de datos, junto con sus complicaciones
			$stmt = Conexion::getpdo()->prepare('INSERT INTO Seguimientos(Filiaciones_NASI, Diagnosticos_fecha, fecha) VALUES (:nasi, :fechaDiagnostico, :fecha);');
			$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fecha' => $_POST["fecha"]]);
			if(isset($_POST['Complicaciones_complicacion'])){
				$stmt = Conexion::getpdo()->prepare('INSERT INTO Complicaciones(Filiaciones_NASI, Diagnosticos_fecha, Seguimientos_fecha, complicacion) VALUES (:nasi, :fechaDiagnostico, :fecha, :complicacion);');
				for($i=0; $i<count($_POST['Complicaciones_complicacion']); $i++){
					$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fecha' => $_POST["fecha"], 'complicacion' => $_POST['Complicaciones_complicacion'][$i]]);
				}
			}
		}
	}
	header("location: ../consulta_seguimiento.php");
?>