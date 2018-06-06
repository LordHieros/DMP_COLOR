<?php

final class MakeForm
{

    /**
     * Crea el formulario
     *
     * @param Formulario $formulario
     * @return string
     */
    public static function makeForm($formulario)
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
     * Crea el contenido e inputs del formulario
     *
     * @param Formulario $formulario
     * @return string
     */
    private static function makeFormContent($formulario)
    {
        $res = '';
        $first = true;
        foreach ($formulario->getItems() as $item) {
            if ($first) {
                $first = false;
            } else {
                $res = $res . "\n";
            }
            $contenido = self::makeItemContent($item);
            $opcionesDiv = 'div class="tab-pane fade show active" id="v-pills-' . $item->getNombre() . '" role="tabpanel" aria-labelledby="v-pills-' . $item->getNombre() . '-tab"';
            $res = $res . self::makeDiv($opcionesDiv, $contenido);
        }
        $res = self::makeDiv('class="tab-content" id="v-pills-tabContent"', $res);
        $res = self::makeDiv('class = "col-9"', $res);
        return $res;
    }
    
    /**
     * Crea el contenido de un ItemFormulario
     * 
     * @param ItemFormulario $item
     * @return string
     */
    private static function makeItemContent($item){
        //TODO cosas
        return '';
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
        $res = '<div ' . $opciones . '>';
        $res = $res . "\n";
        $res = $res . $contenido;
        $res = $res . "\n";
        $res = $res . '</div>';
        return $res;
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
        $res = '<a ' . $opciones . '>';
        $res = $res . "\n";
        $res = $res . $contenido;
        $res = $res . "\n";
        $res = $res . '</a>';
        return $res;
    }
}

