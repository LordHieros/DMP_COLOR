<?php
    include_once('php/header.php');
    include_once('php/Comprobaciones.php');
    include_once("php/AccesoBD.php");
    include_once("php/Utils.php");
    include_once("php/CampoSession.php");
    include_once("php/Formulario.php");

    try {
        Comprobaciones::compruebaIntervencion(false);
    } catch (Exception $e){
        Utils::manageException($e);
    }

    //EMPIEZA EL HTML
    //Ponemos el título de la página
    $html['titulo'] = 'Datos de la intervención del ' . $_SESSION[CampoSession::FECHA_INTERVENCION] . ' del diagnóstico del ' . $_SESSION[CampoSession::FECHA_DIAGNOSTICO] . ' del paciente de NASI ' . $_SESSION[CampoSession::NASI];
    //Creamos el cuerpo
    try {
        $view = Formulario::formIntervencion()->makeView();
    } catch (Exception $e){
        Utils::manageException($e);
    }
    if(empty($view)){
        $view = '<h4>No hay datos introducidos para esta intervención</h4>';
    }
    $html['body'] = Utils::getBasicBody() . $view;
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
    $opcion['objetivo'] = 'consultaDiagnostico.php';
    $opcion['nombre'] = 'Volver';
    $datos[] = $opcion;
    unset($opcion);
    $opcion['tipo'] = 'redirección';
    $opcion['objetivo'] = 'editaIntervencion.php';
    $opcion['nombre'] = 'Editar Intervencion';
    $datos[] = $opcion;
    unset($opcion);
    $html['body'] = $html['body'] . make_navbar($datos);
    unset($datos);
    echo make_html($html);
