<?php

//No se incluye en el repositorio la clase de conexión, pues incluye datos de conexión. Para usar la plataforma
//se deben rellenar los datos $host (ip:puerto), user (nombre de usuario de la base de datos) y password (cntraseña).
//Tras esto bastará con renombrar el fichero a Conexión.php y la plataforma estará lista.
final class Conexion
{

    private static $pdo;

    /**
     * Devuelve el PDO de conexión, ya preconfigurado, con singleton
     *
     * @return PDO
     */
    static function getpdo()
    {
        if (! isset(self::$pdo)) {
            $host = "";
            $user = "";
            $password = "";
            $db = "proxectos_dmp-color";
            $charset = 'utf8';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $opt = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            self::$pdo = new PDO($dsn, $user, $password, $opt);
        }
        return self::$pdo;
    }

    /**
     * make this private so noone can make one
     * Conexion constructor.
     *
     * @throws Exception
     */
    private function __construct()
    {
        // throw an exception if someone can get in here (I'm paranoid)
        throw new Exception("Can't get an instance of Errors");
    }
}