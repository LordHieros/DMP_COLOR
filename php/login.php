<?php
    include ('Formulario.php');
    include ('CampoSession.php');
    include ('Utils.php');

	if (isset($_POST[Formulario::formLogin()->getSubmit()])){
		if (empty($_POST['nombreUsuario']) || empty($_POST['contrasenha'])){
			$_SESSION[CampoSession::ERROR] = "Debe introducir un nombre de usuario y una contraseña";
		}
		else{
            Utils::console_log('Seteado ');
			// Definir $nombre y $contraseña
			$nombre=$_POST['nombreUsuario'];
			$contraseña=$_POST['contrasenha'];
			// Establecer conexion y obtener información
			require 'Conexion.php';
			$stmt = Conexion::getpdo()->prepare('SELECT * FROM Usuarios where nombreUsuario=:nombre;');
			$stmt->execute(['nombre' => $nombre]);

			// Comprobar informacion
			$resultado = $stmt->fetchAll();
			if (count($resultado) > 0) { //Comprobar si existe usuario
                Utils::console_log('Existe usuario');
				if ($resultado[0]['contrasenha'] == $contraseña){ //Comprobar la contraseña
                    Utils::console_log('CoincideContraseña');
					$_SESSION[CampoSession::USUARIO]=$resultado[0]['nombreUsuario']; //Cargar nombre de la base de datos
					if($resultado[0]['administrador']==1){$_SESSION[CampoSession::USUARIO]=true;} //Comprobar si es administrador
					else{$_SESSION[CampoSession::USUARIO]=false;}
                    $_SESSION[CampoSession::ERROR] = "Login correcto, ha habido otros problemas";
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
    header("location: ../index.php"); // Redigir a panel
?>