<?php

include_once("Columna.php");

final class Tabla
{

    private $nombreTabla;

    private $claves;

    private $campos;

    /**
     * Devuelve el nombre de la tabla
     *
     * @return string
     */
    function getNombreTabla()
    {
        return $this->nombreTabla;
    }

    /**
     * Devuelve las pks de la tabla, salvo los MULT_STRING
     *
     * @return Columna[]
     */
    function getClaves()
    {
        return $this->claves;
    }

    /**
     * Devuelve las columnas no PK de la tabla y los MULT_STRING
     * array vacio por defecto
     *
     * @return Columna[]
     */
    function getCampos()
    {
        return $this->campos;
    }

    /**
     * Devuelve todas las columnas de la tabla
     *
     * @return Columna[]
     */
    function getColumnas()
    {
        return array_merge($this->getCampos(), $this->getClaves());
    }

    // Esta propiedad solo existe para evitar repetir el chequeo de tablas ya chequeadas
    private $multivalued;

    /**
     * Devuelve la columna tipo MultString o null si no hay
     *
     * @return NULL|Columna
     */
    function getMultivalued()
    {
        if (! isset($this->multivalued)) {
            // Se podria añadir un chequeo de "solo un multivaluado por tabla" o excepcion, pero que palo
            $multivalued = null;
            foreach ($this->getColumnas() as $campo) {
                if ($campo->getTipo() === TipoColumna::multString()) {
                    $this->multivalued = $campo;
                }
            }
        }
        return $this->multivalued;
    }

    // Impide que la clase se instancie desde fuera
    private function __construct()
    {
        $this->campos = array();
    }

    private static $Hospitales;

    /**
     * Singleton de Hospitales
     *
     * @return Tabla
     */
    static function Hospitales()
    {
        if (! isset(self::$Hospitales)) {
            self::$Hospitales = new Tabla();
            self::$Hospitales->nombreTabla = 'Hospitales';
            self::$Hospitales->claves = array(
                Columna::idHospital()
            );
            self::$Hospitales->campos = array(
                Columna::numeroCamas()
            );
        }
        return self::$Hospitales;
    }

    private static $Usuarios;

    /**
     * Singleton de Usuarios
     *
     * @return Tabla
     */
    static function Usuarios()
    {
        if (! isset(self::$Usuarios)) {
            self::$Usuarios = new Tabla();
            self::$Usuarios->nombreTabla = 'Usuarios';
            self::$Usuarios->claves = array(
                Columna::nombreUsuario()
            );
            self::$Usuarios->campos = array(
                Columna::contrasenha(),
                Columna::administrador()
            );
        }
        return self::$Usuarios;
    }

    private static $Filiaciones;

    /**
     * Singleton de Filiaciones
     *
     * @return Tabla
     */
    static function Filiaciones()
    {
        if (! isset(self::$Filiaciones)) {
            self::$Filiaciones = new Tabla();
            self::$Filiaciones->nombreTabla = 'Filiaciones';
            self::$Filiaciones->claves = array(
                Columna::nasi()
            );
            self::$Filiaciones->campos = array(
                Columna::nombreUsuario(),
                Columna::nhc()
            );
        }
        return self::$Filiaciones;
    }

    private static $Diagnosticos;

    /**
     * Singleton de Diagnosticos
     *
     * @return Tabla
     */
    static function Diagnosticos()
    {
        if (! isset(self::$Diagnosticos)) {
            self::$Diagnosticos = new Tabla();
            self::$Diagnosticos->nombreTabla = 'Diagnosticos';
            self::$Diagnosticos->claves = array(
                Columna::nasi(),
                Columna::fechaDiagnostico()
            );
            self::$Diagnosticos->campos = array(
                Columna::sexo(),
                Columna::edad(),
                Columna::talla(),
                Columna::peso(),
                Columna::fechaIngreso(),
                Columna::fechaEndoscopia(),
                Columna::fechaBiopsia(),
                Columna::fechaPresentacion(),
                Columna::cea(),
                Columna::hemoglobina(),
                Columna::albumina(),
                Columna::asa(),
                Columna::anticoagulantes(),
                Columna::diagnosticoCerrado()
            );
        }
        return self::$Diagnosticos;
    }

    private static $Intervenciones;

    /**
     * Singleton de Intervenciones
     *
     * @return Tabla
     */
    static function Intervenciones()
    {
        if (! isset(self::$Intervenciones)) {
            self::$Intervenciones = new Tabla();
            self::$Intervenciones->nombreTabla = 'Intervenciones';
            self::$Intervenciones->claves = array(
                Columna::nasi(),
                Columna::fechaDiagnostico(),
                Columna::fechaIntervencion()
            );
            self::$Intervenciones->campos = array(
                Columna::duracion(),
                Columna::fechaListaEspera(),
                Columna::fechaAlta(),
                Columna::neoadyuvancia(),
                Columna::codigoCirujano(),
                Columna::tipoReseccion(),
                Columna::margenAfecto(),
                Columna::excision(),
                Columna::rio(),
                Columna::estoma(),
                Columna::reingreso30Dias(),
                Columna::histologia(),
                Columna::T(),
                Columna::N(),
                Columna::M(),
                Columna::distanciaMargenDistal(),
                Columna::gradoDiferenciacion(),
                Columna::intervencionCerrado()
            );
        }
        return self::$Intervenciones;
    }

    private static $MetastasisPulmonares;

    /**
     * Singleton de MetastasisPulmonares
     *
     * @return Tabla
     */
    static function MetastasisPulmonares()
    {
        if (! isset(self::$MetastasisPulmonares)) {
            self::$MetastasisPulmonares = new Tabla();
            self::$MetastasisPulmonares->nombreTabla = 'MetastasisPulmonares';
            self::$MetastasisPulmonares->claves = self::Diagnosticos()->claves;
        }
        return self::$MetastasisPulmonares;
    }

    private static $CirugiasPulmonares;

    /**
     * Singleton de CirugiasPulmonares
     *
     * @return Tabla
     */
    static function CirugiasPulmonares()
    {
        if (! isset(self::$CirugiasPulmonares)) {
            self::$CirugiasPulmonares = new Tabla();
            self::$CirugiasPulmonares->nombreTabla = 'CirugiasPulmonares';
            self::$CirugiasPulmonares->claves = self::Diagnosticos()->claves;
            self::$CirugiasPulmonares->campos = array(
                Columna::fechaCirugiaPulmonar()
            );
        }
        return self::$CirugiasPulmonares;
    }

    private static $MetastasisHepaticas;

    /**
     * Singleton de MetastasisHepaticas
     *
     * @return Tabla
     */
    static function MetastasisHepaticas()
    {
        if (! isset(self::$MetastasisHepaticas)) {
            self::$MetastasisHepaticas = new Tabla();
            self::$MetastasisHepaticas->nombreTabla = 'MetastasisHepaticas';
            self::$MetastasisHepaticas->claves = self::Diagnosticos()->claves;
        }
        return self::$MetastasisHepaticas;
    }

    private static $CirugiasHepaticas;

    /**
     * Singleton de CirugiasHepaticas
     *
     * @return Tabla
     */
    static function CirugiasHepaticas()
    {
        if (! isset(self::$CirugiasHepaticas)) {
            self::$CirugiasHepaticas = new Tabla();
            self::$CirugiasHepaticas->nombreTabla = 'CirugiasHepaticas';
            self::$CirugiasHepaticas->claves = self::Diagnosticos()->claves;
            self::$CirugiasHepaticas->campos = array(
                Columna::tipoCirugiaHepatica(),
                Columna::tecnicaCirugiaHepatica(),
                Columna::fechaCirugiaHepatica()
            );
        }
        return self::$CirugiasHepaticas;
    }

    private static $LocalizacionesCancer;

    /**
     * Singleton de LocalizacionesCancer
     *
     * @return Tabla
     */
    static function LocalizacionesCancer()
    {
        if (! isset(self::$LocalizacionesCancer)) {
            self::$LocalizacionesCancer = new Tabla();
            self::$LocalizacionesCancer->nombreTabla = 'LocalizacionesCancer';
            self::$LocalizacionesCancer->claves = self::Diagnosticos()->claves;
            self::$LocalizacionesCancer->campos = array(
                Columna::localizacionesCancer()
            );
        }
        return self::$LocalizacionesCancer;
    }

    private static $TumoresSincronicos;

    /**
     * Singleton de TumoresSincronicos
     *
     * @return Tabla
     */
    static function TumoresSincronicos()
    {
        if (! isset(self::$TumoresSincronicos)) {
            self::$TumoresSincronicos = new Tabla();
            self::$TumoresSincronicos->nombreTabla = 'TumoresSincronicos';
            self::$TumoresSincronicos->claves = self::Diagnosticos()->claves;
            self::$TumoresSincronicos->campos = array(
                Columna::tumoresSincronicos()
            );
        }
        return self::$TumoresSincronicos;
    }

    private static $Protesis;

    /**
     * Singleton de Protesis
     *
     * @return Tabla
     */
    static function Protesis()
    {
        if (! isset(self::$Protesis)) {
            self::$Protesis = new Tabla();
            self::$Protesis->nombreTabla = 'Protesis';
            self::$Protesis->claves = self::Diagnosticos()->claves;
            self::$Protesis->campos = array(
                Columna::fechaProtesis(),
                Columna::complicacionProtesis()
            );
        }
        return self::$Protesis;
    }

    private static $CausasIntervencion;

    /**
     * Singleton de CausasIntervencion
     *
     * @return Tabla
     */
    static function CausasIntervencion()
    {
        if (! isset(self::$CausasIntervencion)) {
            self::$CausasIntervencion = new Tabla();
            self::$CausasIntervencion->nombreTabla = 'CausasIntervencion';
            self::$CausasIntervencion->claves = self::Diagnosticos()->claves;
            self::$CausasIntervencion->campos = array(
                Columna::causasIntervencion()
            );
        }
        return self::$CausasIntervencion;
    }

    private static $Comorbilidades;

    /**
     * Singleton de Comorbilidades
     *
     * @return Tabla
     */
    static function Comorbilidades()
    {
        if (! isset(self::$Comorbilidades)) {
            self::$Comorbilidades = new Tabla();
            self::$Comorbilidades->nombreTabla = 'Comorbilidades';
            self::$Comorbilidades->claves = self::Diagnosticos()->claves;
            self::$Comorbilidades->campos = array(
                Columna::comorbilidades()
            );
        }
        return self::$Comorbilidades;
    }

    private static $Metastasis;

    /**
     * Singleton de Metastasis
     *
     * @return Tabla
     */
    static function Metastasis()
    {
        if (! isset(self::$Metastasis)) {
            self::$Metastasis = new Tabla();
            self::$Metastasis->nombreTabla = 'Metastasis';
            self::$Metastasis->claves = self::Diagnosticos()->claves;
            self::$Metastasis->campos = array(
                Columna::metastasis()
            );
        }
        return self::$Metastasis;
    }

    private static $Intoxicaciones;

    /**
     * Singleton de Intoxicaciones
     *
     * @return Tabla
     */
    static function Intoxicaciones()
    {
        if (! isset(self::$Intoxicaciones)) {
            self::$Intoxicaciones = new Tabla();
            self::$Intoxicaciones->nombreTabla = 'Intoxicaciones';
            self::$Intoxicaciones->claves = self::Diagnosticos()->claves;
            self::$Intoxicaciones->campos = array(
                Columna::intoxicaciones()
            );
        }
        return self::$Intoxicaciones;
    }

    private static $CanceresRecto;

    /**
     * Singleton de CanceresRecto
     *
     * @return Tabla
     */
    static function CanceresRecto()
    {
        if (! isset(self::$CanceresRecto)) {
            self::$CanceresRecto = new Tabla();
            self::$CanceresRecto->nombreTabla = 'CanceresRecto';
            self::$CanceresRecto->claves = self::Intervenciones()->claves;
            self::$CanceresRecto->campos = array(
                Columna::mandardCancerRecto(),
                Columna::calidadMesorrectoCancerRecto()
            );
        }
        return self::$CanceresRecto;
    }

    private static $Estadificaciones;

    /**
     * Singleton de Estadificaciones
     *
     * @return Tabla
     */
    static function Estadificaciones()
    {
        if (! isset(self::$Estadificaciones)) {
            self::$Estadificaciones = new Tabla();
            self::$Estadificaciones->nombreTabla = 'Estadificaciones';
            self::$Estadificaciones->claves = self::Intervenciones()->claves;
            self::$Estadificaciones->campos = array(
                Columna::estadificaciones_CancerRecto()
            );
        }
        return self::$Estadificaciones;
    }

    private static $ComplicacionesIntraoperatorias;

    /**
     * Singleton de ComplicacionesIntraoperatorias
     *
     * @return Tabla
     */
    static function ComplicacionesIntraoperatorias()
    {
        if (! isset(self::$ComplicacionesIntraoperatorias)) {
            self::$ComplicacionesIntraoperatorias = new Tabla();
            self::$ComplicacionesIntraoperatorias->nombreTabla = 'ComplicacionesIntraoperatorias';
            self::$ComplicacionesIntraoperatorias->claves = self::Intervenciones()->claves;
            self::$ComplicacionesIntraoperatorias->campos = array(
                Columna::complicacionesIntraoperatorias()
            );
        }
        return self::$ComplicacionesIntraoperatorias;
    }

    private static $FactoresRiesgo;

    /**
     * Singleton de FactoresRiesgo
     *
     * @return Tabla
     */
    static function FactoresRiesgo()
    {
        if (! isset(self::$FactoresRiesgo)) {
            self::$FactoresRiesgo = new Tabla();
            self::$FactoresRiesgo->nombreTabla = 'FactoresRiesgo';
            self::$FactoresRiesgo->claves = self::Intervenciones()->claves;
            self::$FactoresRiesgo->campos = array(
                Columna::factoresRiesgo()
            );
        }
        return self::$FactoresRiesgo;
    }

    private static $IntervencionesAsociadas;

    /**
     * Singleton de IntervencionesAsociadas
     *
     * @return Tabla
     */
    static function IntervencionesAsociadas()
    {
        if (! isset(self::$IntervencionesAsociadas)) {
            self::$IntervencionesAsociadas = new Tabla();
            self::$IntervencionesAsociadas->nombreTabla = 'IntervencionesAsociadas';
            self::$IntervencionesAsociadas->claves = self::Intervenciones()->claves;
            self::$IntervencionesAsociadas->campos = array(
                Columna::intervencionesAsociadas()
            );
        }
        return self::$IntervencionesAsociadas;
    }

    private static $Accesos;

    /**
     * Singleton de Accesos
     *
     * @return Tabla
     */
    static function Accesos()
    {
        if (! isset(self::$Accesos)) {
            self::$Accesos = new Tabla();
            self::$Accesos->nombreTabla = 'Accesos';
            self::$Accesos->claves = self::Intervenciones()->claves;
            self::$Accesos->campos = array(
                Columna::accesos()
            );
        }
        return self::$Accesos;
    }

    private static $Urgentes;

    /**
     * Singleton de Urgentes
     *
     * @return Tabla
     */
    static function Urgentes()
    {
        if (! isset(self::$Urgentes)) {
            self::$Urgentes = new Tabla();
            self::$Urgentes->nombreTabla = 'Urgentes';
            self::$Urgentes->claves = self::Intervenciones()->claves;
            self::$Urgentes->campos = array(
                Columna::hemodinamicamenteEstableUrgente(),
                Columna::insuficienciaRenalUrgente(),
                Columna::peritonitisUrgente()
            );
        }
        return self::$Urgentes;
    }

    private static $Motivos;

    /**
     * Singleton de Motivos
     *
     * @return Tabla
     */
    static function Motivos()
    {
        if (! isset(self::$Motivos)) {
            self::$Motivos = new Tabla();
            self::$Motivos->nombreTabla = 'Motivos';
            self::$Motivos->claves = self::Intervenciones()->claves;
            self::$Motivos->campos = array(
                Columna::motivos_Urgente()
            );
        }
        return self::$Motivos;
    }

    private static $ComplicacionesMedicas;

    /**
     * Singleton de ComplicacionesMedicas
     *
     * @return Tabla
     */
    static function ComplicacionesMedicas()
    {
        if (! isset(self::$ComplicacionesMedicas)) {
            self::$ComplicacionesMedicas = new Tabla();
            self::$ComplicacionesMedicas->nombreTabla = 'ComplicacionesMedicas';
            self::$ComplicacionesMedicas->claves = self::Intervenciones()->claves;
            self::$ComplicacionesMedicas->campos = array(
                Columna::complicacionesMedicas()
            );
        }
        return self::$ComplicacionesMedicas;
    }

    private static $Anastomosis;

    /**
     * Singleton de Anastomosis
     *
     * @return Tabla
     */
    static function Anastomosis()
    {
        if (! isset(self::$Anastomosis)) {
            self::$Anastomosis = new Tabla();
            self::$Anastomosis->nombreTabla = 'Anastomosis';
            self::$Anastomosis->claves = self::Intervenciones()->claves;
            self::$Anastomosis->campos = array(
                Columna::tipoAnastomosis(),
                Columna::tecnicaAnastomosis(),
                Columna::modalidadAnastomosis()
            );
        }
        return self::$Anastomosis;
    }

    private static $TiposIntervencion;

    /**
     * Singleton de TiposIntervencion
     *
     * @return Tabla
     */
    static function TiposIntervencion()
    {
        if (! isset(self::$TiposIntervencion)) {
            self::$TiposIntervencion = new Tabla();
            self::$TiposIntervencion->nombreTabla = 'TiposIntervencion';
            self::$TiposIntervencion->claves = self::Intervenciones()->claves;
            self::$TiposIntervencion->campos = array(
                Columna::tiposIntervencion()
            );
        }
        return self::$TiposIntervencion;
    }

    private static $ComplicacionesCirugia;

    /**
     * Singleton de ComplicacionesCirugia
     *
     * @return Tabla
     */
    static function ComplicacionesCirugia()
    {
        if (! isset(self::$ComplicacionesCirugia)) {
            self::$ComplicacionesCirugia = new Tabla();
            self::$ComplicacionesCirugia->nombreTabla = 'ComplicacionesCirugia';
            self::$ComplicacionesCirugia->claves = self::Intervenciones()->claves;
            self::$ComplicacionesCirugia->campos = array(
                Columna::complicacionesCirugia()
            );
        }
        return self::$ComplicacionesCirugia;
    }

    private static $Transfusiones;

    /**
     * Singleton de Transfusiones
     *
     * @return Tabla
     */
    static function Transfusiones()
    {
        if (! isset(self::$Transfusiones)) {
            self::$Transfusiones = new Tabla();
            self::$Transfusiones->nombreTabla = 'Transfusiones';
            self::$Transfusiones->claves = self::Intervenciones()->claves;
            self::$Transfusiones->campos = array(
                Columna::transfusiones()
            );
        }
        return self::$Transfusiones;
    }
}