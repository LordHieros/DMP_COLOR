<?php

final class AccesoBD
{

    /**
     * Carga la tabla o tablas, devolviendo excepcion si hay problemas con el acceso a base de datos.
     * Devuelve null si no hay datos
     *
     * @param Tabla $tabla
     * @param DatosTabla $datos
     * @throws Exception
     * @return DatosTabla | null
     */
    static function loadTabla($tabla, $datos)
    {
        $statement = self::makeSelect($tabla, '*', $datos);
        try {
            $stmt = self::executeStatement($statement);
            $resultado = $stmt->fetchAll();
            $no_vacio = count($resultado) > 0;
            $res = null;
            if ($no_vacio) {
                $res = new datosTabla($tabla);
                $res->setClaves($tabla->getClaves());
                foreach ($tabla->getCampos() as $campo) {
                    if ($campo->getTipo() === TipoColumna::multString()) {
                        foreach ($resultado as $unResultado) {
                            $res->addCampo($unResultado[$campo->getColumna()], $campo);
                        }
                    } else if ($campo->getTipo() !== TipoColumna::bool()) {
                        if ($resultado[0][$campo->getColumna()] == 1) {
                            $res->addCampo(true, $campo);
                        } else {
                            $res->addCampo(false, $campo);
                        }
                    } else {
                        $res->addCampo($resultado[0][$campo->getColumna()], $campo);
                    }
                }
            }
            else{
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
     * @param Tabla $tabla
     * @param DatosTabla $datos
     * @throws Exception
     */
    static function saveTabla($tabla, $datos)
    {
        try {
            if ($tabla->getMultivalued() != null) {
                self::deleteTabla($tabla, $datos);
                $statement = self::makeInsert($tabla, $datos);
            } else {
                if (self::checkTabla($tabla, $datos)) {
                    $statement = self::makeUpdate($tabla, $datos);
                } else {
                    $statement = self::makeInsert($tabla, $datos);
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
     * @param Tabla $tabla
     * @param DatosTabla $datos
     * @throws Exception
     */
    static function deleteTabla($tabla, $datos)
    {
        $statement = self::makeDelete($tabla, $datos);
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
     * @param Tabla $tabla
     * @param DatosTabla $datos
     * @throws Exception
     * @return boolean
     */
    static function checkTabla($tabla, $datos)
    {
        $statement = self::makeSelect($tabla, '1', $datos);
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
     * @param Tabla $tabla
     * @param DatosTabla $datos
     * @return string
     */
    private static function makeInsert($tabla, $datos)
    {
        //Inutil, usado para evitar un warning del phpstorm que no le gusta que se acabe string en abrir paréntesis.
        $tmp = ' (';
        $statement = 'INSERT INTO ' . $tabla->getNombreTabla() . $tmp;
        $keys = null;
        $values = null;
        foreach ($tabla->getClaves() as $clave) {
            $keys[] = $clave->getColumna();
            $values[] = $datos->getClaves()[$clave->getNombre()];
        }
        foreach ($tabla->getCampos() as $campo) {
            if ($campo->getTipo() !== TipoColumna::multString()) {
                $keys[] = $campo->getColumna();
                if (array_key_exists($campo->getNombre(), $datos->getCampos())) {
                    if ($campo->getTipo() !== TipoColumna::bool()) {
                        if ($datos->getCampos()[$campo->getNombre()]) {
                            $values[] = '1';
                        } else {
                            $values[] = '0';
                        }
                    } else {
                        $values[] = $datos->getCampos()[$campo->getNombre()];
                    }
                } else {
                    $values[] = 'NULL';
                }
            }
        }
        $statement = $statement . $keys[0];
        for ($i = 1; $i < sizeof($keys); $i ++) {
            $statement = $statement . ', ' . $keys[$i];
        }
        $multi = $tabla->getMultivalued();
        if ($multi != null) {
            $multivalues = $datos->getCampos()[$multi->getNombre()];
            $statement = $statement . ', ' . $multi->getColumna() . ') VALUES ';
            $item = '(' . $values[0];
            for ($i = 1; $i < sizeof($values); $i ++) {
                $item = $item . ', ' . $values[$i];
            }
            $statement = $statement . $item . ', ' . $multivalues[0] . ')';
            for ($i = 1; $i < sizeof($multivalues); $i ++) {
                $statement = $statement . ', ' . $item . ', ' . $multivalues[$i] . ')';
            }
            $statement = $statement . ';';
        } else {
            $statement = $statement . ') VALUES (' . $values[0];
            for ($i = 1; $i < sizeof($values); $i ++) {
                $statement = $statement . ', ' . $values[$i];
            }
            $statement = $statement . ');';
        }
        return $statement;
    }

    /**
     * Crea un statement SELECT, seleccionando $itemsToSelect
     *
     * @param Tabla $tabla
     * @param string $itemsToSelect
     * @param DatosTabla $datos
     * @return string
     */
    private static function makeSelect($tabla, $itemsToSelect, $datos)
    {
        $statement = 'SELECT ' . $itemsToSelect . 'FROM ' . $tabla->getNombreTabla();
        return $statement . self::makeWhere($tabla, $datos);
    }

    /**
     * Crea un statement UPDATE
     *
     * @param Tabla $tabla
     * @param DatosTabla $datos
     * @return string
     */
    private static function makeUpdate($tabla, $datos)
    {
        $statement = 'UPDATE ' . $tabla->getNombreTabla() . ' SET ';
        $sets = null;
        foreach ($tabla->getCampos() as $campo) {
            // Nunca debiera darse el caso, tal y como está la base de datos, de que hubiese que updatear un multistring. Se deja por deporte.
            if ($campo->getTipo() !== TipoColumna::multString()) {
                if (array_key_exists($campo->getNombre(), $datos->getCampos())) {
                    if ($campo->getTipo() !== TipoColumna::bool()) {
                        if ($datos->getCampos()[$campo->getNombre()]) {
                            $sets[] = $campo->getColumna() . ' = 1';
                        } else {
                            $sets[] = $campo->getColumna() . ' = 0';
                        }
                    } else {
                        $sets[] = $campo->getColumna() . ' = ' . $datos->getCampos()[$campo->getNombre()];
                    }
                } else {
                    $sets[] = $campo->getColumna() . ' = NULL';
                }
            }
        }
        $statement = $statement . $sets[0];
        for ($i = 1; $i < sizeof($sets); $i ++) {
            $statement = $statement . ', ' . $sets[$i];
        }
        return $statement . self::makeWhere($tabla, $datos);
    }

    /**
     * Crea un statement DELETE FROM.
     *
     * @param Tabla $tabla
     * @param DatosTabla $datos
     * @return string
     */
    private static function makeDelete($tabla, $datos)
    {
        $statement = 'DELETE FROM ' . $tabla->getNombreTabla();
        return $statement . self::makeWhere($tabla, $datos);
    }

    /**
     * Crea una clausula WHERE
     *
     * @param Tabla $tabla
     * @param DatosTabla $datos
     * @return string
     */
    private static function makeWhere($tabla, $datos)
    {
        $statement = ' WHERE ';
        $wheres = null;
        foreach ($tabla->getClaves() as $clave) {
            $wheres[] = $clave->getColumna() . ' = ' . $datos->getClaves()[$clave->getNombre()];
        }
        $statement = $statement . $wheres[0];
        for ($i = 1; $i < sizeof($wheres); $i ++) {
            $statement = $statement . ' AND ' . $wheres[$i];
        }
        return $statement . ';';
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