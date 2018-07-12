<?php

final class TipoColumna
{

    const TINYINT = 'TINYINT';

    const VARCHAR = 'VARCHAR';

    const INT = 'INT';

    const DECIMAL = 'DECIMAL';

    const DATE = 'DATE';

    const NO_DATE = '0001-01-01';

    private $tipoSql;

    /**
     * Devuelve el tipo de datos que se usa para guardar la columna en SQL
     *
     * @return string
     */
    function getTipoSql()
    {
        return $this->tipoSql;
    }

    private static $bool;

    /**
     * Usa boolean, se guarda en BD como 1/0
     * Singleton
     *
     * @return TipoColumna
     */
    static function bool()
    {
        if (! isset(self::$bool)) {
            self::$bool = new TipoColumna();
            self::$bool->tipoSql = self::TINYINT;
        }
        return self::$bool;
    }

    private static $control;

    /**
     * Usa boolean, implica variables de control que tienen valor por defecto y no pueden estar a NULL
     * Singleton
     *
     * @return TipoColumna
     */
    static function control()
    {
        if (! isset(self::$control)) {
            self::$control = new TipoColumna();
            self::$control->tipoSql = self::TINYINT;
        }
        return self::$control;
    }

    private static $multString;

    /**
     * array de strings, uno por cada PK.
     * Se incluye en campos, no en claves. Tiene que ser un array, nunca un String único.
     * Si hay más de un campo de estos en una tabla, o el campo no corresponde a una PK, cosas malas pasarán.
     * No siempre será un Varchar, pero casi siempre si
     * Singleton
     *
     * @return TipoColumna
     */
    static function multString()
    {
        if (! isset(self::$multString)) {
            self::$multString = new TipoColumna();
            self::$multString->tipoSql = self::VARCHAR;
        }
        return self::$multString;
    }

    private static $int;

    /**
     * Numero entero, sin tratamiento especial
     * Singleton
     *
     * @return TipoColumna
     */
    static function int()
    {
        if (! isset(self::$int)) {
            self::$int = new TipoColumna();
            self::$int->tipoSql = self::INT;
        }
        return self::$int;
    }

    private static $float;

    /**
     * Numero decimal, sin tratamiento especial
     * Singleton
     *
     * @return TipoColumna
     */
    static function float()
    {
        if (! isset(self::$float)) {
            self::$float = new TipoColumna();
            self::$float->tipoSql = self::DECIMAL;
        }
        return self::$float;
    }

    private static $string;

    /**
     * Cadena de caracteres, sin tratamiento especial
     * Singleton
     *
     * @return TipoColumna
     */
    static function string()
    {
        if (! isset(self::$string)) {
            self::$string = new TipoColumna();
            self::$string->tipoSql = self::VARCHAR;
        }
        return self::$string;
    }

    private static $date;

    /**
     * Fecha, sin tratamiento especial
     * Singleton
     *
     * @return TipoColumna
     */
    static function date()
    {
        if (! isset(self::$date)) {
            self::$date = new TipoColumna();
            self::$date->tipoSql = self::DATE;
        }
        return self::$date;
    }

    // Impide que la clase se instancie desde fuera
    private function __construct()
    {}
}