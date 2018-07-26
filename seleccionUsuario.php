<?php
    include_once('php/header.php');
    include_once('php/Comprobaciones.php');
    include_once("php/AccesoBD.php");
    include_once("php/Utils.php");

    Comprobaciones::compruebaNombre(true);
	if(isset($_SESSION[CampoSession::NOMBRE_USUARIO])){ Utils::headTo('seleccionFiliacion.php', null); }
	
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Usuarios de la plataforma';
	$html['encabezado'] = '<h4> Haga clic en el nombre de uno de los usuarios para consultar sus filiaciones </h4>';
	//Creamos el cuerpo
    try {
        $datos['resultado'] = AccesoBD::getUsuarios();
    } catch (Exception $e){
        Utils::manageException($e);
    }
	$html['body'] = Utils::getBasicBody();
	//Mostrar usuarios
	$datos['titulo'] = 'Usuarios:';
	$datos['clave'] = CampoSession::NOMBRE_USUARIO;
	$datos['destino'] = '';
	$datos['vacio'] = 'No hay usuarios (avisar al servicio técnico)';
	$html['body'] = $html['body'] . make_eleccion($datos);
	unset($datos);
	//Crear las opciones
	$datos[0]['tipo'] = 'redirección';
	$datos[0]['objetivo'] = 'creacionUsuario.php';
	$datos[0]['nombre'] = 'Crear usuario';
	$html['body'] = $html['body'] . make_navbar($datos);	
	unset($datos);
	echo make_html($html);