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
        $statement = self::makeSelect('*', $datos, $datos->getTabla()->getColumnas(), '');
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
        $statement = self::makeSelect('1', $datos, $datos->getTabla()->getClaves(), '');
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
        Utils::console_log('Ejecutando: ' . $statement, false);
        return $stmt;
    }

    /**
     * Crea un statement INSERT, con inserción múltiple en caso de ser necesario
     *
     * @param DatosTabla $datos
     * @throws Exception
     * @return string
     */
    private static function makeInsert($datos)
    {
        // Inutil, usado para evitar un warning del phpstorm que no le gusta que se acabe string en abrir paréntesis.
        $tmp = ' (';
        $statement = 'INSERT INTO ' . $datos->getTabla()->getNombreTabla() . $tmp;
        $keys = null;
        $values = null;
        foreach ($datos->getTabla()->getColumnas() as $columna) {
            if ($columna->getTipo() !== TipoColumna::multString()) {
                $keys[] = $columna->getColumna();
                $campo = $datos->getCampo($columna);
                if (!empty($campo)) {
                    if ($columna->getTipo()->getTipoSql() === TipoColumna::TINYINT) {
                        if ($campo) {
                            $values[] = '1';
                        } else {
                            $values[] = '0';
                        }
                    } else {
                        $values[] = "'" . $campo . "'";
                    }
                } else if($columna->getTipo() !== TipoColumna::control()) {
                    $values[] = 'NULL';
                }
                else{
                    $values[] = '0';
                }
            }
        }
        $statement = $statement . implode(', ', $keys);
        $multi = $datos->getTabla()->getMultivalued();
        if ($multi != null) {
            $statement = $statement . ', ' . $multi->getColumna() . ') VALUES ';
            $item = '(' . implode(', ', $values);
            $multivalues[] = '';//Inserta vacio por defecto de ser pertinente, para ceptar checklist vacios si van precedidos de un si/no
            $campo = $datos->getCampo($multi);
            if(!empty($campo)) {
                $multivalues = $campo;
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
     * @param string $opciones
     * @throws Exception
     * @return string
     */
    private static function makeSelect($itemsToSelect, $datos, $columnas, $opciones)
    {
        $statement = 'SELECT ' . $itemsToSelect . ' FROM ' . $datos->getTabla()->getNombreTabla();
        return $statement . self::makeWhere($datos, $columnas) . $opciones . ';';
    }

    /**
     * Crea un statement UPDATE
     *
     * @param DatosTabla $datos
     * @throws Exception
     * @return string
     */
    private static function makeUpdate($datos)
    {
        $statement = 'UPDATE ' . $datos->getTabla()->getNombreTabla() . ' SET ';
        $sets = null;
        foreach ($datos->getTabla()->getColumnas() as $columna) {
            // Nunca debiera darse el caso, tal y como está la base de datos, de que hubiese que updatear un multistring. Se deja por deporte.
            if ($columna->getTipo() !== TipoColumna::multString()) {
                $campo = $datos->getCampo($columna);
                if (!empty($campo)) {
                    if ($columna->getTipo()->getTipoSql() === TipoColumna::TINYINT) {
                        if ($campo == TipoItem::SI) {
                            $sets[] = $columna->getColumna() . ' = 1';
                        } else {
                            $sets[] = $columna->getColumna() . ' = 0';
                        }
                    } else {
                        $sets[] = $columna->getColumna() . " = '" . $campo . "'";
                    }
                } else if($columna->getTipo() !== TipoColumna::control()) {
                    $sets[] = $columna->getColumna() . ' = NULL';
                }
            }
        }
        $statement = $statement . implode(', ', $sets);
        return $statement . self::makeWhere($datos, $datos->getTabla()->getClaves()) . ';';
    }

    /**
     * Crea un statement DELETE FROM.
     *
     * @throws Exception
     * @param DatosTabla $datos
     * @return string
     */
    private static function makeDelete($datos)
    {
        $statement = 'DELETE FROM ' . $datos->getTabla()->getNombreTabla();
        return $statement . self::makeWhere($datos, $datos->getTabla()->getClaves()) . ';';
    }

    /**
     * Crea una clausula WHERE
     *
     * @throws Exception
     * @param DatosTabla $datos
     * @param Columna[] $columnas
     * @return string
     */
    private static function makeWhere($datos, $columnas)
    {
        $statement = '';
        foreach ($columnas as $columna) {
            $campo = $datos->getCampo($columna);
            if (!empty($campo)) {
                $wheres[] = $columna->getColumna() . ' = "' . $campo . '"';
            }
        }
        if (!empty($wheres)) {
            $statement = $statement . ' WHERE ' . implode(' AND ', $wheres);
        }
        return $statement;
    }

    /**
     * Devuelve todos los items de la tabla, solo usando las claves, con las opcioens que se especifiquen
     *
     * @param DatosTabla $datos
     * @param string $opciones
     * @throws Exception
     * @return array[]
     */
    private static function getAll($datos, $opciones){
        return self::executeStatement(self::makeSelect('*', $datos, $datos->getTabla()->getColumnas(), $opciones))->fetchAll();
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
        foreach(self::getAll($datos, '') as $item){
            $res[$item[Columna::nombreUsuario()->getColumna()]] = $item[Columna::nombreUsuario()->getColumna()];
        }
        return $res;
    }

    /**
     * Devuelve lista de filiaciones
     *
     * @throws Exception
     * @return string[]
     */
    public static function getFiliaciones(){
        $datos = DatosTabla::makeWithSessionKeys(Tabla::Filiaciones());
        $res = array();
        foreach(self::getAll($datos, '') as $item){
            $entry = $item[Columna::nasi()->getColumna()];
            $datosDiagnostico = DatosTabla::makeWithSessionKeys(Tabla::Diagnosticos());
            $datosDiagnostico->setCampo($entry, Columna::nasi());
            $resDiagnosticos = self::getAll($datosDiagnostico, ' ORDER BY ' . Columna::fechaDiagnostico()->getColumna() . ' DESC LIMIT 1 ');
            $readableEntry = $entry;
            if(!empty($resDiagnosticos)){
                $diagnostico = $resDiagnosticos[0];
                if(!empty($diagnostico[Columna::sexo()->getColumna()]) && !empty($diagnostico[Columna::edad()->getColumna()])){
                    $readableEntry = $readableEntry . ' - ' . $diagnostico[Columna::sexo()->getColumna()] . ', ' . $diagnostico[Columna::edad()->getColumna()] . ' años';
                    if(!empty($diagnostico[Columna::talla()->getColumna()])){
                        $readableEntry = $readableEntry . ', ' . $diagnostico[Columna::talla()->getColumna()] . ' cm de altura';
                    }
                    if(!empty($diagnostico[Columna::peso()->getColumna()])){
                        $readableEntry = $readableEntry . ', ' . $diagnostico[Columna::peso()->getColumna()] . ' kg de peso';
                    }
                }
                else{
                    $readableEntry = $readableEntry . ' - Su diagnóstico más reciente (' . $diagnostico[Columna::fechaDiagnostico()->getColumna()] . ') no tiene especificados sexo y edad';
                }
            }
            else{
                $readableEntry = $readableEntry . ' - Sin diagnósticos';
            }
            $res[$entry] = $readableEntry;
        }
        return $res;
    }

    /**
     * Devuelve lista de diagnosticos de la filiación
     *
     * @throws Exception
     * @return string[]
     */
    public static function getDiagnosticos(){
        $datos = DatosTabla::makeWithSessionKeys(Tabla::Diagnosticos());
        $causas = DatosTabla::makeWithSessionKeys(Tabla::CausasIntervencion());
        $res = array();
        foreach(self::getAll($datos, '') as $item){
            $entry = $item[Columna::fechaDiagnostico()->getColumna()];
            $causas->setCampo($entry, Columna::fechaDiagnostico());
            $readableEntry = Utils::readableDate($entry);
            $resCausas = self::getAll($causas, '');
            if(empty($resCausas)) {
                $readableEntry = $readableEntry . ' - Sin causas de intervención especificadas';
            }
            else{
                $entries = array();
                foreach ($resCausas as $causa){
                    $entries[] = $causa[Columna::causasIntervencion()->getColumna()];
                }
                $readableEntry = $readableEntry . ' - Causas de intervención: ' . implode(', ', $entries);;
            }
            $res[$entry] = $readableEntry;
        }
        return $res;
    }

    /**
     * Devuelve lista de intervenciones de la filiación
     *
     * @throws Exception
     * @return string[]
     */
    public static function getIntervenciones(){
        $datos = DatosTabla::makeWithSessionKeys(Tabla::Intervenciones());
        $tipos = DatosTabla::makeWithSessionKeys(Tabla::TiposIntervencion());
        $res = array();
        foreach(self::getAll($datos, '') as $item){
            $entry = $item[Columna::fechaIntervencion()->getColumna()];
            $tipos->setCampo($entry, Columna::fechaIntervencion());
            $readableEntry = Utils::readableDate($entry);
            $resTipos = self::getAll($tipos, '');
            if(empty($resTipos)) {
                $readableEntry = $readableEntry . ' - Sin tipos de intervención especificados';
            }
            else{
                $entries = array();
                foreach ($resTipos as $tipo){
                    $entries[] = $tipo[Columna::tiposIntervencion()->getColumna()];
                }
                $readableEntry = $readableEntry . ' - Tipos de intervención: ' . implode(', ', $entries);
            }
            $res[$entry] = $readableEntry;
        }
        return $res;
    }

    /**
     * Cambia el id de hospital. Se pone aparte porque es la unica clave primaria que puede cambiar.
     *
     * @param string $original
     * @param string $new
     * @throws Exception
     */
    public static function changeHospitalId($original, $new){
        $statement = "UPDATE " . Tabla::Hospitales()->getNombreTabla();
        $statement = $statement . " SET " . Columna::idHospital()->getColumna() . "='" . $new . "'";
        $statement = $statement . " WHERE " . Columna::idHospital()->getColumna() . "='" . $original . "';";
        self::executeStatement($statement);
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