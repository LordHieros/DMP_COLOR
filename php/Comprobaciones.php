<?php

include_once("Formulario.php");
include_once("CampoServer.php");

final class Comprobaciones
{

    public static function checkLogin()
    {
        if (isset($_POST[Formulario::formLogin()->getSubmit()])) {
            $nombre = $_POST[ItemFormulario::nombreUsuario()->getNombre()];
            $contrasenha = $_POST[ItemFormulario::contrasenha()->getNombre()];
            if (empty($nombre) || empty($contrasenha)) {
                $_SESSION[CampoSession::ERROR] = "Debe introducir un nombre de usuario y una contraseña";
            } else {
                // Establecer conexion y obtener información
                require 'Conexion.php';
                $stmt = Conexion::getpdo()->prepare('SELECT * FROM Usuarios where nombreUsuario=:nombre;');
                $stmt->execute(['nombre' => $nombre]);

                // Comprobar informacion
                $resultado = $stmt->fetchAll();
                if (count($resultado) > 0) { //Comprobar si existe usuario
                    if ($resultado[0]['contrasenha'] == $contrasenha) { //Comprobar la contraseña
                        $_SESSION[CampoSession::USUARIO] = $resultado[0]['nombreUsuario']; //Cargar nombre de la base de datos
                        if ($resultado[0]['administrador'] == 1) {
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

    //FUNCIONES 
    //Se comprueba si se ha elegido usuario
    static function compruebaUsuario(){
        if(!isset($_SESSION[CampoSession::USUARIO])){ //
            header('location: ' . $_SERVER[CampoServer::DOCUMENT_ROOT] . '/index.php');
        }
    }

    //Se comprueba si se ha elegido nombre
    static function compruebaNombre(){
        self::compruebaUsuario();
        if(!$_SESSION[CampoSession::ADMINISTRADOR]){ //Si no se es admin se asigna el nombre de usuario como nombre de cirujano
            $_SESSION[CampoSession::NOMBRE] = $_SESSION[CampoSession::USUARIO];
        }
        else if (isset($_POST[CampoSession::NOMBRE])){ //Si se llega como admin con un nombre en POST se añade ese nombre a la sesión
            $_SESSION[CampoSession::NOMBRE] = $_POST[CampoSession::NOMBRE];
            unset($_POST[CampoSession::NOMBRE]);
        }
        else if(basename($_SERVER[CampoServer::PHP_SELF])=='consulta_usuario.php'){ //Si se llega como admin sin un nombre en POST a consulta usuario se borra el nombre de la sesión
            unset($_SESSION[CampoSession::NOMBRE]);
        }
        if(!isset($_SESSION[CampoSession::NOMBRE]) && basename($_SERVER[CampoServer::PHP_SELF])!='consulta_usuario.php'){ //Si no se tiene elegido nombre y no se está en consulta usuario
            header('location: ' . $_SERVER[CampoServer::DOCUMENT_ROOT] . '/consulta_usuario.php');  //Se manda a consulta_usuario
        }
    }

    //Se comprueba si se ha elegido NASI; en caso contrario se manda al panel con un mensaje de error
    static function compruebaNasi(){
        self::compruebaNombre();
        if(isset($_POST[CampoSession::NASI])) { //Esto existe para proteger la base de datos; en caso de intentar seleccionar un NASI al que no se pueda acceder se mandará de vuelta al panel con un mensaje de error. Si se puede acceder se añade el nasi a la sesión.
            $stmt = Conexion::getpdo()->prepare('SELECT * FROM Usuarios_has_Claves where Usuarios_nombre=:nombre AND Claves_NASI=:nasi;');
            $stmt->execute(['nombre' => $_SESSION[CampoSession::NOMBRE], 'nasi' => $_POST[CampoSession::NASI]]);
            $resultado = $stmt->fetchAll();
            if (count($resultado) > 0)
                $_SESSION[CampoSession::NASI] = $_POST[CampoSession::NASI];
            else{
                $_SESSION[CampoSession::ERROR]='Se ha intentado elegir un NASI que no pertenece al usuario seleccionado';
                header('location: ' . $_SERVER[CampoServer::DOCUMENT_ROOT] . '/panel.php');
            }
            unset($_POST["nasi"]);
        }
        if(!isset($_SESSION[CampoSession::NASI])){ //Si no se tiene nasi elegido se manda a panel con un mensaje de error
            $_SESSION[CampoSession::ERROR]='Solo se puede acceder a la página elegida tras seleccionar un NASI';
            header('location: ' . $_SERVER[CampoServer::DOCUMENT_ROOT] . '/panel.php');
        }
    }

    //Se comprueba si se ha elegido diagnóstico; en caso contrario se manda al panel con un mensaje de error
    static function compruebaDiagnostico(){
        self::compruebaNasi();
        if(isset($_POST[CampoSession::FECHA_DIAGNOSTICO])) { //Esto existe para proteger la base de datos; en caso de intentar seleccionar un NASI al que no se pueda acceder se mandará de vuelta al panel con un mensaje de error
            $claves = ['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_POST[CampoSession::FECHA_DIAGNOSTICO]];
            try {
                if (AccesoBD::checkTabla(Tabla::Diagnosticos(), $claves))
                    $_SESSION[CampoSession::FECHA_DIAGNOSTICO] = $_POST[CampoSession::FECHA_DIAGNOSTICO];
                else {
                    $_SESSION[CampoSession::ERROR] = 'El paciente elegido no tiene un diagnóstico con esa fecha';
                    header('location: ' . $_SERVER[CampoServer::DOCUMENT_ROOT] . '/panel.php');
                }
            } catch (Exception $e){
                $_SESSION[CampoSession::ERROR] = $e->getMessage();
            }
            unset($_POST[CampoSession::FECHA_DIAGNOSTICO]);
        }
        if(!isset($_SESSION[CampoSession::FECHA_DIAGNOSTICO])){
            $_SESSION[CampoSession::ERROR]='Solo se puede acceder a la página elegida tras seleccionar un diagnóstico';
            header('location: ' . $_SERVER[CampoServer::DOCUMENT_ROOT] . '/panel.php');
        }
    }

    //Se comprueba si se ha elegido intervención; en caso contrario se manda al panel con un mensaje de error
    static function compruebaIntervencion(){
        self::compruebaDiagnostico();
        if(isset($_POST[CampoSession::FECHA_INTERVENCION])) { //Esto existe para proteger la base de datos; en caso de intentar seleccionar un NASI al que no se pueda acceder se mandará de vuelta al panel con un mensaje de error
            $claves = ['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fechaIntervencion' => $_POST[CampoSession::FECHA_INTERVENCION]];
            try {
                if (AccesoBD::checkTabla(Tabla::Intervenciones(), $claves))
                    $_SESSION[CampoSession::FECHA_INTERVENCION] = $_POST[CampoSession::FECHA_INTERVENCION];
                else{
                    $_SESSION[CampoSession::ERROR]='El paciente elegido no tiene una intervención con esa fecha para ese diagóstico';
                    header('location: ' . $_SERVER[CampoServer::DOCUMENT_ROOT] . '/panel.php');
                }
            } catch (Exception $e){
                $_SESSION[CampoSession::ERROR] = $e->getMessage();
            }
            unset($_POST[CampoSession::FECHA_INTERVENCION]);
        }
        if(!isset($_SESSION[CampoSession::FECHA_INTERVENCION])){
            $_SESSION[CampoSession::ERROR]='Solo se puede acceder a la página elegida tras seleccionar una intervención';
            header('location: ' . $_SERVER[CampoServer::DOCUMENT_ROOT] . '/panel.php');
        }
    }
}