<?php
    include_once('php/header.php');
    include_once('php/Comprobaciones.php');
    include_once("php/AccesoBD.php");
    include_once("php/Utils.php");

    try{
        Comprobaciones::compruebaNasi(true);
    } catch (Exception $e){
        Utils::manageException($e);
    }
    if(isset($_SESSION[CampoSession::NASI])){
        Utils::headTo('consultaFiliacion.php', null);
    }
	
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Filiaciones de ' . $_SESSION[CampoSession::NOMBRE_USUARIO];
	$html['encabezado'] = '<h4> Haga clic en una de las filiaciones para consultar sus diagnósticos </h4>';
	//Creamos el cuerpo
    try{
        $datos['resultado'] = AccesoBD::getFiliaciones();
    } catch (Exception $e){
        Utils::manageException($e);
    }
	$html['body'] = Utils::getBasicBody();
	//Mostrar filiaciones
	$datos['titulo'] = 'Filiaciones:';
	$datos['clave'] = CampoSession::NASI;
	$datos['destino'] = '';
	$datos['vacio'] = 'No hay filiaicones a nombre de este usuario';
	$html['body'] = $html['body'] . make_eleccion($datos);
	unset($datos);
	//Crear las opciones
	$i=0;
	if($_SESSION[CampoSession::ADMINISTRADOR]){
		$datos[$i]['tipo'] = 'redirección';
		$datos[$i]['objetivo'] = 'seleccionUsuario.php';
		$datos[$i]['nombre'] = 'Volver';
		$i++;
	}
	$datos[$i]['tipo'] = 'redirección';
	$datos[$i]['objetivo'] = 'consultaNhc.php';
	$datos[$i]['nombre'] = 'Consultar NHC';
	$html['body'] = $html['body'] . make_navbar($datos);
	unset($datos);
	echo make_html($html);