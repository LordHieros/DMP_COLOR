<?php

final class Formulario
{

    private $items;

    private $claves;

    private $action;
    
    private $legend;
    
    private $method;
    
    private $modelo;

    /**
     * Devuelve los items del formulario
     *
     * @return ItemFormulario[]
     */
    function getItems()
    {
        return $this->items;
    }

    /**
     * Devuelve las claves del formulario (guardadas en session)
     *
     * @return string[]
     */
    function getClaves()
    {
        return $this->claves;
    }

    /**
     * Devuelve la accion del formulario
     * String vacío por defecto
     *
     * @return string
     */
    function getAction()
    {
        return $this->action;
    }
    
    /**
     * Devuelve la leyenda del formulario
     * null por defecto
     *
     * @return string
     */
    function getLegend()
    {
        return $this->legend;
    }
    
    /**
     * Devuelve el method del formulario
     * post por defecto
     *
     * @return string
     */
    function getMethod()
    {
        return $this->method;
    }
    
    /**
     * Devuelve el modelo del formulario
     *
     * @return Modelo
     */
    function getModelo()
    {
        return $this->modelo;
    }

    /**
     * Cosntructior, requiere itemsFormulario, claves y modelo
     * 
     * @param ItemFormulario[] $items
     * @param string[] $claves
     * @param Modelo $modelo
     */
    private function __construct($items, $claves, $modelo)
    {
        $this->items = $items;
        $this->claves = $claves;
        $this->action = '';
        $this->legend = null;
        $this->method = 'post';
        $this->modelo = $modelo;
    }

    private static $formLogin;

    /**
     * Formulario de Login
     * Singleton
     *
     * @return Formulario
     */
    static function formLogin()
    {
        if (! isset(self::$formLogin)) {
            $items = array(
                self::grupoLogin()
            );
            $claves = array();
            self::$formLogin = new Formulario($items, $claves, Modelo::modeloUsuarios());
            self::$formLogin->legend = 'Iniciar sesión';
        }
        return self::$formLogin;
    }

    private static $formDiagnostico;

    /**
     * Formulario de Diagnóstico
     * Singleton
     *
     * @return Formulario
     */
    static function formDiagnostico()
    {
        if (! isset(self::$formDiagnostico)) {
            $items = array(
                self::grupoDiagnosticoPrincipal(),
                self::grupoMetastasis(),
                self::grupoPreoperatorio()
            );
            $claves = array(
                CampoSession::NASI,
                CampoSession::FECHA_DIAGNOSTICO
            );
            self::$formDiagnostico = new Formulario($items, $claves, Modelo::modeloDiagnostico());
            self::$formDiagnostico->action = 'php/diagnostico_datos.php';
            self::$formDiagnostico->legend = 'Diagnóstico';
        }
        return self::$formDiagnostico;
    }

    private static $formIntervencion;

    /**
     * Formulario de Intervención
     * Singleton
     *
     * @return Formulario
     */
    static function formIntervencion()
    {
        if (! isset(self::$formIntervencion)) {
            $items = array(
                self::grupoIntervencionPrincipal(),
                self::grupoPostoperatorio(),
                self::grupoEstudioAnatomopatologico()
            );
            $claves = array(
                CampoSession::NASI,
                CampoSession::FECHA_DIAGNOSTICO,
                CampoSession::FECHA_INTERVENCION
            );
            self::$formIntervencion = new Formulario($items, $claves, Modelo::modeloIntervencion());
            self::$formIntervencion->action = 'php/intervencion_datos.php';
            self::$formIntervencion->legend = 'Intervención';
        }
        return self::$formIntervencion;
    }

    /**
     * Crea el grupo de items de diagnóstico principal
     *
     * @return ItemFormulario
     */
    private static function grupoDiagnosticoPrincipal()
    {
        $etiqueta = 'Principal';
        $nest = array(
            ItemFormulario::sexo(),
            ItemFormulario::edad(),
            ItemFormulario::talla(),
            ItemFormulario::peso(),
            ItemFormulario::fechaIngreso(),
            ItemFormulario::causasIntervencion(),
            ItemFormulario::anticoagulantes()
        );
        return ItemFormulario::makeGroup($etiqueta, $nest);
    }

    /**
     * Crea el grupo de items de metástasis
     *
     * @return ItemFormulario
     */
    private static function grupoMetastasis()
    {
        $etiqueta = 'Metástasis';
        $nest = array(
            ItemFormulario::hayMetastasisHepatica(),
            ItemFormulario::hayMetastasisPulmonar(),
            ItemFormulario::hayMetastasis()
        );
        return ItemFormulario::makeGroup($etiqueta, $nest);
    }

    /**
     * Crea el grupo de items de preoperatorio
     *
     * @return ItemFormulario
     */
    private static function grupoPreoperatorio()
    {
        $etiqueta = 'Preoperatorio';
        $nest = array(
            ItemFormulario::cea(),
            ItemFormulario::hemoglobina(),
            ItemFormulario::albumina(),
            ItemFormulario::asa(),
            ItemFormulario::intoxicaciones(),
            ItemFormulario::comorbilidades(),
            ItemFormulario::hayEndoscopia(),
            ItemFormulario::hayBiopsia(),
            ItemFormulario::hayPresentacion(),
            ItemFormulario::localizacionesCancer(),
            ItemFormulario::hayTumoresSincronicos(),
            ItemFormulario::hayProtesis()
        );
        return ItemFormulario::makeGroup($etiqueta, $nest);
    }

    /**
     * Crea el grupo de items de intervención principal
     *
     * @return ItemFormulario
     */
    private static function grupoIntervencionPrincipal()
    {
        $etiqueta = 'Principal';
        $nest = array(
            ItemFormulario::duracion(),
            ItemFormulario::hayListaEspera(),
            ItemFormulario::tiposIntervencion(),
            ItemFormulario::codigoCirujano(),
            ItemFormulario::hayUrgente(),
            ItemFormulario::accesos(),
            ItemFormulario::tipoReseccion(),
            ItemFormulario::margenAfecto(),
            ItemFormulario::hayExcision(),
            ItemFormulario::hayAnastomosis(),
            ItemFormulario::rio(),
            ItemFormulario::hayEstoma(),
            ItemFormulario::hayIntervencionesAsociadas()
        );
        return ItemFormulario::makeGroup($etiqueta, $nest);
    }

    /**
     * Crea el grupo de items de postoperatorio
     *
     * @return ItemFormulario
     */
    private static function grupoPostoperatorio()
    {
        $etiqueta = 'Postoperatorio';
        $nest = array(
            ItemFormulario::fechaAlta(),
            ItemFormulario::hayComplicacionesIntraoperatorias(),
            ItemFormulario::hayComplicacionesCirugia(),
            ItemFormulario::hayComplicacionesMedicas(),
            ItemFormulario::hayTransfusiones(),
            ItemFormulario::hayNeoadyuvancia(),
            ItemFormulario::reingreso30Dias()
        );
        return ItemFormulario::makeGroup($etiqueta, $nest);
    }

    /**
     * Crea el grupo de items de estudio anatomopatológico
     *
     * @return ItemFormulario
     */
    private static function grupoEstudioAnatomopatologico()
    {
        $etiqueta = 'Estudio Anatomopatológico';
        $nest = array(
            ItemFormulario::histologia(),
            ItemFormulario::TNM(),
            ItemFormulario::distanciaMargenDistal(),
            ItemFormulario::gradoDiferenciacion(),
            ItemFormulario::factoresRiesgo(),
            ItemFormulario::hayCancerRecto()
        );
        return ItemFormulario::makeGroup($etiqueta, $nest);
    }

    /**
     * Crea el grupo de items de login
     *
     * @return ItemFormulario
     */
    private static function grupoLogin()
    {
        $etiqueta = 'Login';
        $nest = array(
            ItemFormulario::nombreUsuario(),
            ItemFormulario::contrasenha()
        );
        return ItemFormulario::makeGroup($etiqueta, $nest);
    }

    /**
     * Devuelve el array de datos correspondiente al formulario, cogiendo los datos de POST
     * null si no hay datos en post correspondientes
     *
     * @throws Exception
     * @return DatosTabla[] | NULL
     */
    public function makeDatos()
    {
        $datos = array();
        foreach ($this->getItems() as $item) {
            $datos = $item->makeDatos($datos);
        }
        return $datos;
    }
    
    /**
     * Crea el html del formulario
     *
     * @throws Exception
     * @return string
     */
    public function makeHtml()
    {
        return MakeForm::makeForm($this);
    }
}