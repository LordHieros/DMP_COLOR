<?php

include_once("Formulario.php");
include_once("CampoServer.php");
include_once("CampoSession.php");
include_once("DatosTabla.php");
include_once("Modelo.php");
include_once("AccesoBD.php");

final class Comprobaciones
{
    /**
     * Comprueba el login, devuelve true en caso de que sea correcto, false en caso contrario
     *
     * @throws Exception
     * @return bool
     */
    public static function checkLogin()
    {
        if (isset($_POST[Formulario::formLogin()->getSubmit()])) {
            $nombre = $_POST[ItemFormulario::nombreUsuario()->getNombre()];
            $contrasenha = $_POST[ItemFormulario::contrasenha()->getNombre()];
            if (empty($nombre) || empty($contrasenha)) {
                $_SESSION[CampoSession::ERROR] = "Debe introducir un nombre de usuario y una contraseña";
            } else {
                $datos = Modelo::loadModelo(Modelo::modeloUsuarios(), [Columna::nombreUsuario()->getNombre() => $nombre])[Tabla::Usuarios()->getNombreTabla()];
                if ($datos != null) { //Comprobar si existe usuario
                    if ($datos->getCampos()[Columna::contrasenha()->getNombre()] == $contrasenha) { //Comprobar la contraseña
                        $_SESSION[CampoSession::USUARIO] = $datos->getCampos()[Columna::nombreUsuario()->getNombre()]; //Cargar nombre de la base de datos
                        if ($datos->getCampos()[Columna::administrador()->getNombre()]) {
                            $_SESSION[CampoSession::ADMINISTRADOR] = true;
                        } //Comprobar si es administrador
                        else {
                            $_SESSION[CampoSession::ADMINISTRADOR] = false;
                        }
                        return true;
                    } else {
                        $_SESSION[CampoSession::ERROR] = "Contraseña incorrecta";
                    }
                } else {
                    $_SESSION[CampoSession::ERROR] = "No existe un usario con ese nombre";
                }
            }
        }
        return false;
    }

    /**
     * Comprueba el nhc, redirigiendo de existir, creando de no existir y soltando excepción en caso de error
     *
     * @throws Exception
     */
    public static function checkNHC()
    {
        if (isset($_POST[Formulario::formNHC()->getSubmit()])) {
            $nhc = $_POST[ItemFormulario::nhc()->getNombre()];
            if (empty($nhc)) {
                $_SESSION[CampoSession::ERROR] = "Debe introducir un nhc";
            } else {
                $datos = Modelo::loadModelo(Modelo::modeloFiliaciones(), [Columna::nhc()->getNombre() => $nhc])[Tabla::Filiaciones()->getNombreTabla()];
                if ($datos != null) { //Comprobar si existe filiacion
                    if ($datos->getCampos()[Columna::nombreUsuario()->getNombre()] == $_SESSION[CampoSession::NOMBRE_USUARIO] || $_SESSION[CampoSession::ADMINISTRADOR]) { //Comprobar si tenemos acceso a la filiacion
                        $_SESSION[CampoSession::NASI] = $datos->getCampos()[Columna::nasi()->getNombre()];
                    } else {
                        $_SESSION[CampoSession::ERROR] = "El NHC introducido ya está asignado a otro usuario: " . $datos->getCampos()[Columna::nombreUsuario()->getNombre()];
                    }
                } else {
                    $_SESSION[CampoSession::NASI] = AccesoBD::randomNasi();
                    $datos = new DatosTabla(Tabla::Filiaciones());
                    $datos->setCampo($_SESSION[CampoSession::NASI], Columna::nasi());
                    $datos->setCampo($nhc, Columna::nhc());
                    $datos->setCampo($_SESSION[CampoSession::NOMBRE_USUARIO], Columna::nombreUsuario());
                    AccesoBD::saveTabla($datos);
                }
            }
        }
    }

    /**
     * Crea diagnóstico, soltando excepción en caso de error
     *
     * @throws Exception
     */
    public static function checkCreaDiagnostico()
    {
        if (isset($_POST[Formulario::formCreaDiagnostico()->getSubmit()])) {
            $fechaDiagnostico = $_POST[ItemFormulario::fechaDiagnostico()->getNombre()];
            if (empty($fechaDiagnostico)) {
                $_SESSION[CampoSession::ERROR] = "Debe introducir una fecha de diagnóstico";
            } else {
                $datos = Modelo::loadModelo(Modelo::modeloDiagnostico(), [Columna::nasi()->getNombre() => $_SESSION[CampoSession::NASI], Columna::fechaDiagnostico()->getNombre() => $fechaDiagnostico])[Tabla::Diagnosticos()->getNombreTabla()];
                if ($datos != null) { //Comprobar si existe diagnostico
                    $_SESSION[CampoSession::ERROR] = "Ya existe un diagnóstico programado para " . $fechaDiagnostico;
                } else {
                    $_SESSION[CampoSession::FECHA_DIAGNOSTICO] = $fechaDiagnostico;
                    $datos = new DatosTabla(Tabla::Diagnosticos());
                    $datos->setCampo($_SESSION[CampoSession::NASI], Columna::nasi());
                    $datos->setCampo($fechaDiagnostico, Columna::fechaDiagnostico());
                    $datos->setCampo(false, Columna::diagnosticoCerrado());
                    AccesoBD::saveTabla($datos);
                }
            }
        }
    }

    /**
     * Crea intervención, soltando excepción en caso de error
     *
     * @throws Exception
     */
    public static function checkCreaIntervencion()
    {
        if (isset($_POST[Formulario::formCreaIntervencion()->getSubmit()])) {
            $fechaIntervencion = $_POST[ItemFormulario::fechaIntervencion()->getNombre()];
            if (empty($fechaIntervencion)) {
                $_SESSION[CampoSession::ERROR] = "Debe introducir una fecha de intervención";
            } else {
                $datos = Modelo::loadModelo(Modelo::modeloIntervencion(), [Columna::nasi()->getNombre() => $_SESSION[CampoSession::NASI], Columna::fechaDiagnostico()->getNombre() => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], Columna::fechaIntervencion()->getNombre() => $fechaIntervencion])[Tabla::Intervenciones()->getNombreTabla()];
                if ($datos != null) { //Comprobar si existe diagnostico
                    $_SESSION[CampoSession::ERROR] = "Ya existe una intervneción programada para " . $fechaIntervencion;
                } else {
                    $_SESSION[CampoSession::FECHA_INTERVENCION] = $fechaIntervencion;
                    $datos = new DatosTabla(Tabla::Intervenciones());
                    $datos->setCampo($_SESSION[CampoSession::NASI], Columna::nasi());
                    $datos->setCampo($_SESSION[CampoSession::FECHA_DIAGNOSTICO], Columna::fechaDiagnostico());
                    $datos->setCampo($fechaIntervencion, Columna::fechaIntervencion());
                    $datos->setCampo(false, Columna::intervencionCerrado());
                    AccesoBD::saveTabla($datos);
                }
            }
        }
    }

    /**
     * Edita diagnóstico, soltando excepción en caso de error
     *
     * @throws Exception
     * @return boolean
     */
    public static function editaDiagnostico()
    {
        if (isset($_POST[Formulario::formDiagnostico()->getSubmit()])) {
            if(true) {
                require_once 'Utils.php';
                foreach (array_keys($_POST) as $dato)
                    if (!empty($_POST[$dato]))
                        Utils::console_log('POST[' . $dato . ']: ' . print_r($_POST[$dato], true));
                foreach (Formulario::formDiagnostico()->makeDatos() as $dato)
                    Utils::console_log('Datos[' . $dato->getTabla()->getNombreTabla() . ']: ' . print_r($dato->getCampos(), true));
            }
            Formulario::formDiagnostico()->saveToDatabase();
            return true;
        }
        return false;
    }

    /**
     * Edita intervención, soltando excepción en caso de error
     *
     * @throws Exception
     * @return boolean
     */
    public static function editaIntervencion()
    {
        if (isset($_POST[Formulario::formIntervencion()->getSubmit()])) {
            if(true) {
                require_once 'Utils.php';
                foreach (array_keys($_POST) as $dato)
                    if (!empty($_POST[$dato]))
                        Utils::console_log('POST[' . $dato . ']: ' . print_r($_POST[$dato], true));
                foreach (Formulario::formIntervencion()->makeDatos() as $dato)
                    Utils::console_log('Datos[' . $dato->getTabla()->getNombreTabla() . ']: ' . print_r($dato->getCampos(), true));
            }
            Formulario::formIntervencion()->saveToDatabase();
            return true;
        }
        return false;
    }

    /**
     * Se comprueba si se ha elegido usuario
     */
    static function compruebaUsuario(){
        if(!isset($_SESSION[CampoSession::USUARIO])){ //
            $error='No ha hecho login';
            Utils::headTo('index.php', $error);
        }
    }

    /**
     * Se comprueba si se ha elegido nombre
     *
     * @param boolean checkPost
     */
    static function compruebaNombre($checkPost){
        self::compruebaUsuario();
        if($checkPost) {
            if (!$_SESSION[CampoSession::ADMINISTRADOR]) { //Si no se es admin se asigna el nombre de usuario como nombre de cirujano
                $_SESSION[CampoSession::NOMBRE_USUARIO] = $_SESSION[CampoSession::USUARIO];
            }
            else if(isset($_POST[CampoSession::NOMBRE_USUARIO])){ //Si se llega como admin con un nombre en POST se añade ese nombre a la sesión
                $_SESSION[CampoSession::NOMBRE_USUARIO] = $_POST[CampoSession::NOMBRE_USUARIO];
                unset($_POST[CampoSession::NOMBRE_USUARIO]);
            }
            else{ //Si se llega como admin sin un nombre en POST a consulta usuario se borra el nombre de la sesión
                unset($_SESSION[CampoSession::NOMBRE_USUARIO]);
            }
        }
        else if(!isset($_SESSION[CampoSession::NOMBRE_USUARIO])){ //Si no se tiene elegido nombre y no se está en consulta usuario
            $error='Debe de elegir un usuario para continuar';
            Utils::headTo('seleccionUsuario.php', $error);
        }
    }

    /**
     * Se comprueba si se ha elegido NASI; en caso contrario se manda al panel con un mensaje de error
     *
     * @param boolean checkPost
     * @throws Exception
     */
    static function compruebaNasi($checkPost){
        self::compruebaNombre(false);
        if($checkPost) {
            if(isset($_POST[CampoSession::NASI])){
                $_SESSION[CampoSession::NASI] = $_POST[CampoSession::NASI];
                unset($_POST[CampoSession::NASI]);
            }
            else{
                unset($_SESSION[CampoSession::NASI]);
            }
        }
        else if(isset($_SESSION[CampoSession::NASI])) { //Esto existe para proteger la base de datos; en caso de intentar seleccionar un NASI al que no se pueda acceder se mandará de vuelta al panel con un mensaje de error. Si se puede acceder se añade el nasi a la sesión.
            $datos = new DatosTabla(Tabla::Filiaciones());
            $datos->setCampo($_SESSION[CampoSession::NASI], Columna::nasi());
            $datos->setCampo($_SESSION[CampoSession::NOMBRE_USUARIO], Columna::nombreUsuario());
            if (!AccesoBD::checkTabla($datos)) {
                $error = 'Se ha intentado elegir un NASI que no pertenece al usuario seleccionado o ha habido un error al crear usuario';
                Utils::headTo('panel.php', $error);
            }
        }
        else{ //Si no se tiene nasi elegido se manda a panel con un mensaje de error
            $error='Solo se puede acceder a la página elegida tras seleccionar un NASI';
            Utils::headTo('panel.php', $error);
        }
    }

    /**
     * Se comprueba si se ha elegido diagnóstico; en caso contrario se manda al panel con un mensaje de error
     *
     * @param boolean checkPost
     * @throws Exception
     */
    static function compruebaDiagnostico($checkPost){
        self::compruebaNasi(false);
        if($checkPost) {
            if(isset($_POST[CampoSession::FECHA_DIAGNOSTICO])){
                $_SESSION[CampoSession::FECHA_DIAGNOSTICO] = $_POST[CampoSession::FECHA_DIAGNOSTICO];
                unset($_POST[CampoSession::FECHA_DIAGNOSTICO]);
            }
            else{
                unset($_SESSION[CampoSession::FECHA_DIAGNOSTICO]);
            }
        }
        else if(isset($_SESSION[CampoSession::FECHA_DIAGNOSTICO])) { //Esto existe para proteger la base de datos; en caso de intentar seleccionar un NASI al que no se pueda acceder se mandará de vuelta al panel con un mensaje de error. Si se puede acceder se añade el nasi a la sesión.
            $datos = new DatosTabla(Tabla::Diagnosticos());
            $datos->setCampo($_SESSION[CampoSession::FECHA_DIAGNOSTICO], Columna::fechaDiagnostico());
            $datos->setCampo($_SESSION[CampoSession::NASI], Columna::nasi());
            if (!AccesoBD::checkTabla($datos)) {
                $error = 'El paciente elegido (' . $_SESSION[CampoSession::NASI] . ')no tiene un diagnóstico con esa fecha(' . $_SESSION[CampoSession::FECHA_DIAGNOSTICO] . '), consulte con el administrador';
                Utils::headTo('panel.php', $error);
            }
        }
        else{ //Si no se tiene fecha de daignóstico elegida
            $error='Solo se puede acceder a la página elegida tras seleccionar un diagnóstico';
            Utils::headTo('panel.php', $error);
        }
    }

    /**
     * Se comprueba si se ha elegido intervención; en caso contrario se manda al panel con un mensaje de error
     *
     * @param boolean checkPost
     * @throws Exception
     */
    static function compruebaIntervencion($checkPost){
        self::compruebaDiagnostico(false);
        if($checkPost) {
            if(isset($_POST[CampoSession::FECHA_INTERVENCION])){
                $_SESSION[CampoSession::FECHA_INTERVENCION] = $_POST[CampoSession::FECHA_INTERVENCION];
                unset($_POST[CampoSession::FECHA_INTERVENCION]);
            }
            else{
                unset($_SESSION[CampoSession::FECHA_INTERVENCION]);
            }
        }
        else if(isset($_SESSION[CampoSession::FECHA_INTERVENCION])) { //Esto existe para proteger la base de datos; en caso de intentar seleccionar un NASI al que no se pueda acceder se mandará de vuelta al panel con un mensaje de error. Si se puede acceder se añade el nasi a la sesión.
            $datos = new DatosTabla(Tabla::Intervenciones());
            $datos->setCampo($_SESSION[CampoSession::FECHA_INTERVENCION], Columna::fechaDiagnostico());
            $datos->setCampo($_SESSION[CampoSession::FECHA_DIAGNOSTICO], Columna::fechaDiagnostico());
            $datos->setCampo($_SESSION[CampoSession::NASI], Columna::nasi());
            if (!AccesoBD::checkTabla($datos)) {
                $error = 'El paciente elegido (' . $_SESSION[CampoSession::NASI] . ')no tiene una intervención con esa fecha(' . $_SESSION[CampoSession::FECHA_INTERVENCION] . '), consulte con el administrador';
                Utils::headTo('panel.php', $error);
            }
        }
        else{ //Si no se tiene fecha de diagnóstico elegida
            $error='Solo se puede acceder a la página elegida tras seleccionar una intervención';
            Utils::headTo('panel.php', $error);
        }
    }
}