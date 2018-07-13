<?php

final class Modelo
// Formato de datos a pasar para un modelo: array, claves nombreTabla (una por modelo), cada elemento es un elemento
// de la tabla, clave nombre columna, contenido contenido columna. Mismo para returns.
{

    private $tablas;

    /**
     * Devuelve las tablas del modelo
     *
     * @return Tabla[]
     */
    function getTablas()
    {
        return $this->tablas;
    }

    // Impide que la clase se instancie desde fuera
    private function __construct()
    {}

    private static $modeloDiagnostico;

    /**
     * Singleton de modeloDiagnostico
     *
     * @return Modelo
     */
    static function modeloDiagnostico()
    {
        if (! isset(self::$modeloDiagnostico)) {
            self::$modeloDiagnostico = new Modelo();
            self::$modeloDiagnostico->tablas = array(
                Tabla::Diagnosticos(),
                Tabla::MetastasisPulmonares(),
                Tabla::CirugiasPulmonares(),
                Tabla::MetastasisHepaticas(),
                Tabla::CirugiasHepaticas(),
                Tabla::Intoxicaciones(),
                Tabla::Comorbilidades(),
                Tabla::Metastasis(),
                Tabla::LocalizacionesCancer(),
                Tabla::TumoresSincronicos(),
                Tabla::Protesis(),
                Tabla::CausasIntervencion()
            );
        }
        return self::$modeloDiagnostico;
    }
    
    private static $modeloIntervencion;
    
    /**
     * Singleton de modeloIntervencion
     *
     * @return Modelo
     */
    static function modeloIntervencion()
    {
        if (! isset(self::$modeloIntervencion)) {
            self::$modeloIntervencion = new Modelo();
            self::$modeloIntervencion->tablas = array(
                Tabla::Intervenciones(),
                Tabla::ComplicacionesIntraoperatorias(),
                Tabla::FactoresRiesgo(),
                Tabla::IntervencionesAsociadas(),
                Tabla::Accesos(),
                Tabla::Urgentes(),
                Tabla::Motivos(),
                Tabla::Transfusiones(),
                Tabla::ComplicacionesCirugia(),
                Tabla::TiposIntervencion(),
                Tabla::Anastomosis(),
                Tabla::ComplicacionesMedicas(),
                Tabla::CanceresRecto(),
                Tabla::Estadificaciones()
            );
        }
        return self::$modeloIntervencion;
    }

    private static $modeloUsuarios;

    /**
     * Singleton de modeloUsuarios
     *
     * @return Modelo
     */
    static function modeloUsuarios()
    {
        if (! isset(self::$modeloUsuarios)) {
            self::$modeloUsuarios = new Modelo();
            self::$modeloUsuarios->tablas = array(
                Tabla::Usuarios()
            );
        }
        return self::$modeloUsuarios;
    }

    private static $modeloHospitales;

    /**
     * Singleton de modeloHospitales
     *
     * @return Modelo
     */
    static function modeloHospitales()
    {
        if (! isset(self::$modeloHospitales)) {
            self::$modeloHospitales = new Modelo();
            self::$modeloHospitales->tablas = array(
                Tabla::Hospitales()
            );
        }
        return self::$modeloHospitales;
    }

    private static $modeloFiliaciones;

    /**
     * Singleton de modeloFiliacion
     *
     * @return Modelo
     */
    static function modeloFiliaciones()
    {
        if (! isset(self::$modeloFiliaciones)) {
            self::$modeloFiliaciones = new Modelo();
            self::$modeloFiliaciones->tablas = array(
                Tabla::Filiaciones()
            );
        }
        return self::$modeloFiliaciones;
    }

    /**
     * Carga los datos del modelo, devolviendolos, con clave (array de nombreColumna->claveQueEs)
     * Lanza excepcion si hay problemas con el acceso a base de datos
     * devuelve null en caso de pasar modelo vacío (nunca debiera pasar)
     *
     * @param Modelo $modelo
     * @param array $claves
     * @throws Exception
     * @return DatosTabla[]
     */
    static function loadModelo($modelo, $claves)
    {
        try {
            $datos = null;
            foreach ($modelo->getTablas() as $tabla) {
                $datos[$tabla->getNombreTabla()] = AccesoBD::loadTabla(DatosTabla::makeFromClaves($claves, $tabla));
            }
            return $datos;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Guarda los datos en el modelo; insert si no había, update si si, delete si no hay datos en la tabla.
     * Lanza excepcion si hay problemas con el acceso a base de datos o los datos no son correctos
     *
     * @param Modelo $modelo
     * @param DatosTabla[] $datos
     * @throws Exception
     */
    static function saveModelo($modelo, $datos)
    {
        if (! self::checkDatos($datos)) {
            throw new Exception("La entrada no es un array");
        } else {
            try {
                // Crea unas claves "básicas" para hacer borrados
                $datosBase = reset($datos);
                $claves = array();
                foreach ($modelo->getTablas()[0]->getClaves() as $clave) {
                    $claves[$clave->getNombre()] = $datosBase->getCampo($clave);
                }
                foreach ($modelo->getTablas() as $tabla) {
                    if (array_key_exists($tabla->getNombreTabla(), $datos)) {
                        AccesoBD::saveTabla($datos[$tabla->getNombreTabla()]);
                    } else {
                        $unosDatos = new DatosTabla($tabla);
                        $unosDatos->setCampos($claves);
                        AccesoBD::deleteTabla($unosDatos);
                    }
                }
            } catch (Exception $e) {
                throw $e;
            }
        }
    }

    /**
     * Comprueba si los datos estén bien formateados.
     * true si lo están, false si no.
     *
     * @param DatosTabla[] $datos
     * @return boolean
     */
    private static function checkDatos($datos)
    {
        return is_array($datos);
    }
}