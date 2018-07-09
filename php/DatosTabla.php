<?php

final class DatosTabla
{

    private $tabla;

    private $claves;

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
    public function getClaves()
    {
        return $this->claves;
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
        $this->claves = array();
    }

    /**
     * Crea un $datosTabla con solo claves, sin tabla asociada, a partir de los campos SESSION
     *
     * @return DatosTabla
     */
    public static function makeFromSession()
    {
        return self::makeFromClaves(self::getSessionClaves());
    }

    /**
     * Crea un $datosTabla con solo claves, sin tabla asociada.
     * No permite hacer chequeo, con lo que no es muy seguro.
     *
     * @param $claves
     * @return DatosTabla
     */
    public static function makeFromClaves($claves)
    {
        $datosBase = new DatosTabla(null);
        $datosBase->claves = $claves;
        //TODO MIRAR!
        ;
        return $datosBase;
    }

    /**
     * Setea el array de claves, devolviendo excepcion si está mal formateado
     *
     * @param array $claves
     * @throws Exception
     */
    public function setClaves($claves)
    {
        if ($this->checkClaves($claves)) {
            $this->claves = $claves;
        } else {
            throw new Exception("Array de claves " . $claves . " no corresponde al formato requerido para la tabla " . $this->getTabla()->getNombreTabla());
        }
    }

    /**
     * Comprueba si el array de claves introducido es correcto
     *
     * @param array $claves
     * @return boolean
     */
    private function checkClaves($claves)
    {
        $checked = true;
        foreach (array_keys($claves) as $clave) {
            $found = false;
            foreach ($this->getTabla()->getClaves() as $columnaClave) {
                if ($columnaClave->getNombre() == $clave) {
                    $found = true;
                }
            }
            if (! $found) {
                $checked = false;
            }
        }
        return $checked;
    }

    /**
     * Añade el campo a la columna correspondiente
     * Si ya existe, lo reemplaza
     *
     * @param string $campo
     * @param Columna $columna
     * @throws Exception
     */
    public function setCampo($campo, $columna)
    {
        if ($this->checkColumnaCampo($columna)) {
            $this->campos[$columna->getNombre()] = $campo;
        } else {
            throw new Exception("La columna " . $columna->getNombre() . " introducida no es uno de los campos de la tabla " . $this->getTabla()->getNombreTabla());
        }
    }

    /**
     * Comprueba si la columna es uno de los campos de la tabla
     *
     * @param Columna $columna
     * @return boolean
     */
    private function checkColumnaCampo($columna)
    {
        $found = false;
        foreach ($this->getTabla()->getCampos() as $columnaCampo) {
            if ($columnaCampo === $columna) {
                $found = true;
            }
        }
        return $found;
    }

    /**
     * Crea un array de claves a partir de los campos SESSION
     *
     * @return array
     */
    private static function getSessionClaves()
    {
        $claves = array();
        if (array_key_exists(CampoSession::NOMBRE, $_SESSION)) {
            $claves[Columna::nombreUsuario()->getNombre()] = $_SESSION[CampoSession::NOMBRE];
        } else if (array_key_exists(CampoSession::USUARIO, $_SESSION)) {
            $claves[Columna::nombreUsuario()->getNombre()] = $_SESSION[CampoSession::USUARIO];
        }
        foreach (CampoSession::getPKs() as $PK) {
            if (array_key_exists($PK, $_SESSION)) {
                $claves[$PK] = $_SESSION[$PK];
            }
        }
        return $claves;
    }

    /**
     * Crea un Datos Tabla de la tabla especificada usando las claves de la sesion
     *
     * @param Tabla $tabla
     * @return DatosTabla
     */
    public static function makeWithSessionKeys($tabla)
    {
        $res = new DatosTabla($tabla);
        $clavesSession = self::getSessionClaves();
        $claves = array();
        foreach ($tabla->getClaves() as $clave) {
            if (array_key_exists($clave->getNombre(), $clavesSession)) {
                $claves[$clave->getNombre()] = $clavesSession[$clave->getNombre()];
            }
        }
        $res->claves = $claves;
        return $res;
    }
}