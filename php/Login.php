<?php
include ('Formulario.php');
final class Login
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
}
?>