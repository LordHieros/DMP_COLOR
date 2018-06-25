<?php

include('Columna.php');
include('TipoItem.php');
include('DatosTabla.php');

final class ItemFormulario
{

    private $tipo;

    private $etiqueta;

    private $nombre;

    private $opciones;

    private $requerido;

    private $nest;

    private $tabla;

    private $columna;

    private $valores;
    
    private $tablaCirugia;

    /**
     * Devuelve el tipo de item
     *
     * @return TipoItem
     */
    function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Devuelve la etiqueta con que se verá el item
     * por defecto se pone a null, si no se especifica se iguala al nombre capitalizado
     * problema si se hace el get sin haber especificado ni nombre ni etiqueta
     *
     * @return string
     */
    function getEtiqueta()
    {
        if ($this->etiqueta == null) {
            $this->etiqueta = ucfirst($this->nombre);
        }
        return $this->etiqueta;
    }

    /**
     * Devuelve el nombre de item
     * null por defecto
     * si no es null debiera aparecer sin caracteres especiales ni espacios
     *
     * @return string
     */
    function getNombre()
    {
        if ($this->nombre == null) {
            if ($this->getTipo() === TipoItem::agrupacion()) {
                $this->nombre = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $this->etiqueta)); // Elimina espacios y caracteres epeciales
            } else if ($this->getColumna() != null) {
                if ($this->getTipo() === TipoItem::siNo() || $this->getTipo() === TipoItem::metastasis()) {
                    $this->nombre = 'hay' . ucfirst($this->getColumna()->getNombre());
                } else {
                    $this->nombre = $this->getColumna()->getNombre();
                }
            } else if ($this->getTabla() != null) {
                $this->nombre = 'hay' . ucfirst($this->getTabla()->getNombreTabla());
            }
        }
        return $this->nombre;
    }

    /**
     * Devuelve el nombre de item para formato post.
     * Solo es distinto para los checkbox
     * null por defecto
     *
     * @return string
     */
    function getNombrePost()
    {
        if ($this->getNombre() != null && $this->getTipo() === TipoItem::checkbox()) {
            return $this->getNombre() . '[]';
        }
        return $this->getNombre();
    }

    /**
     * Devuelve las opciones del formulario
     * String vacío por defecto
     *
     * @return string
     */
    function getOpciones()
    {
        return $this->opciones;
    }

    /**
     * Devuelve la tabla asociada al item del formulario
     * null por defecto
     * si hay columna devuelve la tabla asociada a la columna
     *
     * @return Tabla
     */
    function getTabla()
    {
        if($this->tabla == NULL){
            if($this->getColumna()!=NULL){
                $this->tabla = $this->getColumna()->getTabla();
            }
        }
        return $this->tabla;
    }

    /**
     * Devuelve la columna asociada al item del formulario
     * null por defecto
     *
     * @return Columna
     */
    function getColumna()
    {
        return $this->columna;
    }

    /**
     * Devuelve el nest del item,
     * array vacío por defecto
     *
     * @return ItemFormulario[]
     */
    function getNest()
    {
        return $this->nest;
    }
    
    /**
     * Devuelve la tabla de cirugía asociada a la metástasis
     * solo existe en caso de que sea un item de tipo metástasis
     * null por defecto (por que por qué no?)
     *
     * @return Tabla
     */
    function getTablaCirugia()
    {
        return $this->tablaCirugia;
    }

    /**
     * Devuelve los valores del item
     * Null por defecto, pero si no se ha definido comprueba el tipo y se define
     * De ser Si/No o Mestástasis se incluye lo pertinente, en caso contrario array vacío
     * Problema si se hace el get sin haber especificado ni valores ni tipoItem
     *
     * @return string[]
     */
    function getValores()
    {
        if ($this->valores == null) {
            if ($this->tipo === TipoItem::metastasis()) {
                $this->valores = array(
                    TipoItem::NO,
                    TipoItem::SIN_CIRUGIA,
                    TipoItem::CON_CIRUGIA
                );
            } else if ($this->tipo === TipoItem::siNo() || $this->tipo === TipoItem::boolean()) {
                $this->valores = array(
                    TipoItem::NO,
                    TipoItem::SI
                );
            } else {
                $this->valores = array();
            }
        }
        return $this->valores;
    }

    /**
     * Devuelve si el campo es requerido
     * false por defecto
     *
     * @return boolean
     */
    function getRequerido()
    {
        return $this->requerido;
    }

    // Impide que la clase se instancie desde fuera, inicializa los valores pertinentes por defecto
    private function __construct()
    {
        $this->requerido = false;
        $this->valores = null;
        $this->nombre = null;
        $this->etiqueta = null;
        $this->nest = array();
        $this->opciones = '';
        $this->tabla = null;
        $this->columna = null;
        $this->tablaCirugia = null;
    }

    /**
     * Completa el array de datos del item.
     * Excepcion en caso de problemas en la carga de datos.
     *
     * @param DatosTabla[] $datos
     * @throws Exception
     * @return DatosTabla[]
     */
    public function makeDatos($datos)
    {
        $checkSub = true;
        if ($this->getTipo() !== TipoItem::agrupacion()) {
            if (isset($_POST[$this->getNombrePost()])) {
                $valor = $_POST[$this->getNombrePost()];
                if ($this->getTipo() === TipoItem::siNo()) {
                    $checkSub = ($valor == TipoItem::SI);
                } else if ($this->getTipo() === TipoItem::metastasis()) {
                    if ($valor != TipoItem::NO) {
                        $datos[$this->getTabla()->getNombreTabla()] = DatosTabla::makeWithSessionKeys($this->getTabla());
                    }
                    $checkSub = ($valor == TipoItem::CON_CIRUGIA);
                } else {
                    $columna = $this->getColumna()
                        ->getTabla()
                        ->getNombreTabla();
                    if (! array_key_exists($columna, $datos)) {
                        $datos[$columna] = DatosTabla::makeWithSessionKeys($this->getTabla());
                    }
                    $datos[$columna]->addCampo($valor, $this->getColumna());
                }
            } else if ($this->getTipo() === TipoItem::siNo() || $this->getTipo() === TipoItem::metastasis()) {
                $checkSub = false;
            }
        }
        if ($checkSub) {
            foreach ($this->getNest() as $subitem) {
                $subitem->makeDatos($datos);
            }
        }
        return $datos;
    }

    /**
     * Crea una agrupacion de items
     *
     * @param string $etiqueta
     * @param ItemFormulario[] $nest
     * @return ItemFormulario
     */
    static function makeGroup($etiqueta, $nest)
    {
        $group = new ItemFormulario();
        $group->tipo = TipoItem::agrupacion();
        $group->etiqueta = $etiqueta;
        $group->nest = $nest;
        return $group;
    }

    // Login
    private static $nombreUsuario;

    /**
     * Singleton de nombreUsuario
     *
     * @return ItemFormulario
     */
    static function nombreUsuario()
    {
        if (! isset(self::$sexo)) {
            self::$nombreUsuario = new ItemFormulario();
            self::$nombreUsuario->columna = Columna::nombreUsuario();
            self::$nombreUsuario->tipo = TipoItem::text();
            self::$nombreUsuario->etiqueta = 'Nombre de usuario';
        }
        return self::$nombreUsuario;
    }

    private static $contrasenha;

    /**
     * Singleton de contrasenha
     *
     * @return ItemFormulario
     */
    static function contrasenha()
    {
        if (! isset(self::$sexo)) {
            self::$contrasenha = new ItemFormulario();
            self::$contrasenha->columna = Columna::contrasenha();
            self::$contrasenha->tipo = TipoItem::password();
            self::$contrasenha->etiqueta = 'Contraseña';
        }
        return self::$contrasenha;
    }

    // Diagnóstico
    private static $sexo;

    /**
     * Singleton de sexo
     *
     * @return ItemFormulario
     */
    static function sexo()
    {
        if (! isset(self::$sexo)) {
            self::$sexo = new ItemFormulario();
            self::$sexo->columna = Columna::sexo();
            self::$sexo->tipo = TipoItem::radio();
            self::$sexo->valores = [
                'Varón',
                'Mujer',
                'Otro'
            ];
        }
        return self::$sexo;
    }

    private static $edad;

    /**
     * Singleton de edad
     *
     * @return ItemFormulario
     */
    static function edad()
    {
        if (! isset(self::$edad)) {
            self::$edad = new ItemFormulario();
            self::$edad->columna = Columna::edad();
            self::$edad->tipo = TipoItem::number();
            self::$edad->opciones = 'min="0" max="200"';
        }
        return self::$edad;
    }

    private static $talla;

    /**
     * Singleton de talla
     *
     * @return ItemFormulario
     */
    static function talla()
    {
        if (! isset(self::$talla)) {
            self::$talla = new ItemFormulario();
            self::$talla->columna = Columna::talla();
            self::$talla->tipo = TipoItem::number();
            self::$talla->etiqueta = 'Talla (cm)';
            self::$talla->opciones = 'min="10" max="300"';
        }
        return self::$talla;
    }

    private static $peso;

    /**
     * Singleton de peso
     *
     * @return ItemFormulario
     */
    static function peso()
    {
        if (! isset(self::$peso)) {
            self::$peso = new ItemFormulario();
            self::$peso->columna = Columna::peso();
            self::$peso->tipo = TipoItem::number();
            self::$peso->etiqueta = 'Peso (Kg)';
            self::$peso->opciones = 'min="1" max="1000"';
        }
        return self::$peso;
    }

    private static $fechaIngreso;

    /**
     * Singleton de fechaIngreso
     *
     * @return ItemFormulario
     */
    static function fechaIngreso()
    {
        if (! isset(self::$fechaIngreso)) {
            self::$fechaIngreso = new ItemFormulario();
            self::$fechaIngreso->columna = Columna::fechaIngreso();
            self::$fechaIngreso->tipo = TipoItem::date();
            self::$fechaIngreso->etiqueta = 'Fecha de ingreso';
        }
        return self::$fechaIngreso;
    }

    private static $causasIntervencion;

    /**
     * Singleton de causasIntervencion
     *
     * @return ItemFormulario
     */
    static function causasIntervencion()
    {
        if (! isset(self::$causasIntervencion)) {
            self::$causasIntervencion = new ItemFormulario();
            self::$causasIntervencion->columna = Columna::causasIntervencion();
            self::$causasIntervencion->tipo = TipoItem::checkbox();
            self::$causasIntervencion->etiqueta = 'Diagnóstico que causa intervención';
            self::$causasIntervencion->valores = [
                'Cancer de recto (hasta 15 cm del OECA)',
                'Cancer de ano',
                'Cancer de colon (más de 15 cm del OECA)',
                'Enfermedad de CROHN',
                'Enfermedad de CROHN perineal',
                'C.U.',
                'Enfermedad diverticular aguda',
                'Enfermedad diverticular crónica',
                'Estoma / Colostomia',
                'Estoma / Ileostomia',
                'Vólvulo',
                'Suelo pélvico / Celes',
                'Incontinencia Fecal',
                'Poliposis',
                'Isquemia',
                'Traumatismos',
                'Cuerpos extraños',
                'Ogilvie'
            ];
        }
        return self::$causasIntervencion;
    }

    private static $anticoagulantes;

    /**
     * Singleton de anticoagulantes
     *
     * @return ItemFormulario
     */
    static function anticoagulantes()
    {
        if (! isset(self::$anticoagulantes)) {
            self::$anticoagulantes = new ItemFormulario();
            self::$anticoagulantes->columna = Columna::anticoagulantes();
            self::$anticoagulantes->tipo = TipoItem::boolean();
            self::$anticoagulantes->etiqueta = 'Anticoagulantes Orales';
        }
        return self::$anticoagulantes;
    }

    private static $hayMetastasisHepatica;

    /**
     * Singleton de hayMetastasisHepatica
     *
     * @return ItemFormulario
     */
    static function hayMetastasisHepatica()
    {
        if (! isset(self::$hayMetastasisHepatica)) {
            self::$hayMetastasisHepatica = new ItemFormulario();
            self::$hayMetastasisHepatica->tabla = Tabla::MetastasisHepaticas();
            self::$hayMetastasisHepatica->tipo = TipoItem::metastasis();
            self::$hayMetastasisHepatica->etiqueta = 'Metástasis hepáticas';
            self::$hayMetastasisHepatica->nest = array(
                self::tipoCirugiaHepatica(),
                self::tecnicaCirugiaHepatica(),
                self::fechaCirugiaHepatica()
            );
            self::$hayMetastasisHepatica->tablaCirugia = Tabla::CirugiasHepaticas();
        }
        return self::$hayMetastasisHepatica;
    }

    private static $tipoCirugiaHepatica;

    /**
     * Singleton de tipoCirugiaHepatica
     *
     * @return ItemFormulario
     */
    static function tipoCirugiaHepatica()
    {
        if (! isset(self::$tipoCirugiaHepatica)) {
            self::$tipoCirugiaHepatica = new ItemFormulario();
            self::$tipoCirugiaHepatica->columna = Columna::tipoCirugiaHepatica();
            self::$tipoCirugiaHepatica->tipo = TipoItem::radio();
            self::$tipoCirugiaHepatica->etiqueta = 'Tipo de cirugía de las metástasis hepáticas';
            self::$tipoCirugiaHepatica->valores = [
                'Sincrónica laparoscópica',
                'Sincrónica laparotomía',
                'Terapia inversa',
                'Terapia secuencial'
            ];
        }
        return self::$tipoCirugiaHepatica;
    }

    private static $tecnicaCirugiaHepatica;

    /**
     * Singleton de tecnicaCirugiaHepatica
     *
     * @return ItemFormulario
     */
    static function tecnicaCirugiaHepatica()
    {
        if (! isset(self::$tecnicaCirugiaHepatica)) {
            self::$tecnicaCirugiaHepatica = new ItemFormulario();
            self::$tecnicaCirugiaHepatica->columna = Columna::tecnicaCirugiaHepatica();
            self::$tecnicaCirugiaHepatica->tipo = TipoItem::radio();
            self::$tecnicaCirugiaHepatica->etiqueta = 'Técnica de cirugía de las metástasis hepáticas';
            self::$tecnicaCirugiaHepatica->valores = [
                'Metastasectomía',
                'Segmentectomía',
                'Hepatectomía',
                'Embilización',
                'Alcoholización',
                'Ligadura porta'
            ];
        }
        return self::$tecnicaCirugiaHepatica;
    }

    private static $fechaCirugiaHepatica;

    /**
     * Singleton de fechaCirugiaHepatica
     *
     * @return ItemFormulario
     */
    static function fechaCirugiaHepatica()
    {
        if (! isset(self::$fechaCirugiaHepatica)) {
            self::$fechaCirugiaHepatica = new ItemFormulario();
            self::$fechaCirugiaHepatica->columna = Columna::fechaCirugiaHepatica();
            self::$fechaCirugiaHepatica->tipo = TipoItem::date();
            self::$fechaCirugiaHepatica->etiqueta = 'Fecha de cirugía de las metástasis hepáticas';
        }
        return self::$fechaCirugiaHepatica;
    }

    private static $hayMetastasisPulmonar;

    /**
     * Singleton de hayMetastasisPulmonar
     *
     * @return ItemFormulario
     */
    static function hayMetastasisPulmonar()
    {
        if (! isset(self::$hayMetastasisPulmonar)) {
            self::$hayMetastasisPulmonar = new ItemFormulario();
            self::$hayMetastasisPulmonar->tabla = Tabla::MetastasisPulmonares();
            self::$hayMetastasisPulmonar->tipo = TipoItem::metastasis();
            self::$hayMetastasisPulmonar->etiqueta = 'Metástasis pulmonares';
            self::$hayMetastasisPulmonar->nest = array(
                self::fechaCirugiaPulmonar()
            );
            self::$hayMetastasisPulmonar->tablaCirugia = Tabla::CirugiasPulmonares();
        }
        return self::$hayMetastasisPulmonar;
    }

    private static $fechaCirugiaPulmonar;

    /**
     * Singleton de fechaCirugiaPulmonar
     *
     * @return ItemFormulario
     */
    static function fechaCirugiaPulmonar()
    {
        if (! isset(self::$fechaCirugiaPulmonar)) {
            self::$fechaCirugiaPulmonar = new ItemFormulario();
            self::$fechaCirugiaPulmonar->columna = Columna::fechaCirugiaPulmonar();
            self::$fechaCirugiaPulmonar->tipo = TipoItem::date();
            self::$fechaCirugiaPulmonar->etiqueta = 'Fecha de cirugía de las metástasis pulmonares';
        }
        return self::$fechaCirugiaPulmonar;
    }

    private static $hayMetastasis;

    /**
     * Singleton de hayMetastasis
     *
     * @return ItemFormulario
     */
    static function hayMetastasis()
    {
        if (! isset(self::$hayMetastasis)) {
            self::$hayMetastasis = new ItemFormulario();
            self::$hayMetastasis->tabla = Tabla::Metastasis();
            self::$hayMetastasis->tipo = TipoItem::siNo();
            self::$hayMetastasis->etiqueta = 'Otras metástasis';
            self::$hayMetastasis->nest = array(
                self::metastasis()
            );
        }
        return self::$hayMetastasis;
    }

    private static $metastasis;

    /**
     * Singleton de metastasis
     *
     * @return ItemFormulario
     */
    static function metastasis()
    {
        if (! isset(self::$metastasis)) {
            self::$metastasis = new ItemFormulario();
            self::$metastasis->columna = Columna::metastasis();
            self::$metastasis->tipo = TipoItem::checkbox();
            self::$metastasis->etiqueta = 'Localización';
            self::$metastasis->valores = [
                'Óseas',
                'Cerebrales',
                'Carcinomatosis',
                'Otra'
            ];
        }
        return self::$metastasis;
    }

    private static $cea;

    /**
     * Singleton de cea
     *
     * @return ItemFormulario
     */
    static function cea()
    {
        if (! isset(self::$cea)) {
            self::$cea = new ItemFormulario();
            self::$cea->columna = Columna::cea();
            self::$cea->tipo = TipoItem::number();
            self::$cea->etiqueta = 'CEA preoperatorio';
        }
        return self::$cea;
    }

    private static $hemoglobina;

    /**
     * Singleton de hemoglobina
     *
     * @return ItemFormulario
     */
    static function hemoglobina()
    {
        if (! isset(self::$hemoglobina)) {
            self::$hemoglobina = new ItemFormulario();
            self::$hemoglobina->columna = Columna::hemoglobina();
            self::$hemoglobina->tipo = TipoItem::number();
            self::$hemoglobina->etiqueta = 'Hemoglobina preoperatoria (en analítica del preoperatorio) (g/L)';
            self::$hemoglobina->opciones = 'min="0"';
        }
        return self::$hemoglobina;
    }

    private static $albumina;

    /**
     * Singleton de albumina
     *
     * @return ItemFormulario
     */
    static function albumina()
    {
        if (! isset(self::$albumina)) {
            self::$albumina = new ItemFormulario();
            self::$albumina->columna = Columna::albumina();
            self::$albumina->tipo = TipoItem::number();
            self::$albumina->etiqueta = 'Albúmina (g/dL)';
            self::$albumina->opciones = 'min="0"';
        }
        return self::$albumina;
    }

    private static $asa;

    /**
     * Singleton de asa
     *
     * @return ItemFormulario
     */
    static function asa()
    {
        if (! isset(self::$asa)) {
            self::$asa = new ItemFormulario();
            self::$asa->columna = Columna::asa();
            self::$asa->tipo = TipoItem::radio();
            self::$asa->etiqueta = 'ASA';
            self::$asa->valores = [
                'I',
                'II',
                'III',
                'IV'
            ];
        }
        return self::$asa;
    }

    private static $intoxicaciones;

    /**
     * Singleton de intoxicaciones
     *
     * @return ItemFormulario
     */
    static function intoxicaciones()
    {
        if (! isset(self::$intoxicaciones)) {
            self::$intoxicaciones = new ItemFormulario();
            self::$intoxicaciones->columna = Columna::intoxicaciones();
            self::$intoxicaciones->tipo = TipoItem::checkbox();
            self::$intoxicaciones->etiqueta = 'Intoxicación';
            self::$intoxicaciones->valores = [
                'Alcohol',
                'Tabaco',
                'Esteroides'
            ];
        }
        return self::$intoxicaciones;
    }

    private static $comorbilidades;

    /**
     * Singleton de comorbilidades
     *
     * @return ItemFormulario
     */
    static function comorbilidades()
    {
        if (! isset(self::$comorbilidades)) {
            self::$comorbilidades = new ItemFormulario();
            self::$comorbilidades->columna = Columna::comorbilidades();
            self::$comorbilidades->tipo = TipoItem::checkbox();
            self::$comorbilidades->etiqueta = 'Comorbilidades';
            self::$comorbilidades->valores = [
                'Cardiológica Valvular',
                'Cardiológica Coronaria',
                'Anticoagulado',
                'Arritmia',
                'Hematológica',
                'Respiratoria',
                'HTA',
                'Diabetes Mellitus',
                'Neurológica',
                'Renal',
                'Inmunodeprimido',
                'Aterosclerosis Periférica',
                'Hepática'
            ];
        }
        return self::$comorbilidades;
    }

    private static $hayEndoscopia;

    /**
     * Singleton de hayEndoscopia
     *
     * @return ItemFormulario
     */
    static function hayEndoscopia()
    {
        if (! isset(self::$hayEndoscopia)) {
            self::$hayEndoscopia = new ItemFormulario();
            self::$hayEndoscopia->columna = Columna::fechaEndoscopia();
            self::$hayEndoscopia->tipo = TipoItem::siNo();
            self::$hayEndoscopia->etiqueta = 'Endoscopia diagnóstica';
            self::$hayEndoscopia->nest = array(
                self::fechaEndoscopia()
            );
        }
        return self::$hayEndoscopia;
    }

    private static $fechaEndoscopia;

    /**
     * Singleton de fechaEndoscopia
     *
     * @return ItemFormulario
     */
    static function fechaEndoscopia()
    {
        if (! isset(self::$fechaEndoscopia)) {
            self::$fechaEndoscopia = new ItemFormulario();
            self::$fechaEndoscopia->columna = Columna::fechaEndoscopia();
            self::$fechaEndoscopia->tipo = TipoItem::date();
            self::$fechaEndoscopia->etiqueta = 'Fecha';
        }
        return self::$fechaEndoscopia;
    }

    private static $hayBiopsia;

    /**
     * Singleton de hayBiopsia
     *
     * @return ItemFormulario
     */
    static function hayBiopsia()
    {
        if (! isset(self::$hayBiopsia)) {
            self::$hayBiopsia = new ItemFormulario();
            self::$hayBiopsia->columna = Columna::fechaBiopsia();
            self::$hayBiopsia->tipo = TipoItem::siNo();
            self::$hayBiopsia->etiqueta = 'Biopsia';
            self::$hayBiopsia->nest = array(
                self::fechaBiopsia()
            );
        }
        return self::$hayBiopsia;
    }

    private static $fechaBiopsia;

    /**
     * Singleton de fechaBiopsia
     *
     * @return ItemFormulario
     */
    static function fechaBiopsia()
    {
        if (! isset(self::$fechaBiopsia)) {
            self::$fechaBiopsia = new ItemFormulario();
            self::$fechaBiopsia->columna = Columna::fechaBiopsia();
            self::$fechaBiopsia->tipo = TipoItem::date();
            self::$fechaBiopsia->etiqueta = 'Fecha';
        }
        return self::$fechaBiopsia;
    }

    private static $hayPresentacion;

    /**
     * Singleton de hayPresentacion
     *
     * @return ItemFormulario
     */
    static function hayPresentacion()
    {
        if (! isset(self::$hayPresentacion)) {
            self::$hayPresentacion = new ItemFormulario();
            self::$hayPresentacion->columna = Columna::fechaPresentacion();
            self::$hayPresentacion->tipo = TipoItem::siNo();
            self::$hayPresentacion->etiqueta = 'Presentación en comité CCR';
            self::$hayPresentacion->nest = array(
                self::fechaPresentacion()
            );
        }
        return self::$hayPresentacion;
    }

    private static $fechaPresentacion;

    /**
     * Singleton de fechaPresentacion
     *
     * @return ItemFormulario
     */
    static function fechaPresentacion()
    {
        if (! isset(self::$fechaPresentacion)) {
            self::$fechaPresentacion = new ItemFormulario();
            self::$fechaPresentacion->columna = Columna::fechaPresentacion();
            self::$fechaPresentacion->tipo = TipoItem::date();
            self::$fechaPresentacion->etiqueta = 'Fecha';
        }
        return self::$fechaPresentacion;
    }

    private static $localizacionesCancer;

    /**
     * Singleton de localizacionesCancer
     *
     * @return ItemFormulario
     */
    static function localizacionesCancer()
    {
        if (! isset(self::$localizacionesCancer)) {
            self::$localizacionesCancer = new ItemFormulario();
            self::$localizacionesCancer->columna = Columna::localizacionesCancer();
            self::$localizacionesCancer->tipo = TipoItem::checkbox();
            self::$localizacionesCancer->etiqueta = 'Localización del cancer';
            self::$localizacionesCancer->valores = [
                'Margen anal',
                'Canal anal',
                'Tercio inferior de recto (0 a 5 cm)',
                'Tercio medio de recto (6 a 10 cm)',
                'Tercio superior de recto (11 a 15 cm)',
                'Unión rectosigmoidea (16 a 20 cm)',
                'Sigma (21 hasta 28 cm)',
                'Colon izquierdo',
                'Flexura esplénica',
                'Colon Transverso',
                'Flexura hepática',
                'Colon derecho',
                'Ciego',
                'Apéndice'
            ];
        }
        return self::$localizacionesCancer;
    }

    private static $hayTumoresSincronicos;

    /**
     * Singleton de hayTumoresSincronicos
     *
     * @return ItemFormulario
     */
    static function hayTumoresSincronicos()
    {
        if (! isset(self::$hayTumoresSincronicos)) {
            self::$hayTumoresSincronicos = new ItemFormulario();
            self::$hayTumoresSincronicos->columna = Columna::TumoresSincronicos();
            self::$hayTumoresSincronicos->tipo = TipoItem::boolean();
            self::$hayTumoresSincronicos->etiqueta = 'Tumor Sincrónico';
            self::$hayTumoresSincronicos->nest = array(
                self::tumoresSincronicos()
            );
        }
        return self::$hayTumoresSincronicos;
    }

    private static $tumoresSincronicos;

    /**
     * Singleton de tumoresSincronicos
     *
     * @return ItemFormulario
     */
    static function tumoresSincronicos()
    {
        if (! isset(self::$tumoresSincronicos)) {
            self::$tumoresSincronicos = new ItemFormulario();
            self::$tumoresSincronicos->columna = Columna::tumoresSincronicos();
            self::$tumoresSincronicos->tipo = TipoItem::checkbox();
            self::$tumoresSincronicos->etiqueta = 'Localización (más proximal)';
            self::$tumoresSincronicos->valores = [
                'Colon derecho',
                'Colon trasverso',
                'Colon izquierdo',
                'Colon sigmoide',
                'Unión rectosigmoidea',
                'Recto'
            ];
        }
        return self::$tumoresSincronicos;
    }

    private static $hayProtesis;

    /**
     * Singleton de hayProtesis
     *
     * @return ItemFormulario
     */
    static function hayProtesis()
    {
        if (! isset(self::$hayProtesis)) {
            self::$hayProtesis = new ItemFormulario();
            self::$hayProtesis->tabla = Tabla::Protesis();
            self::$hayProtesis->tipo = TipoItem::siNo();
            self::$hayProtesis->etiqueta = 'Prótesis';
            self::$hayProtesis->nest = array(
                self::fechaProtesis(),
                self::complicacionProtesis()
            );
        }
        return self::$hayProtesis;
    }

    private static $fechaProtesis;

    /**
     * Singleton de fechaProtesis
     *
     * @return ItemFormulario
     */
    static function fechaProtesis()
    {
        if (! isset(self::$fechaProtesis)) {
            self::$fechaProtesis = new ItemFormulario();
            self::$fechaProtesis->columna = Columna::fechaProtesis();
            self::$fechaProtesis->tipo = TipoItem::date();
            self::$fechaProtesis->etiqueta = 'Fecha de la prótesis';
        }
        return self::$fechaProtesis;
    }

    private static $complicacionProtesis;

    /**
     * Singleton de complicacionProtesis
     *
     * @return ItemFormulario
     */
    static function complicacionProtesis()
    {
        if (! isset(self::$complicacionProtesis)) {
            self::$complicacionProtesis = new ItemFormulario();
            self::$complicacionProtesis->columna = Columna::complicacionProtesis();
            self::$complicacionProtesis->tipo = TipoItem::boolean();
            self::$complicacionProtesis->etiqueta = 'Complicación de la prótesis';
        }
        return self::$complicacionProtesis;
    }

    // Intervencion
    private static $duracion;

    /**
     * Singleton de duracion
     *
     * @return ItemFormulario
     */
    static function duracion()
    {
        if (! isset(self::$duracion)) {
            self::$duracion = new ItemFormulario();
            self::$duracion->columna = Columna::duracion();
            self::$duracion->tipo = TipoItem::number();
            self::$duracion->etiqueta = 'Duración (minutos)';
        }
        return self::$duracion;
    }

    private static $hayListaEspera;

    /**
     * Singleton de hayListaEspera
     *
     * @return ItemFormulario
     */
    static function hayListaEspera()
    {
        if (! isset(self::$hayListaEspera)) {
            self::$hayListaEspera = new ItemFormulario();
            self::$hayListaEspera->columna = Columna::fechaListaEspera();
            self::$hayListaEspera->tipo = TipoItem::radio();
            self::$hayListaEspera->etiqueta = 'Puesto en lista de espera';
            self::$hayListaEspera->valores = [
                'No',
                'Si'
            ];
            self::hayListaEspera()->nest = array(
                self::fechaListaEspera()
            );
        }
        return self::$hayListaEspera;
    }

    private static $fechaListaEspera;

    /**
     * Singleton de fechaListaEspera
     *
     * @return ItemFormulario
     */
    static function fechaListaEspera()
    {
        if (! isset(self::$fechaListaEspera)) {
            self::$fechaListaEspera = new ItemFormulario();
            self::$fechaListaEspera->columna = Columna::fechaListaEspera();
            self::$fechaListaEspera->tipo = TipoItem::date();
            self::$fechaListaEspera->etiqueta = 'Fecha';
        }
        return self::$fechaListaEspera;
    }

    private static $tiposIntervencion;

    /**
     * Singleton de tiposIntervencion
     *
     * @return ItemFormulario
     */
    static function tiposIntervencion()
    {
        if (! isset(self::$tiposIntervencion)) {
            self::$tiposIntervencion = new ItemFormulario();
            self::$tiposIntervencion->columna = Columna::tiposIntervencion();
            self::$tiposIntervencion->tipo = TipoItem::checkbox();
            self::$tiposIntervencion->etiqueta = 'Tipo de intervención realizada';
            self::$tiposIntervencion->valores = [
                'Hemicolectomía derecha',
                'HCD ampliada',
                'Colectomía transversa',
                'Hemicolectomía izquierda',
                'Sigmoidectomía',
                'Colectomía abdominal total',
                'Proctocolectomía',
                'Resección ANT alta',
                'Resección ANT baja',
                'AAP',
                'AAP extendida en prono',
                'Hartmann',
                'Derivación',
                'Colostomía',
                'Ileostomía',
                'Laparotomía exploradora',
                'Laparoscopia exploradora',
                'Transanal (TAMIS)',
                'TaTME',
                'Transanal de Parks',
                'Resección ileocecal',
                'Colectomía segmentaria (atípica)',
                'Otras'
            ];
        }
        return self::$tiposIntervencion;
    }

    private static $codigoCirujano;

    /**
     * Singleton de codigoCirujano
     *
     * @return ItemFormulario
     */
    static function codigoCirujano()
    {
        if (! isset(self::$codigoCirujano)) {
            self::$codigoCirujano = new ItemFormulario();
            self::$codigoCirujano->columna = Columna::codigoCirujano();
            self::$codigoCirujano->tipo = TipoItem::number();
            self::$codigoCirujano->etiqueta = 'Código del cirujano que realiza la intervención';
        }
        return self::$codigoCirujano;
    }

    private static $hayUrgente;

    /**
     * Singleton de hayUrgente
     *
     * @return ItemFormulario
     */
    static function hayUrgente()
    {
        if (! isset(self::$hayUrgente)) {
            self::$hayUrgente = new ItemFormulario();
            self::$hayUrgente->tabla = Tabla::Urgentes();
            self::$hayUrgente->tipo = TipoItem::radio();
            self::$hayUrgente->etiqueta = 'Carácter';
            self::$hayUrgente->valores = [
                'Programada',
                'Urgente'
            ];
            self::$hayUrgente->nest = array(
                self::motivos_Urgente(),
                self::peritonitisUrgente(),
                self::insuficienciaRenalUrgente(),
                self::hemodinamicamenteEstableUrgente()
            );
        }
        return self::$hayUrgente;
    }

    private static $motivos_Urgente;

    /**
     * Singleton de motivos_Urgente
     *
     * @return ItemFormulario
     */
    static function motivos_Urgente()
    {
        if (! isset(self::$motivos_Urgente)) {
            self::$motivos_Urgente = new ItemFormulario();
            self::$motivos_Urgente->columna = Columna::motivos_Urgente();
            self::$motivos_Urgente->tipo = TipoItem::checkbox();
            self::$motivos_Urgente->etiqueta = 'Motivo';
            self::$motivos_Urgente->valores = [
                'Obstrucción',
                'Perforación',
                'Hemorragia',
                'Sépsis',
                'Otros'
            ];
        }
        return self::$motivos_Urgente;
    }

    private static $hemodinamicamenteEstableUrgente;

    /**
     * Singleton de hemodinamicamenteEstableUrgente
     *
     * @return ItemFormulario
     */
    static function hemodinamicamenteEstableUrgente()
    {
        if (! isset(self::$hemodinamicamenteEstableUrgente)) {
            self::$hemodinamicamenteEstableUrgente = new ItemFormulario();
            self::$hemodinamicamenteEstableUrgente->columna = Columna::hemodinamicamenteEstableUrgente();
            self::$hemodinamicamenteEstableUrgente->tipo = TipoItem::boolean();
            self::$hemodinamicamenteEstableUrgente->etiqueta = 'Hemodinamicamente estable';
        }
        return self::$hemodinamicamenteEstableUrgente;
    }

    private static $insuficienciaRenalUrgente;

    /**
     * Singleton de insuficienciaRenalUrgente
     *
     * @return ItemFormulario
     */
    static function insuficienciaRenalUrgente()
    {
        if (! isset(self::$insuficienciaRenalUrgente)) {
            self::$insuficienciaRenalUrgente = new ItemFormulario();
            self::$insuficienciaRenalUrgente->columna = Columna::insuficienciaRenalUrgente();
            self::$insuficienciaRenalUrgente->tipo = TipoItem::boolean();
            self::$insuficienciaRenalUrgente->etiqueta = 'Insuficiencia renal';
        }
        return self::$insuficienciaRenalUrgente;
    }

    private static $peritonitisUrgente;

    /**
     * Singleton de peritonitisUrgente
     *
     * @return ItemFormulario
     */
    static function peritonitisUrgente()
    {
        if (! isset(self::$peritonitisUrgente)) {
            self::$peritonitisUrgente = new ItemFormulario();
            self::$peritonitisUrgente->columna = Columna::peritonitisUrgente();
            self::$peritonitisUrgente->tipo = TipoItem::radio();
            self::$peritonitisUrgente->etiqueta = 'Peritonitis';
            self::$peritonitisUrgente->valores = [
                'No',
                'Purulenta',
                'Fecaloidea'
            ];
        }
        return self::$peritonitisUrgente;
    }

    private static $accesos;

    /**
     * Singleton de accesos
     *
     * @return ItemFormulario
     */
    static function accesos()
    {
        if (! isset(self::$accesos)) {
            self::$accesos = new ItemFormulario();
            self::$accesos->columna = Columna::accesos();
            self::$accesos->tipo = TipoItem::checkbox();
            self::$accesos->etiqueta = 'Acceso';
            self::$accesos->valores = [
                'Laparoscopia',
                'Laparotomía',
                'Conversión',
                'Laparoscopia asistida',
                'Transanal',
                'Estoma',
                'Perineal'
            ];
        }
        return self::$accesos;
    }

    private static $tipoReseccion;

    /**
     * Singleton de tipoReseccion
     *
     * @return ItemFormulario
     */
    static function tipoReseccion()
    {
        if (! isset(self::$tipoReseccion)) {
            self::$tipoReseccion = new ItemFormulario();
            self::$tipoReseccion->columna = Columna::tipoReseccion();
            self::$tipoReseccion->tipo = TipoItem::radio();
            self::$tipoReseccion->etiqueta = 'Tipo de resección';
            self::$tipoReseccion->valores = [
                'R0',
                'R1',
                'R2'
            ];
        }
        return self::$tipoReseccion;
    }

    private static $margenAfecto;

    /**
     * Singleton de margenAfecto
     *
     * @return ItemFormulario
     */
    static function margenAfecto()
    {
        if (! isset(self::$margenAfecto)) {
            self::$margenAfecto = new ItemFormulario();
            self::$margenAfecto->columna = Columna::margenAfecto();
            self::$margenAfecto->tipo = TipoItem::radio();
            self::$margenAfecto->etiqueta = 'Margen Afecto';
            self::$margenAfecto->valores = [
                'Margen distal afecto',
                'Margen proximal afecto',
                'Margen circunferencial afecto'
            ];
        }
        return self::$margenAfecto;
    }

    private static $hayExcision;

    /**
     * Singleton de hayExcision
     *
     * @return ItemFormulario
     */
    static function hayExcision()
    {
        if (! isset(self::$hayExcision)) {
            self::$hayExcision = new ItemFormulario();
            self::$hayExcision->columna = Columna::excision();
            self::$hayExcision->tipo = TipoItem::siNo();
            self::$hayExcision->etiqueta = 'Excisión mesorrectal';
            self::$hayExcision->nest = array(
                self::excision()
            );
        }
        return self::$hayExcision;
    }

    private static $excision;

    /**
     * Singleton de excision
     *
     * @return ItemFormulario
     */
    static function excision()
    {
        if (! isset(self::$excision)) {
            self::$excision = new ItemFormulario();
            self::$excision->columna = Columna::excision();
            self::$excision->tipo = TipoItem::radio();
            self::$excision->etiqueta = 'Tipo de excisión mesorrectal';
            self::$excision->valores = [
                'Subtotal',
                'Total (ETM)'
            ];
        }
        return self::$excision;
    }

    private static $hayAnastomosis;

    /**
     * Singleton de hayAnastomosis
     *
     * @return ItemFormulario
     */
    static function hayAnastomosis()
    {
        if (! isset(self::$hayAnastomosis)) {
            self::$hayAnastomosis = new ItemFormulario();
            self::$hayAnastomosis->tabla = Tabla::Anastomosis();
            self::$hayAnastomosis->tipo = TipoItem::siNo();
            self::$hayAnastomosis->etiqueta = 'Anastomosis';
            self::$hayAnastomosis->nest = array(
                self::tipoAnastomosis(),
                self::tecnicaAnastomosis(),
                self::modalidadAnastomosis()
            );
        }
        return self::$hayAnastomosis;
    }

    private static $tipoAnastomosis;

    /**
     * Singleton de tipoAnastomosis
     *
     * @return ItemFormulario
     */
    static function tipoAnastomosis()
    {
        if (! isset(self::$tipoAnastomosis)) {
            self::$tipoAnastomosis = new ItemFormulario();
            self::$tipoAnastomosis->columna = Columna::tipoAnastomosis();
            self::$tipoAnastomosis->tipo = TipoItem::radio();
            self::$tipoAnastomosis->etiqueta = 'Tipo';
            self::$tipoAnastomosis->valores = [
                'Ileo-cólica',
                'Ileo-rectal',
                'Colo-cólica',
                'Colo-rectal',
                'Colo-anal',
                'Ileo-anal con reservorio en j'
            ];
        }
        return self::$tipoAnastomosis;
    }

    private static $tecnicaAnastomosis;

    /**
     * Singleton de tecnicaAnastomosis
     *
     * @return ItemFormulario
     */
    static function tecnicaAnastomosis()
    {
        if (! isset(self::$tecnicaAnastomosis)) {
            self::$tecnicaAnastomosis = new ItemFormulario();
            self::$tecnicaAnastomosis->columna = Columna::tecnicaAnastomosis();
            self::$tecnicaAnastomosis->tipo = TipoItem::radio();
            self::$tecnicaAnastomosis->etiqueta = 'Técnica';
            self::$tecnicaAnastomosis->valores = [
                'Manual',
                'Mecánica'
            ];
        }
        return self::$tecnicaAnastomosis;
    }

    private static $modalidadAnastomosis;

    /**
     * Singleton de modalidadAnastomosis
     *
     * @return ItemFormulario
     */
    static function modalidadAnastomosis()
    {
        if (! isset(self::$modalidadAnastomosis)) {
            self::$modalidadAnastomosis = new ItemFormulario();
            self::$modalidadAnastomosis->columna = Columna::modalidadAnastomosis();
            self::$modalidadAnastomosis->tipo = TipoItem::radio();
            self::$modalidadAnastomosis->etiqueta = 'Modalidad';
            self::$modalidadAnastomosis->valores = [
                'Termino-terminal',
                'Termino-lateral',
                'Latero-lateral',
                'Latero-terminal',
                'Reservorio en j',
                'Coloplastia'
            ];
        }
        return self::$modalidadAnastomosis;
    }

    private static $rio;

    /**
     * Singleton de rio
     *
     * @return ItemFormulario
     */
    static function rio()
    {
        if (! isset(self::$rio)) {
            self::$rio = new ItemFormulario();
            self::$rio->columna = Columna::rio();
            self::$rio->tipo = TipoItem::radio();
            self::$rio->etiqueta = 'RIO';
            self::$rio->valores = [
                'No',
                'Abortada',
                'Si'
            ];
        }
        return self::$rio;
    }

    private static $hayEstoma;

    /**
     * Singleton de hayEstoma
     *
     * @return ItemFormulario
     */
    static function hayEstoma()
    {
        if (! isset(self::$hayEstoma)) {
            self::$hayEstoma = new ItemFormulario();
            self::$hayEstoma->columna = Columna::estoma();
            self::$hayEstoma->tipo = TipoItem::siNo();
            self::$hayEstoma->etiqueta = 'Estoma';
            self::$hayEstoma->nest = array(
                self::estoma()
            );
        }
        return self::$hayEstoma;
    }

    private static $estoma;

    /**
     * Singleton de estoma
     *
     * @return ItemFormulario
     */
    static function estoma()
    {
        if (! isset(self::$estoma)) {
            self::$estoma = new ItemFormulario();
            self::$estoma->columna = Columna::estoma();
            self::$estoma->tipo = TipoItem::radio();
            self::$estoma->etiqueta = 'Tipo';
            self::$estoma->valores = [
                'Colostomía',
                'Ileostomía',
                'Lateral, sobre soporte o cañón de escopeta',
                'De asas desfuncionalizadas',
                'Fístula Mucosa'
            ];
        }
        return self::$estoma;
    }

    private static $hayIntervencionesAsociadas;

    /**
     * Singleton de hayIntervencionesAsociadas
     *
     * @return ItemFormulario
     */
    static function hayIntervencionesAsociadas()
    {
        if (! isset(self::$hayIntervencionesAsociadas)) {
            self::$hayIntervencionesAsociadas = new ItemFormulario();
            self::$hayIntervencionesAsociadas->tabla = Tabla::IntervencionesAsociadas();
            self::$hayIntervencionesAsociadas->tipo = TipoItem::siNo();
            self::$hayIntervencionesAsociadas->etiqueta = 'Resección de órganos adyacentes afectos';
            self::$hayIntervencionesAsociadas->nest = array(
                self::intervencionesAsociadas()
            );
        }
        return self::$hayIntervencionesAsociadas;
    }

    private static $intervencionesAsociadas;

    /**
     * Singleton de intervencionesAsociadas
     *
     * @return ItemFormulario
     */
    static function intervencionesAsociadas()
    {
        if (! isset(self::$intervencionesAsociadas)) {
            self::$intervencionesAsociadas = new ItemFormulario();
            self::$intervencionesAsociadas->columna = Columna::intervencionesAsociadas();
            self::$intervencionesAsociadas->tipo = TipoItem::checkbox();
            self::$intervencionesAsociadas->etiqueta = 'Intervenciones asociadas';
            self::$intervencionesAsociadas->valores = [
                'Resección de vagina',
                'Resección de útero',
                'Resección de trompas',
                'Resección de ovarios',
                'Resección de próstata',
                'Resección parcial de vejiga',
                'Cistectomía total',
                'Resección de coccis',
                'Resección de sacro (S3-S4)',
                'Resección de intestino delgado',
                'Resección de vesículas seminales',
                'Resección de pared abdominal',
                'Otra resección'
            ];
        }
        return self::$intervencionesAsociadas;
    }

    private static $fechaAlta;

    /**
     * Singleton de fechaAlta
     *
     * @return ItemFormulario
     */
    static function fechaAlta()
    {
        if (! isset(self::$fechaAlta)) {
            self::$fechaAlta = new ItemFormulario();
            self::$fechaAlta->columna = Columna::fechaAlta();
            self::$fechaAlta->tipo = TipoItem::date();
            self::$fechaAlta->etiqueta = 'Fecha de alta';
        }
        return self::$fechaAlta;
    }

    private static $hayComplicacionesIntraoperatorias;

    /**
     * Singleton de hayComplicacionesIntraoperatorias
     *
     * @return ItemFormulario
     */
    static function hayComplicacionesIntraoperatorias()
    {
        if (! isset(self::$hayComplicacionesIntraoperatorias)) {
            self::$hayComplicacionesIntraoperatorias = new ItemFormulario();
            self::$hayComplicacionesIntraoperatorias->tabla = Tabla::ComplicacionesIntraoperatorias();
            self::$hayComplicacionesIntraoperatorias->tipo = TipoItem::radio();
            self::$hayComplicacionesIntraoperatorias->etiqueta = 'Complicaciones intraoperatorias';
            self::$hayComplicacionesIntraoperatorias->nest = array(
                self::complicacionesIntraoperatorias()
            );
        }
        return self::$hayComplicacionesIntraoperatorias;
    }

    private static $complicacionesIntraoperatorias;

    /**
     * Singleton de complicacionesIntraoperatorias
     *
     * @return ItemFormulario
     */
    static function complicacionesIntraoperatorias()
    {
        if (! isset(self::$complicacionesIntraoperatorias)) {
            self::$complicacionesIntraoperatorias = new ItemFormulario();
            self::$complicacionesIntraoperatorias->columna = Columna::complicacionesIntraoperatorias();
            self::$complicacionesIntraoperatorias->tipo = TipoItem::checkbox();
            self::$complicacionesIntraoperatorias->etiqueta = 'Complicación';
            self::$complicacionesIntraoperatorias->valores = [
                'Contaminación intraperitoneal',
                'Lesión intestinal',
                'Lesión uretral',
                'Lesión vesical',
                'Lesión vascular',
                'Lesión vaginal',
                'Lesión nerviosa',
                'Otra lesión',
                'Otras complicaciones'
            ];
        }
        return self::$complicacionesIntraoperatorias;
    }

    private static $hayComplicacionesCirugia;

    /**
     * Singleton de hayComplicacionesCirugia
     *
     * @return ItemFormulario
     */
    static function hayComplicacionesCirugia()
    {
        if (! isset(self::$hayComplicacionesCirugia)) {
            self::$hayComplicacionesCirugia = new ItemFormulario();
            self::$hayComplicacionesCirugia->tabla = Tabla::ComplicacionesCirugia();
            self::$hayComplicacionesCirugia->tipo = TipoItem::siNo();
            self::$hayComplicacionesCirugia->etiqueta = 'Complicaciones relacionadas con la cirugía';
            self::$hayComplicacionesCirugia->nest = array(
                self::complicacionesCirugia()
            );
        }
        return self::$hayComplicacionesCirugia;
    }

    private static $complicacionesCirugia;

    /**
     * Singleton de complicacionesCirugia
     *
     * @return ItemFormulario
     */
    static function complicacionesCirugia()
    {
        if (! isset(self::$complicacionesCirugia)) {
            self::$complicacionesCirugia = new ItemFormulario();
            self::$complicacionesCirugia->columna = Columna::complicacionesCirugia();
            self::$complicacionesCirugia->tipo = TipoItem::checkbox();
            self::$complicacionesCirugia->etiqueta = 'Complicación';
            self::$complicacionesCirugia->valores = [
                'Infección superficial del sitio quirúrgico',
                'Infección profunda del sitio quirúrgico',
                'Infección de la herida perineal',
                'Absceso intraabdominal',
                'Absceso pélvico',
                'Peritonitis purulenta',
                'Peritonitis fecaloidea',
                'Sépsis',
                'Hemoperitóneo',
                'Hemorragia digestiva',
                'Ileo paralítico prolongado',
                'Obstrucción intestinal',
                'Isquemia intestinal',
                'Evisceración',
                'Necrosis estoma',
                'Prolapso estoma',
                'Estenosis estoma',
                'Dehiscencia anastomótica clínica',
                'Dehiscencia anastomótica radiológica sin clínica'
            ];
        }
        return self::$complicacionesCirugia;
    }

    private static $hayComplicacionesMedicas;

    /**
     * Singleton de hayComplicacionesMedicas
     *
     * @return ItemFormulario
     */
    static function hayComplicacionesMedicas()
    {
        if (! isset(self::$hayComplicacionesMedicas)) {
            self::$hayComplicacionesMedicas = new ItemFormulario();
            self::$hayComplicacionesMedicas->tabla = Tabla::ComplicacionesMedicas();
            self::$hayComplicacionesMedicas->tipo = TipoItem::siNo();
            self::$hayComplicacionesMedicas->etiqueta = 'Complicaciones médicas';
            self::$hayComplicacionesMedicas->nest = array(
                self::complicacionesMedicas()
            );
        }
        return self::$hayComplicacionesMedicas;
    }

    private static $complicacionesMedicas;

    /**
     * Singleton de complicacionesMedicas
     *
     * @return ItemFormulario
     */
    static function complicacionesMedicas()
    {
        if (! isset(self::$complicacionesMedicas)) {
            self::$complicacionesMedicas = new ItemFormulario();
            self::$complicacionesMedicas->columna = Columna::complicacionesMedicas();
            self::$complicacionesMedicas->tipo = TipoItem::checkbox();
            self::$complicacionesMedicas->etiqueta = 'Complicación';
            self::$complicacionesMedicas->valores = [
                'Evento cardíaco',
                'Evento respiratorio',
                'Evento neurológico',
                'Evento nefrológico',
                'Evento hematológico',
                'Evento endocrino-metabólico',
                'TVP',
                'Flebitis',
                'Infección de vía central',
                'Infección urinaria',
                'Precisó sondaje urinario',
                'RAO',
                'Fiebre de origen desconocido'
            ];
        }
        return self::$complicacionesMedicas;
    }

    private static $hayTransfusiones;

    /**
     * Singleton de hayTransfusiones
     *
     * @return ItemFormulario
     */
    static function hayTransfusiones()
    {
        if (! isset(self::$hayTransfusiones)) {
            self::$hayTransfusiones = new ItemFormulario();
            self::$hayTransfusiones->columna = Columna::Transfusiones();
            self::$hayTransfusiones->tipo = TipoItem::siNo();
            self::$hayTransfusiones->etiqueta = 'hayTransfusiones';
            self::$hayTransfusiones->nest = array(
                self::transfusiones()
            );
        }
        return self::$hayTransfusiones;
    }

    private static $transfusiones;

    /**
     * Singleton de transfusiones
     *
     * @return ItemFormulario
     */
    static function transfusiones()
    {
        if (! isset(self::$transfusiones)) {
            self::$transfusiones = new ItemFormulario();
            self::$transfusiones->columna = Columna::transfusiones();
            self::$transfusiones->tipo = TipoItem::checkbox();
            self::$transfusiones->etiqueta = 'Momento';
            self::$transfusiones->valores = [
                'En preoperatorio',
                'Intraoperatoria',
                'En postoperatorio'
            ];
        }
        return self::$transfusiones;
    }

    private static $hayNeoadyuvancia;

    /**
     * Singleton de hayNeoadyuvancia
     *
     * @return ItemFormulario
     */
    static function hayNeoadyuvancia()
    {
        if (! isset(self::$hayNeoadyuvancia)) {
            self::$hayNeoadyuvancia = new ItemFormulario();
            self::$hayNeoadyuvancia->columna = Columna::neoadyuvancia();
            self::$hayNeoadyuvancia->tipo = TipoItem::siNo();
            self::$hayNeoadyuvancia->etiqueta = 'Neoadyuvancia';
            self::$hayNeoadyuvancia->nest = array(
                self::neoadyuvancia()
            );
        }
        return self::$hayNeoadyuvancia;
    }

    private static $neoadyuvancia;

    /**
     * Singleton de neoadyuvancia
     *
     * @return ItemFormulario
     */
    static function neoadyuvancia()
    {
        if (! isset(self::$neoadyuvancia)) {
            self::$neoadyuvancia = new ItemFormulario();
            self::$neoadyuvancia->columna = Columna::neoadyuvancia();
            self::$neoadyuvancia->tipo = TipoItem::radio();
            self::$neoadyuvancia->etiqueta = 'Tipo';
            self::$neoadyuvancia->valores = [
                'RT ciclo largo + QT',
                'RT ciclo corto + QT',
                'RT ciclo corto',
                'RT',
                'QT'
            ];
        }
        return self::$neoadyuvancia;
    }

    private static $reingreso30Dias;

    /**
     * Singleton de reingreso30Dias
     *
     * @return ItemFormulario
     */
    static function reingreso30Dias()
    {
        if (! isset(self::$reingreso30Dias)) {
            self::$reingreso30Dias = new ItemFormulario();
            self::$reingreso30Dias->columna = Columna::reingreso30Dias();
            self::$reingreso30Dias->tipo = TipoItem::boolean();
            self::$reingreso30Dias->etiqueta = 'Reingreso antes de los 30 días';
        }
        return self::$reingreso30Dias;
    }

    // ***************************************************************************************************************************************
    
    // Tercer grupo de inputs: Estudio Anatomopatológico
    private static $histologia;

    /**
     * Singleton de histologia
     *
     * @return ItemFormulario
     */
    static function histologia()
    {
        if (! isset(self::$histologia)) {
            self::$histologia = new ItemFormulario();
            self::$histologia->columna = Columna::histologia();
            self::$histologia->tipo = TipoItem::radio();
            self::$histologia->etiqueta = 'Histología';
            self::$histologia->valores = [
                'Adenocarcinoma',
                'ADC mucinoso (inf 50%)',
                'GIST',
                'Neuroendocrino',
                'Linfoma',
                'Adenocarcinoide',
                'Carcinoide',
                'Células en anillo de sello',
                'Carcinoma medular',
                'Otro'
            ];
        }
        return self::$histologia;
    }

    private static $TNM;

    /**
     * Singleton de la agrupación TNM
     *
     * @return ItemFormulario
     */
    static function TNM()
    {
        if (! isset(self::$TNM)) {
            $etiqueta = 'TNM';
            $nest = array(
                self::T(),
                self::N(),
                self::M()
            );
            self::$TNM = self::makeGroup($etiqueta, $nest);
        }
        return self::$TNM;
    }

    private static $T;

    /**
     * Singleton de T
     *
     * @return ItemFormulario
     */
    static function T()
    {
        if (! isset(self::$T)) {
            self::$T = new ItemFormulario();
            self::$T->columna = Columna::T();
            self::$T->tipo = TipoItem::radio();
            self::$T->etiqueta = 'Tumor principal (T)';
            self::$T->valores = [
                'TX',
                'T0',
                'Tis',
                'T1',
                'T2',
                'T3',
                'T4a',
                'T4b'
            ];
        }
        return self::$T;
    }

    private static $N;

    /**
     * Singleton de N
     *
     * @return ItemFormulario
     */
    static function N()
    {
        if (! isset(self::$N)) {
            self::$N = new ItemFormulario();
            self::$N->columna = Columna::N();
            self::$N->tipo = TipoItem::radio();
            self::$N->etiqueta = 'Nodos linfáticos regionales (N)';
            self::$N->valores = [
                'NX',
                'N0',
                'N1',
                'N1a',
                'N1b',
                'N1c',
                'N2',
                'N2a',
                'N2b'
            ];
        }
        return self::$N;
    }

    private static $M;

    /**
     * Singleton de M
     *
     * @return ItemFormulario
     */
    static function M()
    {
        if (! isset(self::$M)) {
            self::$M = new ItemFormulario();
            self::$M->columna = Columna::M();
            self::$M->tipo = TipoItem::radio();
            self::$M->etiqueta = 'Metástasis distante (M)';
            self::$M->valores = [
                'M0',
                'M1',
                'M1a',
                'M1b'
            ];
        }
        return self::$M;
    }

    private static $distanciaMargenDistal;

    /**
     * Singleton de distanciaMargenDistal
     *
     * @return ItemFormulario
     */
    static function distanciaMargenDistal()
    {
        if (! isset(self::$distanciaMargenDistal)) {
            self::$distanciaMargenDistal = new ItemFormulario();
            self::$distanciaMargenDistal->columna = Columna::distanciaMargenDistal();
            self::$distanciaMargenDistal->tipo = TipoItem::number();
            self::$distanciaMargenDistal->etiqueta = 'Distancia al margen distal (mm)';
            self::$distanciaMargenDistal->opciones = 'min="0"';
        }
        return self::$distanciaMargenDistal;
    }

    private static $gradoDiferenciacion;

    /**
     * Singleton de gradoDiferenciacion
     *
     * @return ItemFormulario
     */
    static function gradoDiferenciacion()
    {
        if (! isset(self::$gradoDiferenciacion)) {
            self::$gradoDiferenciacion = new ItemFormulario();
            self::$gradoDiferenciacion->columna = Columna::gradoDiferenciacion();
            self::$gradoDiferenciacion->tipo = TipoItem::radio();
            self::$gradoDiferenciacion->etiqueta = 'Grado de diferenciación';
            self::$gradoDiferenciacion->valores = [
                'Bien diferenciado',
                'Moderadamente diferenciado',
                'Mal diferenciado o indiferenciado'
            ];
        }
        return self::$gradoDiferenciacion;
    }

    private static $factoresRiesgo;

    /**
     * Singleton de FactoresRiesgo_tipo
     *
     * @return ItemFormulario
     */
    static function factoresRiesgo()
    {
        if (! isset(self::$factoresRiesgo)) {
            self::$factoresRiesgo = new ItemFormulario();
            self::$factoresRiesgo->nombre = Columna::factoresRiesgo();
            self::$factoresRiesgo->tipo = TipoItem::checkbox();
            self::$factoresRiesgo->etiqueta = 'Factores de riesgo histológico';
            self::$factoresRiesgo->valores = [
                'Infiltración perivascular',
                'Infiltración perineural',
                'Infiltración perilinfática',
                'Crohn like',
                'Frente de invasión'
            ];
        }
        return self::$factoresRiesgo;
    }

    private static $hayCancerRecto;

    /**
     * Singleton de hayCancerRecto
     *
     * @return ItemFormulario
     */
    static function hayCancerRecto()
    {
        if (! isset(self::$hayCancerRecto)) {
            self::$hayCancerRecto = new ItemFormulario();
            self::$hayCancerRecto->tabla = Tabla::CanceresRecto();
            self::$hayCancerRecto->tipo = TipoItem::siNo();
            self::$hayCancerRecto->etiqueta = 'Cancer de recto';
            self::$hayCancerRecto->nest = array(
                self::estadificaciones_CancerRecto(),
                self::mandardCancerRecto(),
                self::calidadMesorrectoCancerRecto()
            );
        }
        return self::$hayCancerRecto;
    }

    private static $estadificaciones_CancerRecto;

    /**
     * Singleton de estadificaciones_CancerRecto
     *
     * @return ItemFormulario
     */
    static function estadificaciones_CancerRecto()
    {
        if (! isset(self::$estadificaciones_CancerRecto)) {
            self::$estadificaciones_CancerRecto = new ItemFormulario();
            self::$estadificaciones_CancerRecto->columna = Columna::estadificaciones_CancerRecto();
            self::$estadificaciones_CancerRecto->tipo = TipoItem::checkbox();
            self::$estadificaciones_CancerRecto->etiqueta = 'Estadificación local por';
            self::$estadificaciones_CancerRecto->valores = [
                'Eco Endoanal',
                'TAC',
                'RMN pélvica',
                'PET'
            ];
        }
        return self::$estadificaciones_CancerRecto;
    }

    private static $mandardCancerRecto;

    /**
     * Singleton de mandardCancerRecto
     *
     * @return ItemFormulario
     */
    static function mandardCancerRecto()
    {
        if (! isset(self::$mandardCancerRecto)) {
            self::$mandardCancerRecto = new ItemFormulario();
            self::$mandardCancerRecto->columna = Columna::mandardCancerRecto();
            self::$mandardCancerRecto->tipo = TipoItem::radio();
            self::$mandardCancerRecto->etiqueta = 'Mandard';
            self::$mandardCancerRecto->valores = [
                'Respuesta patológica completa',
                'Células tumorales aislados',
                'Predominio de fibrosis',
                'Predominio de nidos tumorales',
                'Ausencia de regresión'
            ];
        }
        return self::$mandardCancerRecto;
    }

    private static $calidadMesorrectoCancerRecto;

    /**
     * Singleton de calidadMesorrectoCancerRecto
     *
     * @return ItemFormulario
     */
    static function calidadMesorrectoCancerRecto()
    {
        if (! isset(self::$calidadMesorrectoCancerRecto)) {
            self::$calidadMesorrectoCancerRecto = new ItemFormulario();
            self::$calidadMesorrectoCancerRecto->columna = Columna::calidadMesorrectoCancerRecto();
            self::$calidadMesorrectoCancerRecto->tipo = TipoItem::radio();
            self::$calidadMesorrectoCancerRecto->etiqueta = 'Calidad del mesorrecto';
            self::$calidadMesorrectoCancerRecto->valores = [
                'Óptima',
                'Subóptima',
                'Insatisfactoria / mala'
            ];
        }
        return self::$calidadMesorrectoCancerRecto;
    }
}