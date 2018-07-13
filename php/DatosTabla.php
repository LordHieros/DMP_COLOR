<?php
include_once 'Utils.php';

final class DatosTabla
{

    private $tabla;

    private $campos;

    /**
     *
     * @return Tabla
     */
    public function getTabla()
    {
        return $this->tabla;
    }

    /**
     *
     * @return array
     */
    public function getCampos()
    {
        return $this->campos;
    }

    /**
     * Constructor privado, requiere Tabla
     *
     * @param Tabla $tabla
     */
    public function __construct($tabla)
    {
        $this->tabla = $tabla;
        $this->campos = array();
    }

    /**
     * Crea un $datosTabla con solo claves, sin tabla asociada.
     * No permite hacer chequeo, con lo que no es muy seguro.
     *
     * @param array $claves
     * @param Tabla $tabla
     * @throws Exception
     * @return DatosTabla
     */
    public static function makeFromClaves($claves, $tabla)
    {
        $datosBase = new DatosTabla($tabla);
        if(!empty($claves)){
            $datosBase->setCampos($claves);
        }
        return $datosBase;
    }

    /**
     * Setea el array de claves, devolviendo excepcion si está mal formateado
     *
     * @param array $campos
     */
    public function setCampos($campos)
    {
        foreach (array_keys($campos) as $campo) {
            if ($this->checkColumna($campo)) {
                $this->campos[$campo] = $campos[$campo];
            }
        }
    }

    /**
     * Añade el campo a la columna correspondiente
     * Si ya existe, lo reemplaza
     * Se usan arrays de strings para multistrings
     *
     * @param string | string[] $campo
     * @param Columna $columna
     * @throws Exception
     */
    public function setCampo($campo, $columna)
    {
        if ($this->checkColumna($columna->getNombre())) {
            $this->campos[$columna->getNombre()] = $campo;
        } else{
            throw new Exception("La columna " . $columna->getNombre() . " introducida no es uno de los campos de la tabla " . $this->getTabla()->getNombreTabla());
        }
    }

    /**
     * Devuevle el campo correspondiente a la columna, o null de no haber
     *
     * @param Columna $columna
     * @throws Exception
     * @return string | string[] | boolean | int | float | null
     */
    public function getCampo($columna)
    {
        if ($this->checkColumna($columna->getNombre())) {
            if (array_key_exists($columna->getNombre(), $this->getCampos())){
                return $this->campos[$columna->getNombre()];
            }
            else{
                return null;
            }
        } else{
            throw new Exception("La columna " . $columna->getNombre() . " introducida no es uno de los campos de la tabla " . $this->getTabla()->getNombreTabla());
        }
    }

    /**
     * Comprueba si existe una columna en la tabla con el nombre especificado
     *
     * @param string $columna
     * @return boolean
     */
    private function checkColumna($columna)
    {
        $found = false;
        foreach ($this->getTabla()->getColumnas() as $columnaCampo) {
            if ($columnaCampo->getNombre() === $columna) {
                $found = true;
            }
        }
        return $found;
    }

    /**
     * Crea un Datos Tabla de la tabla especificada usando las claves de la sesion
     *
     * @param Tabla $tabla
     * @throws Exception
     * @return DatosTabla
     */
    public static function makeWithSessionKeys($tabla)
    {
        $res = new DatosTabla($tabla);
        $clavesSession = Utils::getSessionClaves();
        $campos = array();
        foreach ($tabla->getColumnas() as $clave) {
            if (array_key_exists($clave->getNombre(), $clavesSession)) {
                $campos[$clave->getNombre()] = $clavesSession[$clave->getNombre()];
            }
        }
        $res->setCampos($campos);
        return $res;
    }
}