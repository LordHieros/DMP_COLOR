<?php

include('TipoColumna.php');
include('Tabla.php');

final class Columna
{

    private $nombre;

    private $tipo;

    private $columna;

    private $tabla;

    /**
     * Devuelve el "alias" de la columna
     *
     * @return string
     */
    function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Devuelve el tipo de la columna
     *
     * @return TipoItem
     */
    function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Devuelve el nombre de la columna según está en la base de datos
     *
     * @return string
     */
    function getColumna()
    {
        return $this->columna;
    }

    /**
     * Devuelve la tabla a la que pertenece la columna.
     * !!!No modificar el bucle de singleton de Tabla o habrá bucle infinito.
     *
     * @return Tabla
     */
    function getTabla()
    {
        return $this->tabla;
    }

    // Impide que la clase se instancie desde fuera
    private function __construct()
    {}

    // Propios de Hospital
    private static $idHospital;

    /**
     * Singleton de idHospital
     *
     * @return Columna
     */
    static function idHospital()
    {
        if (! isset(self::$idHospital)) {
            self::$idHospital = new Columna();
            self::$idHospital->nombre = CampoSession::ID_HOSPITAL;
            self::$idHospital->tipo = TipoColumna::string();
            self::$idHospital->columna = 'idHospital';
            self::$idHospital->tabla = Tabla::Hospitales();
        }
        return self::$idHospital;
    }

    private static $numeroCamas;

    /**
     * Singleton de numeroCamas
     *
     * @return Columna
     */
    static function numeroCamas()
    {
        if (! isset(self::$numeroCamas)) {
            self::$numeroCamas = new Columna();
            self::$numeroCamas->nombre = 'numeroCamas';
            self::$numeroCamas->tipo = TipoColumna::int();
            self::$numeroCamas->columna = 'numeroCamas';
            self::$numeroCamas->tabla = Tabla::Hospitales();
        }
        return self::$numeroCamas;
    }

    // Propios de Usuario
    private static $nombreUsuario;

    /**
     * Singleton de nombreUsuario
     *
     * @return Columna
     */
    static function nombreUsuario()
    {
        if (! isset(self::$nombreUsuario)) {
            self::$nombreUsuario = new Columna();
            self::$nombreUsuario->nombre = 'nombreUsuario';
            self::$nombreUsuario->tipo = TipoColumna::string();
            self::$nombreUsuario->columna = 'nombreUsuario';
            self::$nombreUsuario->tabla = Tabla::Usuarios();
        }
        return self::$nombreUsuario;
    }

    private static $contrasenha;

    /**
     * Singleton de contrasenha
     *
     * @return Columna
     */
    static function contrasenha()
    {
        if (! isset(self::$contrasenha)) {
            self::$contrasenha = new Columna();
            self::$contrasenha->nombre = 'contrasenha';
            self::$contrasenha->tipo = TipoColumna::string();
            self::$contrasenha->columna = 'contrasenha';
            self::$contrasenha->tabla = Tabla::Usuarios();
        }
        return self::$contrasenha;
    }

    private static $administrador;

    /**
     * Singleton de administrador
     *
     * @return Columna
     */
    static function administrador()
    {
        if (! isset(self::$administrador)) {
            self::$administrador = new Columna();
            self::$administrador->nombre = 'administrador';
            self::$administrador->tipo = TipoColumna::bool();
            self::$administrador->columna = 'administrador';
            self::$administrador->tabla = Tabla::Usuarios();
        }
        return self::$administrador;
    }

    // Propios de Filiacion
    private static $nasi;

    /**
     * Singleton de nasi
     *
     * @return Columna
     */
    static function nasi()
    {
        if (! isset(self::$nasi)) {
            self::$nasi = new Columna();
            self::$nasi->nombre = CampoSession::NASI;
            self::$nasi->tipo = TipoColumna::int();
            self::$nasi->columna = 'NASI';
            self::$nasi->tabla = Tabla::Filiaciones();
        }
        return self::$nasi;
    }

    private static $nhc;

    /**
     * Singleton de nhc
     *
     * @return Columna
     */
    static function nhc()
    {
        if (! isset(self::$nhc)) {
            self::$nhc = new Columna();
            self::$nhc->nombre = 'nhc';
            self::$nhc->tipo = TipoColumna::string();
            self::$nhc->columna = 'NHC';
            self::$nhc->tabla = Tabla::Filiaciones();
        }
        return self::$nhc;
    }

    // Propios de Diagnostico
    private static $fechaDiagnostico;

    /**
     * Singleton de fechaDiagnostico
     *
     * @return Columna
     */
    static function fechaDiagnostico()
    {
        if (! isset(self::$fechaDiagnostico)) {
            self::$fechaDiagnostico = new Columna();
            self::$fechaDiagnostico->nombre = CampoSession::FECHA_DIAGNOSTICO;
            self::$fechaDiagnostico->tipo = TipoColumna::date();
            self::$fechaDiagnostico->columna = 'fechaDiagnostico';
            self::$fechaDiagnostico->tabla = Tabla::Diagnosticos();
        }
        return self::$fechaDiagnostico;
    }

    private static $sexo;

    /**
     * Singleton de sexo
     *
     * @return Columna
     */
    static function sexo()
    {
        if (! isset(self::$sexo)) {
            self::$sexo = new Columna();
            self::$sexo->nombre = 'sexo';
            self::$sexo->tipo = TipoColumna::string();
            self::$sexo->columna = 'sexo';
            self::$sexo->tabla = Tabla::Diagnosticos();
        }
        return self::$sexo;
    }

    private static $edad;

    /**
     * Singleton de edad
     *
     * @return Columna
     */
    static function edad()
    {
        if (! isset(self::$edad)) {
            self::$edad = new Columna();
            self::$edad->nombre = 'edad';
            self::$edad->tipo = TipoColumna::int();
            self::$edad->columna = 'edad';
            self::$edad->tabla = Tabla::Diagnosticos();
        }
        return self::$edad;
    }

    private static $talla;

    /**
     * Singleton de talla
     *
     * @return Columna
     */
    static function talla()
    {
        if (! isset(self::$talla)) {
            self::$talla = new Columna();
            self::$talla->nombre = 'talla';
            self::$talla->tipo = TipoColumna::int();
            self::$talla->columna = 'talla';
            self::$talla->tabla = Tabla::Diagnosticos();
        }
        return self::$talla;
    }

    private static $peso;

    /**
     * Singleton de peso
     *
     * @return Columna
     */
    static function peso()
    {
        if (! isset(self::$peso)) {
            self::$peso = new Columna();
            self::$peso->nombre = 'peso';
            self::$peso->tipo = TipoColumna::int();
            self::$peso->columna = 'peso';
            self::$peso->tabla = Tabla::Diagnosticos();
        }
        return self::$peso;
    }

    private static $fechaIngreso;

    /**
     * Singleton de fechaIngreso
     *
     * @return Columna
     */
    static function fechaIngreso()
    {
        if (! isset(self::$fechaIngreso)) {
            self::$fechaIngreso = new Columna();
            self::$fechaIngreso->nombre = 'fechaIngreso';
            self::$fechaIngreso->tipo = TipoColumna::date();
            self::$fechaIngreso->columna = 'fechaIngreso';
            self::$fechaIngreso->tabla = Tabla::Diagnosticos();
        }
        return self::$fechaIngreso;
    }

    private static $fechaEndoscopia;

    /**
     * Singleton de fechaEndoscopia
     *
     * @return Columna
     */
    static function fechaEndoscopia()
    {
        if (! isset(self::$fechaEndoscopia)) {
            self::$fechaEndoscopia = new Columna();
            self::$fechaEndoscopia->nombre = 'fechaEndoscopia';
            self::$fechaEndoscopia->tipo = TipoColumna::date();
            self::$fechaEndoscopia->columna = 'fechaEndoscopia';
            self::$fechaEndoscopia->tabla = Tabla::Diagnosticos();
        }
        return self::$fechaEndoscopia;
    }

    private static $fechaBiopsia;

    /**
     * Singleton de fechaBiopsia
     *
     * @return Columna
     */
    static function fechaBiopsia()
    {
        if (! isset(self::$fechaBiopsia)) {
            self::$fechaBiopsia = new Columna();
            self::$fechaBiopsia->nombre = 'fechaBiopsia';
            self::$fechaBiopsia->tipo = TipoColumna::date();
            self::$fechaBiopsia->columna = 'fechaBiopsia';
            self::$fechaBiopsia->tabla = Tabla::Diagnosticos();
        }
        return self::$fechaBiopsia;
    }

    private static $fechaPresentacion;

    /**
     * Singleton de fechaPresentacion
     *
     * @return Columna
     */
    static function fechaPresentacion()
    {
        if (! isset(self::$fechaPresentacion)) {
            self::$fechaPresentacion = new Columna();
            self::$fechaPresentacion->nombre = 'fechaPresentacion';
            self::$fechaPresentacion->tipo = TipoColumna::date();
            self::$fechaPresentacion->columna = 'fechaPresentacion';
            self::$fechaPresentacion->tabla = Tabla::Diagnosticos();
        }
        return self::$fechaPresentacion;
    }

    private static $cea;

    /**
     * Singleton de cea
     *
     * @return Columna
     */
    static function cea()
    {
        if (! isset(self::$cea)) {
            self::$cea = new Columna();
            self::$cea->nombre = 'cea';
            self::$cea->tipo = TipoColumna::int();
            self::$cea->columna = 'CEA';
            self::$cea->tabla = Tabla::Diagnosticos();
        }
        return self::$cea;
    }

    private static $hemoglobina;

    /**
     * Singleton de hemoglobina
     *
     * @return Columna
     */
    static function hemoglobina()
    {
        if (! isset(self::$hemoglobina)) {
            self::$hemoglobina = new Columna();
            self::$hemoglobina->nombre = 'hemoglobina';
            self::$hemoglobina->tipo = TipoColumna::float();
            self::$hemoglobina->columna = 'hemoglobina';
            self::$hemoglobina->tabla = Tabla::Diagnosticos();
        }
        return self::$hemoglobina;
    }

    private static $albumina;

    /**
     * Singleton de albumina
     *
     * @return Columna
     */
    static function albumina()
    {
        if (! isset(self::$albumina)) {
            self::$albumina = new Columna();
            self::$albumina->nombre = 'albumina';
            self::$albumina->tipo = TipoColumna::float();
            self::$albumina->columna = 'albumina';
            self::$albumina->tabla = Tabla::Diagnosticos();
        }
        return self::$albumina;
    }

    private static $asa;

    /**
     * Singleton de asa
     *
     * @return Columna
     */
    static function asa()
    {
        if (! isset(self::$asa)) {
            self::$asa = new Columna();
            self::$asa->nombre = 'asa';
            self::$asa->tipo = TipoColumna::string();
            self::$asa->columna = 'ASA';
            self::$asa->tabla = Tabla::Diagnosticos();
        }
        return self::$asa;
    }

    private static $anticoagulantes;

    /**
     * Singleton de anticoagulantes
     *
     * @return Columna
     */
    static function anticoagulantes()
    {
        if (! isset(self::$anticoagulantes)) {
            self::$anticoagulantes = new Columna();
            self::$anticoagulantes->nombre = 'anticoagulantes';
            self::$anticoagulantes->tipo = TipoColumna::bool();
            self::$anticoagulantes->columna = 'anticoagulantes';
            self::$anticoagulantes->tabla = Tabla::Diagnosticos();
        }
        return self::$anticoagulantes;
    }

    private static $diagnosticoCerrado;

    /**
     * Singleton de diagnosticoCerrado
     *
     * @return Columna
     */
    static function diagnosticoCerrado()
    {
        if (! isset(self::$diagnosticoCerrado)) {
            self::$diagnosticoCerrado = new Columna();
            self::$diagnosticoCerrado->nombre = 'diagnosticoCerrado';
            self::$diagnosticoCerrado->tipo = TipoColumna::bool();
            self::$diagnosticoCerrado->columna = 'cerrado';
            self::$diagnosticoCerrado->tabla = Tabla::Diagnosticos();
        }
        return self::$diagnosticoCerrado;
    }

    // Dependientes de Diagnostico, 1:1-0
    private static $tipoCirugiaHepatica;

    /**
     * Singleton de tipoCirugiaHepatica
     *
     * @return Columna
     */
    static function tipoCirugiaHepatica()
    {
        if (! isset(self::$tipoCirugiaHepatica)) {
            self::$tipoCirugiaHepatica = new Columna();
            self::$tipoCirugiaHepatica->nombre = 'tipoCirugiaHepatica';
            self::$tipoCirugiaHepatica->tipo = TipoColumna::string();
            self::$tipoCirugiaHepatica->columna = 'tipo';
            self::$tipoCirugiaHepatica->tabla = Tabla::CirugiasHepaticas();
        }
        return self::$tipoCirugiaHepatica;
    }

    private static $tecnicaCirugiaHepatica;

    /**
     * Singleton de tecnicaCirugiaHepatica
     *
     * @return Columna
     */
    static function tecnicaCirugiaHepatica()
    {
        if (! isset(self::$tecnicaCirugiaHepatica)) {
            self::$tecnicaCirugiaHepatica = new Columna();
            self::$tecnicaCirugiaHepatica->nombre = 'tecnicaCirugiaHepatica';
            self::$tecnicaCirugiaHepatica->tipo = TipoColumna::string();
            self::$tecnicaCirugiaHepatica->columna = 'tecnica';
            self::$tecnicaCirugiaHepatica->tabla = Tabla::CirugiasHepaticas();
        }
        return self::$tecnicaCirugiaHepatica;
    }

    private static $fechaCirugiaHepatica;

    /**
     * Singleton de fechaCirugiaHepatica
     *
     * @return Columna
     */
    static function fechaCirugiaHepatica()
    {
        if (! isset(self::$fechaCirugiaHepatica)) {
            self::$fechaCirugiaHepatica = new Columna();
            self::$fechaCirugiaHepatica->nombre = 'fechaCirugiaHepatica';
            self::$fechaCirugiaHepatica->tipo = TipoColumna::date();
            self::$fechaCirugiaHepatica->columna = 'fecha';
            self::$fechaCirugiaHepatica->tabla = Tabla::CirugiasHepaticas();
        }
        return self::$fechaCirugiaHepatica;
    }

    private static $fechaCirugiaPulmonar;

    /**
     * Singleton de fechaCirugiaPulmonar
     *
     * @return Columna
     */
    static function fechaCirugiaPulmonar()
    {
        if (! isset(self::$fechaCirugiaPulmonar)) {
            self::$fechaCirugiaPulmonar = new Columna();
            self::$fechaCirugiaPulmonar->nombre = 'fechaCirugiaPulmonar';
            self::$fechaCirugiaPulmonar->tipo = TipoColumna::date();
            self::$fechaCirugiaPulmonar->columna = 'fecha';
            self::$fechaCirugiaPulmonar->tabla = Tabla::CirugiasPulmonares();
        }
        return self::$fechaCirugiaPulmonar;
    }

    private static $fechaProtesis;

    /**
     * Singleton de fechaProtesis
     *
     * @return Columna
     */
    static function fechaProtesis()
    {
        if (! isset(self::$fechaProtesis)) {
            self::$fechaProtesis = new Columna();
            self::$fechaProtesis->nombre = 'fechaProtesis';
            self::$fechaProtesis->tipo = TipoColumna::date();
            self::$fechaProtesis->columna = 'fecha';
            self::$fechaProtesis->tabla = Tabla::Protesis();
        }
        return self::$fechaProtesis;
    }

    private static $complicacionProtesis;

    /**
     * Singleton de complicacionProtesis
     *
     * @return Columna
     */
    static function complicacionProtesis()
    {
        if (! isset(self::$complicacionProtesis)) {
            self::$complicacionProtesis = new Columna();
            self::$complicacionProtesis->nombre = 'complicacionProtesis';
            self::$complicacionProtesis->tipo = TipoColumna::bool();
            self::$complicacionProtesis->columna = 'complicacion';
            self::$complicacionProtesis->tabla = Tabla::Protesis();
        }
        return self::$complicacionProtesis;
    }

    // Dependientes de Diagnostico, 1:*
    private static $comorbilidades;

    /**
     * Singleton de comorbilidades
     *
     * @return Columna
     */
    static function comorbilidades()
    {
        if (! isset(self::$comorbilidades)) {
            self::$comorbilidades = new Columna();
            self::$comorbilidades->nombre = 'comorbilidades';
            self::$comorbilidades->tipo = TipoColumna::multString();
            self::$comorbilidades->columna = 'comorbilidad';
            self::$comorbilidades->tabla = Tabla::Comorbilidades();
        }
        return self::$comorbilidades;
    }

    private static $metastasis;

    /**
     * Singleton de metastasis
     *
     * @return Columna
     */
    static function metastasis()
    {
        if (! isset(self::$metastasis)) {
            self::$metastasis = new Columna();
            self::$metastasis->nombre = 'metastasis';
            self::$metastasis->tipo = TipoColumna::multString();
            self::$metastasis->columna = 'localizacion';
            self::$metastasis->tabla = Tabla::Metastasis();
        }
        return self::$metastasis;
    }

    private static $causasIntervencion;

    /**
     * Singleton de causasIntervencion
     *
     * @return Columna
     */
    static function causasIntervencion()
    {
        if (! isset(self::$causasIntervencion)) {
            self::$causasIntervencion = new Columna();
            self::$causasIntervencion->nombre = 'causasIntervencion';
            self::$causasIntervencion->tipo = TipoColumna::multString();
            self::$causasIntervencion->columna = 'causa';
            self::$causasIntervencion->tabla = Tabla::CausasIntervencion();
        }
        return self::$causasIntervencion;
    }

    private static $localizacionesCancer;

    /**
     * Singleton de localizacionesCancer
     *
     * @return Columna
     */
    static function localizacionesCancer()
    {
        if (! isset(self::$localizacionesCancer)) {
            self::$localizacionesCancer = new Columna();
            self::$localizacionesCancer->nombre = 'localizacionesCancer';
            self::$localizacionesCancer->tipo = TipoColumna::multString();
            self::$localizacionesCancer->columna = 'localizacion';
            self::$localizacionesCancer->tabla = Tabla::LocalizacionesCancer();
        }
        return self::$localizacionesCancer;
    }

    private static $tumoresSincronicos;

    /**
     * Singleton de tumoresSincronicos
     *
     * @return Columna
     */
    static function tumoresSincronicos()
    {
        if (! isset(self::$tumoresSincronicos)) {
            self::$tumoresSincronicos = new Columna();
            self::$tumoresSincronicos->nombre = 'tumoresSincronicos';
            self::$tumoresSincronicos->tipo = TipoColumna::multString();
            self::$tumoresSincronicos->columna = 'localizacion';
            self::$tumoresSincronicos->tabla = Tabla::TumoresSincronicos();
        }
        return self::$tumoresSincronicos;
    }

    private static $intoxicaciones;

    /**
     * Singleton de intoxicaciones
     *
     * @return Columna
     */
    static function intoxicaciones()
    {
        if (! isset(self::$intoxicaciones)) {
            self::$intoxicaciones = new Columna();
            self::$intoxicaciones->nombre = 'intoxicaciones';
            self::$intoxicaciones->tipo = TipoColumna::multString();
            self::$intoxicaciones->columna = 'intoxicacion';
            self::$intoxicaciones->tabla = Tabla::Intoxicaciones();
        }
        return self::$intoxicaciones;
    }

    // Propios de Intervencion
    private static $fechaIntervencion;

    /**
     * Singleton de fechaIntervencion
     *
     * @return Columna
     */
    static function fechaIntervencion()
    {
        if (! isset(self::$fechaIntervencion)) {
            self::$fechaIntervencion = new Columna();
            self::$fechaIntervencion->nombre = CampoSession::FECHA_INTERVENCION;
            self::$fechaIntervencion->tipo = TipoColumna::date();
            self::$fechaIntervencion->columna = 'fechaIntervencion';
            self::$fechaIntervencion->tabla = Tabla::Intervenciones();
        }
        return self::$fechaIntervencion;
    }

    private static $duracion;

    /**
     * Singleton de duracion
     *
     * @return Columna
     */
    static function duracion()
    {
        if (! isset(self::$duracion)) {
            self::$duracion = new Columna();
            self::$duracion->nombre = 'duracion';
            self::$duracion->tipo = TipoColumna::int();
            self::$duracion->columna = 'duracion';
            self::$duracion->tabla = Tabla::Intervenciones();
        }
        return self::$duracion;
    }

    private static $fechaListaEspera;

    /**
     * Singleton de fechaListaEspera
     *
     * @return Columna
     */
    static function fechaListaEspera()
    {
        if (! isset(self::$fechaListaEspera)) {
            self::$fechaListaEspera = new Columna();
            self::$fechaListaEspera->nombre = 'fechaListaEspera';
            self::$fechaListaEspera->tipo = TipoColumna::date();
            self::$fechaListaEspera->columna = 'fechaListaEspera';
            self::$fechaListaEspera->tabla = Tabla::Intervenciones();
        }
        return self::$fechaListaEspera;
    }

    private static $fechaAlta;

    /**
     * Singleton de fechaAlta
     *
     * @return Columna
     */
    static function fechaAlta()
    {
        if (! isset(self::$fechaAlta)) {
            self::$fechaAlta = new Columna();
            self::$fechaAlta->nombre = 'fechaAlta';
            self::$fechaAlta->tipo = TipoColumna::date();
            self::$fechaAlta->columna = 'fechaAlta';
            self::$fechaAlta->tabla = Tabla::Intervenciones();
        }
        return self::$fechaAlta;
    }

    private static $neoadyuvancia;

    /**
     * Singleton de neoadyuvancia
     *
     * @return Columna
     */
    static function neoadyuvancia()
    {
        if (! isset(self::$neoadyuvancia)) {
            self::$neoadyuvancia = new Columna();
            self::$neoadyuvancia->nombre = 'neoadyuvancia';
            self::$neoadyuvancia->tipo = TipoColumna::string();
            self::$neoadyuvancia->columna = 'neoadyuvancia';
            self::$neoadyuvancia->tabla = Tabla::Intervenciones();
        }
        return self::$neoadyuvancia;
    }

    private static $codigoCirujano;

    /**
     * Singleton de codigoCirujano
     *
     * @return Columna
     */
    static function codigoCirujano()
    {
        if (! isset(self::$codigoCirujano)) {
            self::$codigoCirujano = new Columna();
            self::$codigoCirujano->nombre = 'codigoCirujano';
            self::$codigoCirujano->tipo = TipoColumna::int();
            self::$codigoCirujano->columna = 'codigoCirujano';
            self::$codigoCirujano->tabla = Tabla::Intervenciones();
        }
        return self::$codigoCirujano;
    }

    private static $tipoReseccion;

    /**
     * Singleton de tipoReseccion
     *
     * @return Columna
     */
    static function tipoReseccion()
    {
        if (! isset(self::$tipoReseccion)) {
            self::$tipoReseccion = new Columna();
            self::$tipoReseccion->nombre = 'tipoReseccion';
            self::$tipoReseccion->tipo = TipoColumna::string();
            self::$tipoReseccion->columna = 'tipoReseccion';
            self::$tipoReseccion->tabla = Tabla::Intervenciones();
        }
        return self::$tipoReseccion;
    }

    private static $margenAfecto;

    /**
     * Singleton de margenAfecto
     *
     * @return Columna
     */
    static function margenAfecto()
    {
        if (! isset(self::$margenAfecto)) {
            self::$margenAfecto = new Columna();
            self::$margenAfecto->nombre = 'margenAfecto';
            self::$margenAfecto->tipo = TipoColumna::string();
            self::$margenAfecto->columna = 'margenAfecto';
            self::$margenAfecto->tabla = Tabla::Intervenciones();
        }
        return self::$margenAfecto;
    }

    private static $excision;

    /**
     * Singleton de excision
     *
     * @return Columna
     */
    static function excision()
    {
        if (! isset(self::$excision)) {
            self::$excision = new Columna();
            self::$excision->nombre = 'excision';
            self::$excision->tipo = TipoColumna::string();
            self::$excision->columna = 'excision';
            self::$excision->tabla = Tabla::Intervenciones();
        }
        return self::$excision;
    }

    private static $rio;

    /**
     * Singleton de rio
     *
     * @return Columna
     */
    static function rio()
    {
        if (! isset(self::$rio)) {
            self::$rio = new Columna();
            self::$rio->nombre = 'rio';
            self::$rio->tipo = TipoColumna::string();
            self::$rio->columna = 'rio';
            self::$rio->tabla = Tabla::Intervenciones();
        }
        return self::$rio;
    }

    private static $estoma;

    /**
     * Singleton de estoma
     *
     * @return Columna
     */
    static function estoma()
    {
        if (! isset(self::$estoma)) {
            self::$estoma = new Columna();
            self::$estoma->nombre = 'estoma';
            self::$estoma->tipo = TipoColumna::string();
            self::$estoma->columna = 'estoma';
            self::$estoma->tabla = Tabla::Intervenciones();
        }
        return self::$estoma;
    }

    private static $reingreso30Dias;

    /**
     * Singleton de reingreso30Dias
     *
     * @return Columna
     */
    static function reingreso30Dias()
    {
        if (! isset(self::$reingreso30Dias)) {
            self::$reingreso30Dias = new Columna();
            self::$reingreso30Dias->nombre = 'reingreso30Dias';
            self::$reingreso30Dias->tipo = TipoColumna::bool();
            self::$reingreso30Dias->columna = 'reingreso30Dias';
            self::$reingreso30Dias->tabla = Tabla::Intervenciones();
        }
        return self::$reingreso30Dias;
    }

    private static $histologia;

    /**
     * Singleton de histologia
     *
     * @return Columna
     */
    static function histologia()
    {
        if (! isset(self::$histologia)) {
            self::$histologia = new Columna();
            self::$histologia->nombre = 'histologia';
            self::$histologia->tipo = TipoColumna::string();
            self::$histologia->columna = 'histologia';
            self::$histologia->tabla = Tabla::Intervenciones();
        }
        return self::$histologia;
    }

    private static $T;

    /**
     * Singleton de T
     *
     * @return Columna
     */
    static function T()
    {
        if (! isset(self::$T)) {
            self::$T = new Columna();
            self::$T->nombre = 'T';
            self::$T->tipo = TipoColumna::string();
            self::$T->columna = 'T';
            self::$T->tabla = Tabla::Intervenciones();
        }
        return self::$T;
    }

    private static $N;

    /**
     * Singleton de N
     *
     * @return Columna
     */
    static function N()
    {
        if (! isset(self::$N)) {
            self::$N = new Columna();
            self::$N->nombre = 'N';
            self::$N->tipo = TipoColumna::string();
            self::$N->columna = 'N';
            self::$N->tabla = Tabla::Intervenciones();
        }
        return self::$N;
    }

    private static $M;

    /**
     * Singleton de M
     *
     * @return Columna
     */
    static function M()
    {
        if (! isset(self::$M)) {
            self::$M = new Columna();
            self::$M->nombre = 'M';
            self::$M->tipo = TipoColumna::string();
            self::$M->columna = 'M';
            self::$M->tabla = Tabla::Intervenciones();
        }
        return self::$M;
    }

    private static $distanciaMargenDistal;

    /**
     * Singleton de distanciaMargenDistal
     *
     * @return Columna
     */
    static function distanciaMargenDistal()
    {
        if (! isset(self::$distanciaMargenDistal)) {
            self::$distanciaMargenDistal = new Columna();
            self::$distanciaMargenDistal->nombre = 'distanciaMargenDistal';
            self::$distanciaMargenDistal->tipo = TipoColumna::int();
            self::$distanciaMargenDistal->columna = 'distanciaMargenDistal';
            self::$distanciaMargenDistal->tabla = Tabla::Intervenciones();
        }
        return self::$distanciaMargenDistal;
    }

    private static $gradoDiferenciacion;

    /**
     * Singleton de gradoDiferenciacion
     *
     * @return Columna
     */
    static function gradoDiferenciacion()
    {
        if (! isset(self::$gradoDiferenciacion)) {
            self::$gradoDiferenciacion = new Columna();
            self::$gradoDiferenciacion->nombre = 'gradoDiferenciacion';
            self::$gradoDiferenciacion->tipo = TipoColumna::string();
            self::$gradoDiferenciacion->columna = 'gradoDiferenciacion';
            self::$gradoDiferenciacion->tabla = Tabla::Intervenciones();
        }
        return self::$gradoDiferenciacion;
    }

    private static $intervencionCerrado;

    /**
     * Singleton de intervencionCerrado
     *
     * @return Columna
     */
    static function intervencionCerrado()
    {
        if (! isset(self::$intervencionCerrado)) {
            self::$intervencionCerrado = new Columna();
            self::$intervencionCerrado->nombre = 'intervencionCerrado';
            self::$intervencionCerrado->tipo = TipoColumna::bool();
            self::$intervencionCerrado->columna = 'cerrado';
            self::$intervencionCerrado->tabla = Tabla::Intervenciones();
        }
        return self::$intervencionCerrado;
    }

    // Dependientes de Intervencion, 1:1-0
    private static $tipoAnastomosis;

    /**
     * Singleton de tipoAnastomosis
     *
     * @return Columna
     */
    static function tipoAnastomosis()
    {
        if (! isset(self::$tipoAnastomosis)) {
            self::$tipoAnastomosis = new Columna();
            self::$tipoAnastomosis->nombre = 'tipoAnastomosis';
            self::$tipoAnastomosis->tipo = TipoColumna::string();
            self::$tipoAnastomosis->columna = 'tipo';
            self::$tipoAnastomosis->tabla = Tabla::Anastomosis();
        }
        return self::$tipoAnastomosis;
    }

    private static $tecnicaAnastomosis;

    /**
     * Singleton de tecnicaAnastomosis
     *
     * @return Columna
     */
    static function tecnicaAnastomosis()
    {
        if (! isset(self::$tecnicaAnastomosis)) {
            self::$tecnicaAnastomosis = new Columna();
            self::$tecnicaAnastomosis->nombre = 'tecnicaAnastomosis';
            self::$tecnicaAnastomosis->tipo = TipoColumna::string();
            self::$tecnicaAnastomosis->columna = 'tecnica';
            self::$tecnicaAnastomosis->tabla = Tabla::Anastomosis();
        }
        return self::$tecnicaAnastomosis;
    }

    private static $modalidadAnastomosis;

    /**
     * Singleton de modalidadAnastomosis
     *
     * @return Columna
     */
    static function modalidadAnastomosis()
    {
        if (! isset(self::$modalidadAnastomosis)) {
            self::$modalidadAnastomosis = new Columna();
            self::$modalidadAnastomosis->nombre = 'modalidadAnastomosis';
            self::$modalidadAnastomosis->tipo = TipoColumna::string();
            self::$modalidadAnastomosis->columna = 'modalidad';
            self::$modalidadAnastomosis->tabla = Tabla::Anastomosis();
        }
        return self::$modalidadAnastomosis;
    }

    private static $mandardCancerRecto;

    /**
     * Singleton de mandardCancerRecto
     *
     * @return Columna
     */
    static function mandardCancerRecto()
    {
        if (! isset(self::$mandardCancerRecto)) {
            self::$mandardCancerRecto = new Columna();
            self::$mandardCancerRecto->nombre = 'mandardCancerRecto';
            self::$mandardCancerRecto->tipo = TipoColumna::string();
            self::$mandardCancerRecto->columna = 'mandard';
            self::$mandardCancerRecto->tabla = Tabla::CanceresRecto();
        }
        return self::$mandardCancerRecto;
    }

    private static $calidadMesorrectoCancerRecto;

    /**
     * Singleton de calidadMesorrectoCancerRecto
     *
     * @return Columna
     */
    static function calidadMesorrectoCancerRecto()
    {
        if (! isset(self::$calidadMesorrectoCancerRecto)) {
            self::$calidadMesorrectoCancerRecto = new Columna();
            self::$calidadMesorrectoCancerRecto->nombre = 'calidadMesorrectoCancerRecto';
            self::$calidadMesorrectoCancerRecto->tipo = TipoColumna::string();
            self::$calidadMesorrectoCancerRecto->columna = 'calidadMesorrecto';
            self::$calidadMesorrectoCancerRecto->tabla = Tabla::CanceresRecto();
        }
        return self::$calidadMesorrectoCancerRecto;
    }

    private static $estadificaciones_CancerRecto;

    /**
     * Singleton de estadificaciones_CancerRecto
     *
     * @return Columna
     */
    static function estadificaciones_CancerRecto()
    {
        if (! isset(self::$estadificaciones_CancerRecto)) {
            self::$estadificaciones_CancerRecto = new Columna();
            self::$estadificaciones_CancerRecto->nombre = 'estadificaciones_CancerRecto';
            self::$estadificaciones_CancerRecto->tipo = TipoColumna::multString();
            self::$estadificaciones_CancerRecto->columna = 'localizacion';
            self::$estadificaciones_CancerRecto->tabla = Tabla::CanceresRecto();
        }
        return self::$estadificaciones_CancerRecto;
    }

    private static $hemodinamicamenteEstableUrgente;

    /**
     * Singleton de hemodinamicamenteEstableUrgente
     *
     * @return Columna
     */
    static function hemodinamicamenteEstableUrgente()
    {
        if (! isset(self::$hemodinamicamenteEstableUrgente)) {
            self::$hemodinamicamenteEstableUrgente = new Columna();
            self::$hemodinamicamenteEstableUrgente->nombre = 'hemodinamicamenteEstableUrgente';
            self::$hemodinamicamenteEstableUrgente->tipo = TipoColumna::bool();
            self::$hemodinamicamenteEstableUrgente->columna = 'hemodinamicamenteEstable';
            self::$hemodinamicamenteEstableUrgente->tabla = Tabla::Urgentes();
        }
        return self::$hemodinamicamenteEstableUrgente;
    }

    private static $insuficienciaRenalUrgente;

    /**
     * Singleton de insuficienciaRenalUrgente
     *
     * @return Columna
     */
    static function insuficienciaRenalUrgente()
    {
        if (! isset(self::$insuficienciaRenalUrgente)) {
            self::$insuficienciaRenalUrgente = new Columna();
            self::$insuficienciaRenalUrgente->nombre = 'insuficienciaRenalUrgente';
            self::$insuficienciaRenalUrgente->tipo = TipoColumna::bool();
            self::$insuficienciaRenalUrgente->columna = 'insuficienciaRenal';
            self::$insuficienciaRenalUrgente->tabla = Tabla::Urgentes();
        }
        return self::$insuficienciaRenalUrgente;
    }

    private static $peritonitisUrgente;

    /**
     * Singleton de peritonitisUrgente
     *
     * @return Columna
     */
    static function peritonitisUrgente()
    {
        if (! isset(self::$peritonitisUrgente)) {
            self::$peritonitisUrgente = new Columna();
            self::$peritonitisUrgente->nombre = 'peritonitisUrgente';
            self::$peritonitisUrgente->tipo = TipoColumna::string();
            self::$peritonitisUrgente->columna = 'peritonitis';
            self::$peritonitisUrgente->tabla = Tabla::Urgentes();
        }
        return self::$peritonitisUrgente;
    }

    private static $motivos_Urgente;

    /**
     * Singleton de motivos_Urgente
     *
     * @return Columna
     */
    static function motivos_Urgente()
    {
        if (! isset(self::$motivos_Urgente)) {
            self::$motivos_Urgente = new Columna();
            self::$motivos_Urgente->nombre = 'motivos_Urgente';
            self::$motivos_Urgente->tipo = TipoColumna::multString();
            self::$motivos_Urgente->columna = 'motivo';
            self::$motivos_Urgente->tabla = Tabla::Motivos();
        }
        return self::$motivos_Urgente;
    }

    // Dependientes de Intervencion, 1:*
    private static $tiposIntervencion;

    /**
     * Singleton de tiposIntervencion
     *
     * @return Columna
     */
    static function tiposIntervencion()
    {
        if (! isset(self::$tiposIntervencion)) {
            self::$tiposIntervencion = new Columna();
            self::$tiposIntervencion->nombre = 'tiposIntervencion';
            self::$tiposIntervencion->tipo = TipoColumna::multString();
            self::$tiposIntervencion->columna = 'tipo';
            self::$tiposIntervencion->tabla = Tabla::TiposIntervencion();
        }
        return self::$tiposIntervencion;
    }

    private static $complicacionesMedicas;

    /**
     * Singleton de complicacionesMedicas
     *
     * @return Columna
     */
    static function complicacionesMedicas()
    {
        if (! isset(self::$complicacionesMedicas)) {
            self::$complicacionesMedicas = new Columna();
            self::$complicacionesMedicas->nombre = 'complicacionesMedicas';
            self::$complicacionesMedicas->tipo = TipoColumna::multString();
            self::$complicacionesMedicas->columna = 'complicacion';
            self::$complicacionesMedicas->tabla = Tabla::ComplicacionesMedicas();
        }
        return self::$complicacionesMedicas;
    }

    private static $complicacionesCirugia;

    /**
     * Singleton de complicacionesCirugia
     *
     * @return Columna
     */
    static function complicacionesCirugia()
    {
        if (! isset(self::$complicacionesCirugia)) {
            self::$complicacionesCirugia = new Columna();
            self::$complicacionesCirugia->nombre = 'complicacionesCirugia';
            self::$complicacionesCirugia->tipo = TipoColumna::multString();
            self::$complicacionesCirugia->columna = 'complicacion';
            self::$complicacionesCirugia->tabla = Tabla::ComplicacionesCirugia();
        }
        return self::$complicacionesCirugia;
    }

    private static $transfusiones;

    /**
     * Singleton de transfusiones
     *
     * @return Columna
     */
    static function transfusiones()
    {
        if (! isset(self::$transfusiones)) {
            self::$transfusiones = new Columna();
            self::$transfusiones->nombre = 'transfusiones';
            self::$transfusiones->tipo = TipoColumna::multString();
            self::$transfusiones->columna = 'momento';
            self::$transfusiones->tabla = Tabla::Transfusiones();
        }
        return self::$transfusiones;
    }

    private static $complicacionesIntraoperatorias;

    /**
     * Singleton de complicacionesIntraoperatorias
     *
     * @return Columna
     */
    static function complicacionesIntraoperatorias()
    {
        if (! isset(self::$complicacionesIntraoperatorias)) {
            self::$complicacionesIntraoperatorias = new Columna();
            self::$complicacionesIntraoperatorias->nombre = 'complicacionesIntraoperatorias';
            self::$complicacionesIntraoperatorias->tipo = TipoColumna::multString();
            self::$complicacionesIntraoperatorias->columna = 'complicacion';
            self::$complicacionesIntraoperatorias->tabla = Tabla::ComplicacionesIntraoperatorias();
        }
        return self::$complicacionesIntraoperatorias;
    }

    private static $factoresRiesgo;

    /**
     * Singleton de factoresRiesgo
     *
     * @return Columna
     */
    static function factoresRiesgo()
    {
        if (! isset(self::$factoresRiesgo)) {
            self::$factoresRiesgo = new Columna();
            self::$factoresRiesgo->nombre = 'factoresRiesgo';
            self::$factoresRiesgo->tipo = TipoColumna::multString();
            self::$factoresRiesgo->columna = 'tipo';
            self::$factoresRiesgo->tabla = Tabla::FactoresRiesgo();
        }
        return self::$factoresRiesgo;
    }

    private static $intervencionesAsociadas;

    /**
     * Singleton de intervencionesAsociadas
     *
     * @return Columna
     */
    static function intervencionesAsociadas()
    {
        if (! isset(self::$intervencionesAsociadas)) {
            self::$intervencionesAsociadas = new Columna();
            self::$intervencionesAsociadas->nombre = 'intervencionesAsociadas';
            self::$intervencionesAsociadas->tipo = TipoColumna::multString();
            self::$intervencionesAsociadas->columna = 'intervencion';
            self::$intervencionesAsociadas->tabla = Tabla::IntervencionesAsociadas();
        }
        return self::$intervencionesAsociadas;
    }

    private static $accesos;

    /**
     * Singleton de accesos
     *
     * @return Columna
     */
    static function accesos()
    {
        if (! isset(self::$accesos)) {
            self::$accesos = new Columna();
            self::$accesos->nombre = 'accesos';
            self::$accesos->tipo = TipoColumna::multString();
            self::$accesos->columna = 'acceso';
            self::$accesos->tabla = Tabla::Accesos();
        }
        return self::$accesos;
    }
}