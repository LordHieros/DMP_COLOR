<?php
	include('header.php');
	
	if(!$_SESSION[CampoSession::USUARIO]){
		$_SESSION[CampoSession::ERROR] = 'Solo los administradores pueden crear usuarios';
		header('location: ../panel.php');
	} 
	else{
		if($_POST["contraseña1"] != $_POST["contraseña2"]){
			$_SESSION[CampoSession::ERROR] = 'Las contraseñas introducidas no coinciden; no se ha podido crear usuario';
		}
		else{
			require 'Conexion.php';
			$stmt = Conexion::getpdo()->prepare('SELECT * FROM Usuarios where nombre=:nombre;');
			$stmt->execute(['nombre' => $_POST["nombre"]]);
			$resultado = $stmt->fetchAll();
			if(count($resultado) > 0){
				$_SESSION[CampoSession::ERROR] = 'Ya existe un usuario con este nombre; no se ha podido crear usuario';
			}
			else{
				$stmt = Conexion::getpdo()->prepare('INSERT INTO Usuarios(nombre, contrasenha, administrador) VALUES (:nombre, :contrasenha, 0);');
				$res=$stmt->execute(['nombre' => $_POST["nombre"], 'contrasenha' => $_POST["contraseña1"]]);
				if(!$res){
					$_SESSION[CampoSession::ERROR] = 'Ha habido un problema en la introducción de datos, error: ' . $stmt->errorInfo();
				}
			}
		}
		header("location: ../consulta_usuario.php");
	}
?>