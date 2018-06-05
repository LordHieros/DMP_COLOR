<?php
	include('header.php');
	include('use_database.php');
	comprueba_nasi();
	
	//Inicializar variables de sesion
	$nasi=$_SESSION[CampoSession::NASI];
	require 'Conexion.php';
	
	//Caso de querer cerrar expediente
	if(isset($_POST["cerrar"])){
		$stmt = Conexion::getpdo()->prepare('UPDATE Filiaciones SET cerrado = :cerrado WHERE NASI = :nasi;');
		if($_POST["cerrar"]=='true')
			$stmt->execute(['cerrado' => 1, 'nasi' => $nasi]);
		else if($_POST["cerrar"]=='false' && $_SESSION[CampoSession::USUARIO])
			$stmt->execute(['cerrado' => 0, 'nasi' => $nasi]);
		else{
			$_SESSION[CampoSession::ERROR] = 'Error de acceso de datos';
			header("location: ../panel.php");
			die;
		}
	}
	
	else{
		//Inicializar variables para introducir/editar
		if(isset($_POST["sexo"])){
			$sexo=$_POST["sexo"];
		}
		if(isset($_POST["edad"])){
			$edad=$_POST["edad"];
		}
		if(isset($_POST["talla"])){
			$talla=$_POST["talla"];
		}
		if(isset($_POST["peso"])){
			$peso=$_POST["peso"];
		}
		//Comprobamos si se está editando
		$editar = false;
		if(isset($_POST["editar"])){
			if($_POST["editar"] == 'true'){
				$editar = true;
			}
		}
		//Empezamos con la introduccion de datos
		$base = 'Filiaciones';
		$claves_principal = ['NASI'];
		$datos_principal = [$nasi];
		$claves = array();
		$datos = array();
		if(!$editar){
			$claves[] = 'cerrado';
			$datos[] = 0;
		}
		$claves[] = 'sexo';
		$datos[] = $sexo;
		$claves[] = 'edad';
		$datos[] = $edad;
		$claves[] = 'talla';
		$datos[] = $talla;
		$claves[] = 'peso';
		$datos[] = $peso;
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
	}
	header("location: ../consulta_filiacion.php");
?>