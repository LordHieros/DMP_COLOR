<?php

final class MakeForm
{

    /**
     * Crea el formulario
     *
     * @param Formulario $formulario
     * @throws Exception
     * @return string
     */
    public static function make($formulario)
    {
        $res = '<form action="' . $formulario->getAction() . '" method="' . $formulario->getMethod() . '">';
        $res = $res . "\n";
        $contenido = self::makeSidePills($formulario) . self::makeFormContent($formulario);
        $res = $res . self::makeDiv('class="row"', $contenido);
        $res = $res . "\n";
        $res = $res . '</form>';
        return $res;
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
        $datos = $formulario->makeDatos();
        $res = '';
        $first = true;
        foreach ($formulario->getItems() as $item) {
            if ($first) {
                $first = false;
            } else {
                $res = $res . "\n";
            }
            $contenido = self::makeItemContent($item, $datos);
            $opcionesDiv = 'div class="tab-pane fade show active" id="v-pills-' . $item->getNombre() . '" role="tabpanel" aria-labelledby="v-pills-' . $item->getNombre() . '-tab"';
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
     * @return string
     */
    private static function makeItemContent($item, $datos)
    {
        $contenido = '';
        foreach ($item->getNest() as $nestItem) {
            $contenido = $contenido . self::makeItemContent($nestItem, $datos);
        }
        if ($item->getTipo()->getTipoInput() == null) {
            $contenido = self::makeLegend($item) . $contenido;
        } else {
            if ($item->getTipo()->getTipoInput() == TipoItem::CHECKBOX || $item->getTipo()->getTipoInput() == TipoItem::RADIO) {
                $contenido = self::makeMultiInput($item, $datos) . $contenido;
            } else {
                $contenido = self::makeSimpleInput($item, $datos) . $contenido;
            }
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
            if ($first) {
                $first = false;
            } else {
                $res = $res . "\n";
            }
            $opciones = 'class="nav-link active" id="v-pills-' . $item->getNombre() . '-tab" data-toggle="pill" href="#v-pills-' . $item->getNombre() . '" role="tab" aria-controls="v-pills-' . $item->getNombre() . '" aria-selected="true"';
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
     * @return string
     */
    private static function makeSimpleInput($item, $datos)
    {
        $contenido = $item->getEtiqueta();
        $contenido = $contenido . '<input class="form-control" type="' . $item->getTipo()->getTipoInput() . '" ';
        $contenido = $contenido . 'name="' . $item->getNombrePost() . '" ';
        if ($item->getRequerido()) {
            $contenido = $contenido . 'required ';
        }
        $contenido = $contenido . self::checkSimpleValue($item, $datos);
        $contenido = $contenido . $item->getOpciones() . '>';
        $contenido = self::makeLabel('', $contenido);
        return $contenido;
    }

    /**
     * Crea el input de un item con entrada múltiple
     *
     * @param ItemFormulario $item
     * @param DatosTabla[] $datos
     * @return string
     */
    private static function makeMultiInput($item, $datos)
    {
        $res = self::makeLegend($item);
        $first = true;
        $values = self::checkMultiValues($item, $datos);
        foreach ($item->getValores() as $valor) {
            if ($first) {
                $first = false;
            } else {
                $res = $res . "\n";
            }
            $contenido = '<input class="" type="' . $item->getTipo()->getTipoInput() . '" ';
            $contenido = $contenido . 'name="' . $item->getNombrePost() . '" ';
            if ($item->getRequerido()) {
                $contenido = $contenido . 'required ';
            }
            $contenido = $contenido . 'value="' . $valor . '" ';
            if (in_array($valor, $values)) {
                $contenido = $contenido . 'checked ';
            }
            $contenido = $contenido . $item->getOpciones() . '>';
            $contenido = $contenido . $item->getEtiqueta();
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
    private static function checkSimpleValue($item, $datos)
    {
        $res = '';
        if ($datos != NULL) {
            $thisDatos = self::getItemData($item, $datos);
            if ($thisDatos != NULL) {
                if (array_key_exists($item->getNombre(), $thisDatos->getCampos())) {
                    if ($thisDatos->getCampos()[$item->getNombre()] != NULL) {
                        $res = 'value="' . $thisDatos->getCampos()[$item->getNombre()] . '" ';
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
            if ($thisDatos != NULL) {
                if ($item->getTipo()->getTipoInput() == TipoItem::CHECKBOX) {
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
                    $res[] = TipoItem::SI;
                } else if ($item->getTipo() === TipoItem::metastasis()) {
                    if (array_key_exists($item->getTablaCirugia()->getNombreTabla(), $datos)) {
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
     * Devuelve lod datos de la tabala a la que pertenece el item
     *
     * @param ItemFormulario $item
     * @param DatosTabla[] $datos
     * @return DatosTabla | NULL
     */
    private static function getItemData($item, $datos)
    {
        $thisDatos = NULL;
        if ($item->getTabla() != NULL) {
            if (array_key_exists($item->getTabla()->getNombreTabla(), $datos)) {
                $thisDatos = $datos[$item->getTabla()->getNombreTabla()];
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

