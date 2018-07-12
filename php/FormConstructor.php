<?php

include_once 'Modelo.php';
include_once 'Utils.php';

final class FormConstructor
{

    //Oculto y revelador poco limpios, eliminables


    /**
     * Crea una vista de los datos del formulario
     *
     * @param Formulario $formulario
     * @throws Exception
     * @return string
     */
    public static function view($formulario)
    {
        $datos = Modelo::loadModelo($formulario->getModelo(), Utils::getSessionClaves());

        require_once 'Utils.php';
        foreach ($datos as $dato) {
            if($dato!= null) {
                Utils::console_log('Datos[' . $dato->getTabla()->getNombreTabla() . ']: ' . print_r($dato->getCampos(), true));
            }
        }
        $res = '';
        foreach ($formulario->getItems() as $item) {
            $res = $res . self::viewItem($item, $datos);
        }
        if(!empty($res)) {
            $res = self::makeTableBody('', $res);
            $res = self::makeTable('class="table table-hover"', $res);
        }
        return $res;
    }

    /**
     * Visualiza el contenido de un ItemFormulario
     *
     * @param ItemFormulario $item
     * @param DatosTabla[] $datos
     * @return string
     */
    public static function viewItem($item, $datos)
    {
        $contenido = '';
        if($item->getTipo()->getTipoInput() != null){
            $thisdatos = self::checkValue($item, $datos);
            if($item->getTipo() === TipoItem::date()){
                $thisdatos = Utils::readableDate($thisdatos);
            }
            if ($thisdatos != null && $thisdatos != TipoItem::NO) {
                $thisvalue = self::makeTableItem('', $item->getEtiqueta()) . self::makeTableItem('', $thisdatos);
                $contenido = $contenido . self::makeTableRow('', $thisvalue);
            }
        }
        foreach ($item->getNest() as $nestItem) {
            $contenido = $contenido . self::viewItem($nestItem, $datos);
        }
        return $contenido;
    }

    /**
     * Crea el formulario
     *
     * @param Formulario $formulario
     * @throws Exception
     * @return string
     */
    public static function make($formulario)
    {
        $contenido = self::makeSidePills($formulario) . self::makeFormContent($formulario);
        $contenido = self::makeDiv('class="row"', $contenido);
        $contenido = self::makeForm('action="' . $formulario->getAction() . '" method="' . $formulario->getMethod() . '"', $contenido);
        return $contenido;
    }


    /**
     * Crea el botón de submit del formulario
     *
     * @param Formulario $formulario
     * @return string
     */
    private static function makeSubmit($formulario)
    {
        $contenido = $formulario->getSubmit();
        $contenido = self::makeButton('type="submit" name="' . $formulario->getSubmit() . '" value=""', $contenido);
        $contenido = self::makeFieldset('class="form-group"', $contenido);
        return $contenido;
    }

    /**
     * Crea el contenido e inputs del formulario
     *
     * @param Formulario $formulario
     * @throws Exception
     * @return string
     */
    private static function makeFormContent($formulario)
    {
        $datos = Modelo::loadModelo($formulario->getModelo(), Utils::getSessionClaves());
        $res = '';
        $first = true;
        foreach ($formulario->getItems() as $item) {
            $showactive = '';
            if ($first) {
                $first = false;
                $showactive = ' show active';
            } else {
                $res = $res . "\n";
            }
            $contenido = self::makeItemContent($item, $datos, false);
            $opcionesDiv = 'class="tab-pane fade' . $showactive . '" id="v-pills-' . $item->getNombre() . '" role="tabpanel" aria-labelledby="v-pills-' . $item->getNombre() . '-tab"';
            $res = $res . self::makeDiv($opcionesDiv, $contenido);
        }
        $res = $res . self::makeSubmit($formulario);
        $res = self::makeDiv('class="tab-content" id="v-pills-tabContent"', $res);
        $res = self::makeDiv('class = "col-9"', $res);
        return $res;
    }

    /**
     * Crea el contenido de un ItemFormulario
     *
     * @param ItemFormulario $item
     * @param DatosTabla[] $datos
     * @param boolean $oculto
     * @return string
     */
    private static function makeItemContent($item, $datos, $oculto)
    {
        $contenido = '';
        $revelador = false;
        if ($item->getTipo()->getTipoInput() == null) {
            $contenido = $contenido . self::makeLegend($item);
        } else {
            if ($item->getTipo()->getTipoInput() === TipoItem::CHECKBOX || $item->getTipo()->getTipoInput() === TipoItem::RADIO) {
                if ($item->getTipo() === TipoItem::siNo() || $item->getTipo() === TipoItem::metastasis()) {
                    $revelador = true;
                }
                $contenido = $contenido . self::makeMultiInput($item, $datos, $oculto, $revelador);
                if($revelador){
                    $oculto = true;
                }
            } else {
                $contenido = $contenido . self::makeSimpleInput($item, $datos, $oculto);
            }
        }
        foreach ($item->getNest() as $nestItem) {
            $contenido = $contenido . self::makeItemContent($nestItem, $datos, $oculto);
        }
        $contenido = self::makeFieldset('class="form-group"', $contenido);
        return $contenido;
    }

    /**
     * Crea la barra de navegacion lateral
     *
     * @param Formulario $formulario
     * @return string
     */
    private static function makeSidePills($formulario)
    {
        $res = '';
        $first = true;
        foreach ($formulario->getItems() as $item) {
            $selected = 'false';
            $active = '';
            if ($first) {
                $first = false;
                $selected = 'true';
                $active = ' active';
            } else {
                $res = $res . "\n";
            }
            $opciones = 'class="nav-link' . $active . '" id="v-pills-' . $item->getNombre() . '-tab" data-toggle="pill" href="#v-pills-' . $item->getNombre() . '" role="tab" aria-controls="v-pills-' . $item->getNombre() . '" aria-selected="' . $selected . '"';
            $res = $res . self::makeHyperlink($opciones, $item->getEtiqueta());
        }
        $res = self::makeDiv('class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical"', $res);
        $res = self::makeDiv('class = "col-3"', $res);
        return $res;
    }

    /**
     * Crea un <div> con las opciones especificadas que contiene el string especificado
     *
     * @param string $opciones
     * @param string $contenido
     * @return string
     */
    private static function makeDiv($opciones, $contenido)
    {
        return self::makeWrapper('div', $opciones, $contenido);
    }

    /**
     * Crea un <form> con las opciones especificadas que contiene el string especificado
     *
     * @param string $opciones
     * @param string $contenido
     * @return string
     */
    private static function makeForm($opciones, $contenido)
    {
        return self::makeWrapper('form', $opciones, $contenido);
    }

    /**
     * Crea un <table> con las opciones especificadas que contiene el string especificado
     *
     * @param string $opciones
     * @param string $contenido
     * @return string
     */
    private static function makeTable($opciones, $contenido)
    {
        return self::makeWrapper('table', $opciones, $contenido);
    }

    /**
     * Crea un <tbody> con las opciones especificadas que contiene el string especificado
     *
     * @param string $opciones
     * @param string $contenido
     * @return string
     */
    private static function makeTableBody($opciones, $contenido)
    {
        return self::makeWrapper('tbody', $opciones, $contenido);
    }

    /**
     * Crea un <tr> con las opciones especificadas que contiene el string especificado
     *
     * @param string $opciones
     * @param string $contenido
     * @return string
     */
    private static function makeTableRow($opciones, $contenido)
    {
        return self::makeWrapper('tr', $opciones, $contenido);
    }

    /**
     * Crea un <td> con las opciones especificadas que contiene el string especificado
     *
     * @param string $opciones
     * @param string $contenido
     * @return string
     */
    private static function makeTableItem($opciones, $contenido)
    {
        return self::makeWrapper('td', $opciones, $contenido);
    }

    /**
     * Crea un <a> con las opciones especificadas que contiene el string especificado
     *
     * @param string $opciones
     * @param string $contenido
     * @return string
     */
    private static function makeHyperlink($opciones, $contenido)
    {
        return self::makeWrapper('a', $opciones, $contenido);
    }

    /**
     * Crea un <label> con las opciones especificadas que contiene el string especificado
     *
     * @param string $opciones
     * @param string $contenido
     * @return string
     */
    private static function makeLabel($opciones, $contenido)
    {
        return self::makeWrapper('label', $opciones, $contenido);
    }

    /**
     * Crea un <button> con las opciones especificadas que contiene el string especificado
     *
     * @param string $opciones
     * @param string $contenido
     * @return string
     */
    private static function makeButton($opciones, $contenido)
    {
        return self::makeWrapper('button', $opciones, $contenido);
    }

    /**
     * Crea un <fieldset> con las opciones especificadas que contiene el string especificado
     *
     * @param string $opciones
     * @param string $contenido
     * @return string
     */
    private static function makeFieldset($opciones, $contenido)
    {
        return self::makeWrapper('fieldset', $opciones, $contenido);
    }

    /**
     * Crea una etiqueta con las opciones especificadas que contiene el string especificado
     *
     * @param string $wrap
     * @param string $opciones
     * @param string $contenido
     * @return string
     */
    private static function makeWrapper($wrap, $opciones, $contenido)
    {
        $res = '<' . $wrap . ' ';
        $res = $res . $opciones . '>';
        $res = $res . "\n";
        $res = $res . $contenido;
        $res = $res . "\n";
        $res = $res . '</' . $wrap . '>';
        return $res;
    }

    /**
     * Crea el input de un item con entrada simple
     *
     * @param ItemFormulario $item
     * @param DatosTabla[] $datos
     * @param boolean $oculto
     * @return string
     */
    private static function makeSimpleInput($item, $datos, $oculto)
    {
        $class = 'form-control';
        if($oculto){
            $class = $class . ' oculto';
        }
        $contenido = $item->getEtiqueta();
        $contenido = $contenido . '<input class="' . $class . '" type="' . $item->getTipo()->getTipoInput() . '" ';
        $contenido = $contenido . 'name="' . $item->getNombrePost() . '" ';
        if ($item->getRequerido()) {
            $contenido = $contenido . 'required ';
        }
        $value = self::checkSimpleValue($item, $datos);
        if($value == TipoColumna::NO_DATE){
            $value = '';
        }
        $contenido = $contenido . 'value="' . $value . '" ';
        $contenido = $contenido . $item->getOpciones() . '>';
        $contenido = self::makeLabel('', $contenido);
        return $contenido;
    }

    /**
     * Crea el input de un item con entrada múltiple
     *
     * @param ItemFormulario $item
     * @param DatosTabla[] $datos
     * @param boolean $oculto
     * @param boolean $revelador
     * @return string
     */
    private static function makeMultiInput($item, $datos, $oculto, $revelador)
    {
        $class = '';
        if($oculto){
            $class = $class . 'oculto';
        }
        $res = self::makeLegend($item);
        $first = true;
        $values = self::checkMultiValues($item, $datos);
        foreach ($item->getValores() as $valor) {
            $thisclass = $class;
            if($revelador){
                if ($valor == TipoItem::SI || $valor == TipoItem::CON_CIRUGIA) {
                    $thisclass = 'revelador';
                    $mostrar = true;
                }
                else {
                    $mostrar = false;
                }
            }
            if ($first) {
                $first = false;
            } else {
                $res = $res . "\n";
            }
            $contenido = '<input class="' . $thisclass . '" type="' . $item->getTipo()->getTipoInput() . '" ';
            $contenido = $contenido . 'name="' . $item->getNombrePost() . '" ';
            if ($item->getRequerido()) {
                $contenido = $contenido . 'required ';
            }
            if(isset($mostrar)){
                if($mostrar){
                    $contenido = $contenido . 'onchange="mostrar(this, true)" ';
                }
                else{
                    $contenido = $contenido . 'onchange="mostrar(this, false)" ';
                }
            }
            $contenido = $contenido . 'value="' . $valor . '" ';
            if (in_array($valor, $values)) {
                $contenido = $contenido . 'checked ';
            }
            $contenido = $contenido . $item->getOpciones() . '>';
            $contenido = $contenido . $valor;
            $contenido = self::makeLabel('', $contenido);
            $res = $res . self::makeFieldset('class="' . $item->getTipo()->getTipoInput() . ' form-group"', $contenido);
        }
        return $res;
    }

    /**
     * Devuelve el valor para una entrada simple, de haber
     *
     * @param ItemFormulario $item
     * @param DatosTabla[] $datos
     * @return string
     */
    private static function checkValue($item, $datos)
    {
        $contenido = '';
        if ($item->getTipo()->getTipoInput() === TipoItem::CHECKBOX || $item->getTipo()->getTipoInput() === TipoItem::RADIO){
            $contenido = implode(', ', self::checkMultiValues($item, $datos));
        }
        else if($item->getTipo()->getTipoInput() != null){
            $contenido = self::checkSimpleValue($item, $datos);
        }
        return $contenido;
    }

    /**
     * Devuelve el valor para una entrada simple, de haber
     *
     * @param ItemFormulario $item
     * @param DatosTabla[] $datos
     * @return string
     */
    private static function checkSimpleValue($item, $datos)
    {
        $res = '';
        if ($datos != NULL) {
            $thisDatos = self::getItemData($item, $datos);
            if ($thisDatos != NULL) {
                if (array_key_exists($item->getNombre(), $thisDatos->getCampos())) {
                    if ($thisDatos->getCampos()[$item->getNombre()] != NULL) {
                        $res = $thisDatos->getCampos()[$item->getNombre()];
                    }
                }
            }
        }
        return $res;
    }

    /**
     * "Chequea" una entrada múltiple
     *
     * @param ItemFormulario $item
     * @param DatosTabla[] $datos
     * @return string[]
     */
    private static function checkMultiValues($item, $datos)
    {
        $res = array();
        if ($datos != NULL) {
            $thisDatos = self::getItemData($item, $datos);
            if (!empty($thisDatos)) {
                if ($item->getTipo()->getTipoInput() === TipoItem::CHECKBOX) {
                    if (array_key_exists($item->getNombre(), $thisDatos->getCampos())) {
                        if ($thisDatos->getCampos()[$item->getNombre()] != NULL) {
                            $res = $thisDatos->getCampos()[$item->getNombre()];
                        }
                    }
                } else if ($item->getTipo() === TipoItem::radio()) {
                    if (array_key_exists($item->getNombre(), $thisDatos->getCampos())) {
                        if ($thisDatos->getCampos()[$item->getNombre()] != NULL) {
                            $res[] = $thisDatos->getCampos()[$item->getNombre()];
                        }
                    }
                } else if ($item->getTipo() === TipoItem::boolean()) {
                    if (array_key_exists($item->getNombre(), $thisDatos->getCampos())) {
                        if ($thisDatos->getCampos()[$item->getNombre()] != NULL) {
                            if ($thisDatos->getCampos()[$item->getNombre()]) {
                                $res[] = TipoItem::SI;
                            } else {
                                $res[] = TipoItem::NO;
                            }
                        }
                    }
                } else if ($item->getTipo() === TipoItem::siNo()) {
                    $found = false;
                    foreach ($item->getNest() as $subitem){
                        if ($subitem->getTipo() === TipoItem::checkbox() || self::checkValue($subitem, $datos)!=null) {
                            $found = true;
                        }
                    }
                    if ($found) {
                        $res[] = TipoItem::SI;
                    }
                    else{
                        $res[] = TipoItem::NO;
                    }
                } else if ($item->getTipo() === TipoItem::metastasis()) {
                    if (!empty($datos[$item->getTablaCirugia()->getNombreTabla()])) {
                        $res[] = TipoItem::CON_CIRUGIA;
                    }
                    else{
                        $res[] = TipoItem::SIN_CIRUGIA;
                    }
                }
            } else {
                if ($item->getTipo() === TipoItem::siNo() || $item->getTipo() === TipoItem::metastasis()) {
                    $res[] = TipoItem::NO;
                }
            }
        }
        return $res;
    }

    /**
     * Devuelve los datos de la tabla a la que pertenece el item o null de no haber
     *
     * @param ItemFormulario $item
     * @param DatosTabla[] $datos
     * @return DatosTabla | null
     */
    private static function getItemData($item, $datos)
    {
        $thisDatos = null;
        if ($item->getTabla() != NULL) {
            if (array_key_exists($item->getTabla()->getNombreTabla(), $datos)) {
                if(!empty($datos[$item->getTabla()->getNombreTabla()])) {
                    $thisDatos = $datos[$item->getTabla()->getNombreTabla()];
                }
            }
        }
        return $thisDatos;
    }

    /**
     * Crea la legend de un item del formulatio
     *
     * @param ItemFormulario $item
     * @return string
     */
    private static function makeLegend($item)
    {
        return '<legend>' . $item->getEtiqueta() . '</legend>' . "\n";
    }
}

