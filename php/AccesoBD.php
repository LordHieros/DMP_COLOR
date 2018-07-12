<?php
include_once ("Tabla.php");
include_once ("DatosTabla.php");
include_once ("Conexion.php");
include_once ("Utils.php");

final class AccesoBD
{

    /**
     * Carga la tabla o tablas, devolviendo excepcion si hay problemas con el acceso a base de datos.
     * Devuelve null si no hay datos
     * Solo funciona bien si se expecifican correctamente las claves primarias
     * Solo admite tablas repetidas si la unica diferecnia son los mult_string
     *
     * @param DatosTabla $datos
     * @throws Exception
     * @return DatosTabla | null
     */
    public static function loadTabla($datos)
    {
        $statement = self::makeSelect('*', $datos, $datos->getTabla()->getColumnas());
        try {
            $stmt = self::executeStatement($statement);
            $resultado = $stmt->fetchAll();
            $no_vacio = count($resultado) > 0;
            $res = null;
            if ($no_vacio) {
                $res = new datosTabla($datos->getTabla());
                foreach ($datos->getTabla()->getColumnas() as $columna) {
                    if ($columna->getTipo() === TipoColumna::multString()) {
                        $datosMultString = array();
                        foreach ($resultado as $unResultado) {
                            $datosMultString[] = $unResultado[$columna->getColumna()];
                        }
                        $res->setCampo($datosMultString, $columna);
                    } else if ($columna->getTipo()->getTipoSql() === TipoColumna::TINYINT) {
                        if ($resultado[0][$columna->getColumna()] == 1) {
                            $res->setCampo(true, $columna);
                        } else {
                            $res->setCampo(false, $columna);
                        }
                    } else {
                        $res->setCampo($resultado[0][$columna->getColumna()], $columna);
                    }
                }
            } else {
                $res = null;
            }
            return $res;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Inserta o actualiza la tabla o tablas, devolviendo excepcion si hay problemas con el acceso a base de datos.
     * En caso de tabla "multivaluada" primero borra las presentes y luego inserta las nuevas
     *
     * @param DatosTabla $datos
     * @throws Exception
     */
    public static function saveTabla($datos)
    {
        try {
            if ($datos->getTabla()->getMultivalued() != null) {
                self::deleteTabla($datos);
                $statement = self::makeInsert($datos);
            } else {
                if (self::checkTabla($datos)) {
                    $statement = self::makeUpdate($datos);
                } else {
                    $statement = self::makeInsert($datos);
                }
            }
            self::executeStatement($statement);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Borra la tabla, lanzando excepcion si hay problemas con el acceso a base de datos.
     *
     * @param DatosTabla $datos
     * @throws Exception
     */
    public static function deleteTabla($datos)
    {
        $statement = self::makeDelete($datos);
        try {
            self::executeStatement($statement);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Devuelve un boolean, true si la tabla existe, false si no.
     * Exception si hay problemas con el acceso a base de datos.
     *
     * @param DatosTabla $datos
     * @throws Exception
     * @return boolean
     */
    public static function checkTabla($datos)
    {
        $statement = self::makeSelect('1', $datos, $datos->getTabla()->getClaves());
        try {
            $stmt = self::executeStatement($statement);
            $resultado = $stmt->fetchAll();
            return (count($resultado) > 0);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Ejecuta el statement, lanzando una excepción si hay problemas
     *
     * @param string $statement
     * @throws Exception
     * @return PDOStatement
     */
    private static function executeStatement($statement)
    {
        $stmt = Conexion::getpdo()->prepare($statement);
        $stmt->execute();
        $arr = $stmt->errorInfo();
        if ($arr[0] != 0) {
            throw new Exception(print_r($arr, true));
        }
        return $stmt;
    }

    /**
     * Crea un statement INSERT, con inserción múltiple en caso de ser necesario
     *
     * @param DatosTabla $datos
     * @return string
     */
    private static function makeInsert($datos)
    {
        // Inutil, usado para evitar un warning del phpstorm que no le gusta que se acabe string en abrir paréntesis.
        $tmp = ' (';
        $statement = 'INSERT INTO ' . $datos->getTabla()->getNombreTabla() . $tmp;
        $keys = null;
        $values = null;
        foreach ($datos->getTabla()->getColumnas() as $campo) {
            if ($campo->getTipo() !== TipoColumna::multString()) {
                $keys[] = $campo->getColumna();
                if (array_key_exists($campo->getNombre(), $datos->getCampos())) {
                    if ($campo->getTipo()->getTipoSql() === TipoColumna::TINYINT) {
                        if ($datos->getCampos()[$campo->getNombre()]) {
                            $values[] = '1';
                        } else {
                            $values[] = '0';
                        }
                    } else {
                        $values[] = "'" . $datos->getCampos()[$campo->getNombre()] . "'";
                    }
                } else if($campo->getTipo() !== TipoColumna::control()) {
                    $values[] = 'NULL';
                }
            }
        }
        $statement = $statement . implode(', ', $keys);
        $multi = $datos->getTabla()->getMultivalued();
        if ($multi != null) {
            $statement = $statement . ', ' . $multi->getColumna() . ') VALUES ';
            $item = '(' . implode(', ', $values);
            $multivalues[] = '';//Inserta vacio por defecto de ser pertinente, para ceptar checklist vacios si van precedidos de un si/no
            if (array_key_exists($multi->getNombre(), $datos->getCampos())) {
                if(!empty($datos->getCampos()[$multi->getNombre()])) {
                    $multivalues = $datos->getCampos()[$multi->getNombre()];
                }
            }
            $items = array();
            foreach ($multivalues as $multivalue) {
                $items[] = $item . ", '" . $multivalue . "')";
            }
            $statement = $statement . implode(', ', $items) . ";";
        } else {
            $statement = $statement . ") VALUES (" . implode(', ', $values) . ");";
        }
        return $statement;
    }

    /**
     * Crea un statement SELECT, seleccionando $itemsToSelect
     *
     * @param string $itemsToSelect
     * @param DatosTabla $datos
     * @param Columna[] $columnas
     * @return string
     */
    private static function makeSelect($itemsToSelect, $datos, $columnas)
    {
        $statement = 'SELECT ' . $itemsToSelect . ' FROM ' . $datos->getTabla()->getNombreTabla();
        return $statement . self::makeWhere($datos, $columnas);
    }

    /**
     * Crea un statement UPDATE
     *
     * @param DatosTabla $datos
     * @return string
     */
    private static function makeUpdate($datos)
    {
        $statement = 'UPDATE ' . $datos->getTabla()->getNombreTabla() . ' SET ';
        $sets = null;
        foreach ($datos->getTabla()->getColumnas() as $campo) {
            // Nunca debiera darse el caso, tal y como está la base de datos, de que hubiese que updatear un multistring. Se deja por deporte.
            if ($campo->getTipo() !== TipoColumna::multString()) {
                if (array_key_exists($campo->getNombre(), $datos->getCampos())) {
                    if ($campo->getTipo()->getTipoSql() === TipoColumna::TINYINT) {
                        if ($datos->getCampos()[$campo->getNombre()] == TipoItem::SI) {
                            $sets[] = $campo->getColumna() . ' = 1';
                        } else {
                            $sets[] = $campo->getColumna() . ' = 0';
                        }
                    } else {
                        $sets[] = $campo->getColumna() . " = '" . $datos->getCampos()[$campo->getNombre()] . "'";
                    }
                } else if($campo->getTipo() !== TipoColumna::control()) {
                    $sets[] = $campo->getColumna() . ' = NULL';
                }
            }
        }
        $statement = $statement . implode(', ', $sets);
        return $statement . self::makeWhere($datos, $datos->getTabla()->getClaves());
    }

    /**
     * Crea un statement DELETE FROM.
     *
     * @param DatosTabla $datos
     * @return string
     */
    private static function makeDelete($datos)
    {
        $statement = 'DELETE FROM ' . $datos->getTabla()->getNombreTabla();
        return $statement . self::makeWhere($datos, $datos->getTabla()->getClaves());
    }

    /**
     * Crea una clausula WHERE
     *
     * @param DatosTabla $datos
     * @param Columna[] $columnas
     * @return string
     */
    private static function makeWhere($datos, $columnas)
    {
        $statement = '';
        foreach ($columnas as $columna) {
            if (array_key_exists($columna->getNombre(), $datos->getCampos())) {
                $wheres[] = $columna->getColumna() . ' = "' . $datos->getCampos()[$columna->getNombre()] . '"';
            }
        }
        if (!empty($wheres)) {
            $statement = $statement . ' WHERE ' . implode(' AND ', $wheres);;
        }
        return $statement . ';';
    }

    /**
     * Devuelve todos los items de la tabla, solo usando las claves
     *
     * @param DatosTabla $datos
     * @throws Exception
     * @return array[]
     */
    private static function getAll($datos){
        return self::executeStatement(self::makeSelect('*', $datos, $datos->getTabla()->getClaves()))->fetchAll();
    }

    /**
     * Devuelve lista de usuarios
     *
     * @throws Exception
     * @return string[]
     */
    public static function getUsuarios(){
        $res = array();
        $datos = new DatosTabla(Tabla::Usuarios());
        foreach(self::getAll($datos) as $item){
            $res[$item[Columna::nombreUsuario()->getColumna()]] = $item[Columna::nombreUsuario()->getColumna()];
        }
        return $res;
    }

    /**
     * Devuelve lista de filiaciones de pacientes
     *
     * @throws Exception
     * @return string[]
     */
    public static function getFiliaciones(){
        $datos = DatosTabla::makeWithSessionKeys(Tabla::Filiaciones());
        $res = array();
        foreach(self::getAll($datos) as $item){
            $res[$item[Columna::nasi()->getColumna()]] = $item[Columna::nasi()->getColumna()];
        }
        return $res;
    }

    /**
     * Devuelve lista de diagnosticos del paciente
     *
     * @throws Exception
     * @return string[]
     */
    public static function getDiagnosticos(){
        $datos = DatosTabla::makeWithSessionKeys(Tabla::Diagnosticos());
        $causas = DatosTabla::makeWithSessionKeys(Tabla::CausasIntervencion());
        $res = array();
        foreach(self::getAll($datos) as $item){
            $entry = $item[Columna::fechaDiagnostico()->getColumna()];
            $causas->setCampo($entry, Columna::fechaDiagnostico());
            $entry = Utils::readableDate($entry);
            $resCausas = self::getAll($causas);
            if(empty($resCausas)) {
                $entry = $entry . ' - Sin causas de intervención especificadas';
            }
            else{
                $entries = array();
                foreach ($resCausas as $causa){
                    $entries[] = $causa[Columna::causasIntervencion()->getColumna()];
                }
                $entry = $entry . ' - Causas de intervención: ' . implode(', ', $entries);;
            }
            $res[$item[Columna::fechaDiagnostico()->getColumna()]] = $entry;
        }
        return $res;
    }

    /**
     * Devuelve lista de intervenciones del paciente
     *
     * @throws Exception
     * @return string[]
     */
    public static function getIntervenciones(){
        $datos = DatosTabla::makeWithSessionKeys(Tabla::Intervenciones());
        $tipos = DatosTabla::makeWithSessionKeys(Tabla::TiposIntervencion());
        $res = array();
        foreach(self::getAll($datos) as $item){
            $entry = $item[Columna::fechaIntervencion()->getColumna()];
            $tipos->setCampo($entry, Columna::fechaIntervencion());
            $entry = Utils::readableDate($entry);
            $resTipos = self::getAll($tipos);
            if(empty($resTipos)) {
                $entry = $entry . ' - Sin tipos de intervención especificados';
            }
            else{
                $entries = array();
                foreach ($resTipos as $tipo){
                    $entries[] = $tipo[Columna::tiposIntervencion()->getColumna()];
                }
                $entry = $entry . ' - Tipos de intervención: ' . implode(', ', $entries);
            }
            $res[$item[Columna::fechaIntervencion()->getColumna()]] = $entry;
        }
        return $res;
    }

    /**
     * Devuelve un nasi aleatorio que no exista ya
     *
     * @return string
     * @throws Exception
     */
    public static function randomNasi(){
        $datos = new DatosTabla(Tabla::Filiaciones());
        do{
            $nasi = rand();
            $datos->setCampo($nasi, Columna::nasi());
        }while(self::checkTabla($datos));
        return $nasi;
    }

    /**
     * make this private so noone can make one
     * TipoColumna constructor.
     *
     * @throws Exception
     */
    private function __construct()
    {
        // throw an exception if someone can get in here (I'm paranoid)
        throw new Exception("Can't get an instance of Errors");
    }
}