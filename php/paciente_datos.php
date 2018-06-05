<?php
	include('header.php');
	
	comprueba_nombre();
	
	$error = false;
	require 'Conexion.php';
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM Claves where NHC=:nhc;');
	$stmt->execute(['nhc' => $_POST["NHC"]]);
	$resultado = $stmt->fetchAll();
	if(count($resultado) > 0){
		$_SESSION[CampoSession::MENSAJE] = '<span class="negrita">Ya existe ficha para este paciente.</span> El NHC introducido (' . $resultado[0]['NHC'] . ') ya existe en la base de datos y tiene asociado el NASI ' . $resultado[0]['NASI'] . '.';
	}
	else{ //Genera un nasi aleatorio, comprueba si existe en la base de datos y en caso afirmativo genera uno nuevo
		$stmt = Conexion::getpdo()->prepare('SELECT * FROM Claves where NASI=:nasi;');
		do{
			$nasi=rand();
			$stmt->execute(['nasi' => $nasi]);
			$resultado = $stmt->fetchAll();
		}while(count($resultado) > 0);
		$stmt = Conexion::getpdo()->prepare('INSERT INTO Claves(NASI, NHC) VALUES (:NASI, :NHC);');
		$res=$stmt->execute(['NASI' => $nasi, 'NHC' => $_POST["NHC"]]);
		if(!$res){
			$error = true;
		}
		else{
			$stmt = Conexion::getpdo()->prepare('INSERT INTO Usuarios_has_Claves(Usuarios_nombre, Claves_NASI) VALUES (:nombre, :nasi);');
			$res=$stmt->execute(['nasi' => $nasi, 'nombre' => $_SESSION[CampoSession::NOMBRE]]);
			if(!$res){ 
				$error = true;
			}
			else{
				$_SESSION[CampoSession::MENSAJE] = '<span class="negrita">Ficha de paciente creada.</span> El NHC ' . $_POST['NHC'] . ' tiene asociado el NASI ' . $nasi . ' para el usuario ' . $_SESSION[CampoSession::NOMBRE] . '.';
			}
		}
	}
	if($error == true){
		$_SESSION[CampoSession::ERROR] = '<span class="error">Ha habido un problema en la introducción de datos, error: ' . $stmt->errorInfo() . '</span>';
		header("Location: ../consulta_paciente.php");
	}
	else{
		$_SESSION[CampoSession::NASI] = $nasi;
		header("Location: ../consulta_paciente.php");
		//WhyTF this doesn't work? ERR_UNSAFE_REDIRECT
		//header("Location: ../consulta_filiacion.php");
	}
?>