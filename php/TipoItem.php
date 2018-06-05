<?php

final class TipoItem
{
    const SI = "Si";
    const NO = "No";
    const CON_CIRUGIA = "Con cirugía";
    const SIN_CIRUGIA = "Sin cirugía";

    private $tipoInput;

    /**
     * Devuelve el tipo de input que se usará en el formulario para este item
     *
     * @return string
     */
    function getTipoInput(){
        return $this->tipoInput;
    }

    private static $checkbox;
    /**
     * Tipo Checkbox, implica pasar valores como array. En POST tiene que añadirse [] al nombre
     * Singleton
     *
     * @return TipoItem
     */
    static function checkbox(){
        if (! isset(self::$checkbox)) {
            self::$checkbox = new TipoItem();
            self::$checkbox->tipoInput = 'checkbox';
        }
        return self::$checkbox;
    }

    private static $radio;
    /**
     * Tipo Radio
     * Singleton
     *
     * @return TipoItem
     */
    static function radio(){
        if (! isset(self::$radio)) {
            self::$radio = new TipoItem();
            self::$radio->tipoInput = 'radio';
        }
        return self::$radio;
    }

    private static $number;
    /**
     * Campo de entrada numérica, pueden especificarse máx y min
     * Singleton
     *
     * @return TipoItem
     */
    static function number(){
        if (! isset(self::$number)) {
            self::$number = new TipoItem();
            self::$number->tipoInput = 'number';
        }
        return self::$number;
    }

    private static $date;
    /**
     * Campo de entrada de fecha (oculto)
     * Singleton
     *
     * @return TipoItem
     */
    static function date(){
        if (! isset(self::$date)) {
            self::$date = new TipoItem();
            self::$date->tipoInput = 'date';
        }
        return self::$date;
    }

    private static $text;
    /**
     * Campo de entrada de texto
     * Singleton
     *
     * @return TipoItem
     */
    static function text(){
        if (! isset(self::$text)) {
            self::$text = new TipoItem();
            self::$text->tipoInput = 'text';
        }
        return self::$text;
    }

    private static $password;
    /**
     * Campo de entrada de contraseña (oculto)
     * Singleton
     *
     * @return TipoItem
     */
    static function password(){
        if (! isset(self::$password)) {
            self::$password = new TipoItem();
            self::$password->tipoInput = 'password';
        }
        return self::$password;
    }

    private static $boolean;
    /**
     * Para opciones booleanas, se guarda en la base de datos
     * Singleton
     *
     * @return TipoItem
     */
    static function boolean(){
        if (! isset(self::$boolean)) {
            self::$boolean = new TipoItem();
            self::$boolean->tipoInput = 'radio';
        }
        return self::$boolean;
    }

    private static $siNo;
    /**
     * Para opciones de si/no, no se guarda directamente en la base de datos
     * Singleton
     *
     * @return TipoItem
     */
    static function siNo(){
        if (! isset(self::$siNo)) {
            self::$siNo = new TipoItem();
            self::$siNo->tipoInput = 'radio';
        }
        return self::$siNo;
    }

    private static $metastasis;
    /**
     * Para metástasis pulmonares y hepáticas, que incluyen opcion de no, si con cirugia y si sin cirugia
     * Singleton
     *
     * @return TipoItem
     */
    static function metastasis(){
        if (! isset(self::$metastasis)) {
            self::$metastasis = new TipoItem();
            self::$metastasis->tipoInput = 'radio';
        }
        return self::$metastasis;
    }

    private static $agrupacion;
    /**
     * Para agrupaciones, sin input propio
     * Singleton
     *
     * @return TipoItem
     */
    static function agrupacion(){
        if (! isset(self::$agrupacion)) {
            self::$agrupacion = new TipoItem();
            self::$agrupacion->tipoInput = null;
        }
        return self::$agrupacion;
    }

    // Impide que la clase se instancie desde fuera
    private function __construct()
    {
    }
}