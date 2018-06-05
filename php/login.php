<?php
	if (isset($_POST['Iniciar'])){
		if (empty($_POST['nombre']) || empty($_POST['contraseña'])){
			$_SESSION[CampoSession::ERROR] = "Debe introducir un nombre de usuario y una contraseña";
		}
		else{
			// Definir $nombre y $contraseña
			$nombre=$_POST['nombre'];
			$contraseña=$_POST['contraseña'];
			// Establecer conexion y obtener información
			require 'Conexion.php';
			$stmt = Conexion::getpdo()->prepare('SELECT * FROM Usuarios where nombre=:nombre;');
			$stmt->execute(['nombre' => $nombre]);

			// Comprobar informacion
			$resultado = $stmt->fetchAll();
			if (count($resultado) > 0) { //Comprobar si existe usuario
				if ($resultado[0]['contrasenha'] == $contraseña){ //Comprobar la contraseña
					$_SESSION[CampoSession::USUARIO]=$resultado[0][nombre]; //Cargar nombre de la base de datos
					if($resultado[0]['administrador']==1){$_SESSION[CampoSession::USUARIO]=true;} //Comprobar si es administrador
					else{$_SESSION[CampoSession::USUARIO]=false;}
					header("location: panel.php"); // Redigir a panel
				}
				else{ 
					$_SESSION[CampoSession::ERROR] = "Contraseña incorrecta";
				}
			} 
			else{
				$_SESSION[CampoSession::ERROR] = "No existe un usario con ese nombre";
			}
		}
	}
?>