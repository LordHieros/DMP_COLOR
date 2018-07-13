<?php
    include_once('php/header.php');
    include_once('php/Comprobaciones.php');
    include_once("php/AccesoBD.php");
    include_once("php/Utils.php");
    include_once("php/CampoSession.php");
    include_once("php/Formulario.php");

    try {
        Comprobaciones::compruebaIntervencion(true);
    } catch (Exception $e){
        Utils::manageException($e);
    }
    if(isset($_SESSION[CampoSession::FECHA_INTERVENCION])){ Utils::headTo('consultaIntervencion.php', null); }

    //EMPIEZA EL HTML
    //Ponemos el título de la página
    $html['titulo'] = 'Datos del diagnóstico del ' .  $_SESSION[CampoSession::FECHA_DIAGNOSTICO] . ' del paciente de NASI ' . $_SESSION[CampoSession::NASI];
    //Creamos el cuerpo
    try {
        $view = Formulario::formDiagnostico()->makeView();
    } catch (Exception $e){
        Utils::manageException($e);
    }
    if(empty($view)){
        $view = '<h4>No hay datos introducidos para este diagnóstico</h4>';
    }
    try {
        $datos['resultado'] = AccesoBD::getIntervenciones();
    } catch (Exception $e){
        Utils::manageException($e);
    }
    $html['body'] = Utils::getBasicBody() . $view;
    //Mostrar intervenciones
    $datos['titulo'] = 'Intervenciones:';
    $datos['clave'] = CampoSession::FECHA_INTERVENCION;
    $datos['destino'] = '';
    $datos['vacio'] = 'Este paciente no tiene intervenciones';
    $html['body'] = $html['body'] . make_eleccion($datos);
    unset($datos);
    //TODO cerrar
    /*
    if($editable){ //Si es editable se puede editar o cerrar el expediente
        $datos[$i]['tipo'] = 'post';
        $datos[$i]['objetivo'] = 'php/diagnostico_datos.php';
        $datos[$i]['valor'] = 'true';
        $datos[$i]['mensaje'] = 'Cerrar diagnóstico';
        $datos[$i]['nombre'] = 'cerrar';
        $i++;
    }
    else if($_SESSION[CampoSession::ADMINISTRADOR]){ //Si no es editable pero se es administrador se puede abrir el expediente
        $datos[$i]['tipo'] = 'post';
        $datos[$i]['objetivo'] = 'php/diagnostico_datos.php';
        $datos[$i]['valor'] = 'false';
        $datos[$i]['mensaje'] = 'Habilitar edición de diagnóstico';
        $datos[$i]['nombre'] = 'cerrar';
        $i++;
    }*/


    //Crear las opciones
    $opcion['tipo'] = 'redirección';
    $opcion['objetivo'] = 'consultaFiliacion.php';
    $opcion['nombre'] = 'Volver';
    $datos[] = $opcion;
    unset($opcion);
    $opcion['tipo'] = 'redirección';
    $opcion['objetivo'] = 'editaDiagnostico.php';
    $opcion['nombre'] = 'Editar Diagnóstico';
    $datos[] = $opcion;
    unset($opcion);
    $opcion['tipo'] = 'redirección';
    $opcion['objetivo'] = 'creacionIntervencion.php';
    $opcion['nombre'] = 'Crear Intervención';
    $datos[] = $opcion;
    unset($opcion);
    $html['body'] = $html['body'] . make_navbar($datos);
    unset($datos);
    echo make_html($html);
