<?php

final class CampoSession
{

    /**
     * Donde se almacena con que usuario se está conectado; en este caso no se usa el mismo nombre en tabla y sesion
     *
     * @var string
     */
    const USUARIO = "usuario";

    /**
     * true si se es admin, false si no
     *
     * @var string
     */
    const ADMINISTRADOR = "administrador";

    /**
     * Donde se almacena el usuario cuyos datos se están consultando (solo administradores, los usuarios normales lo tienen automáticament eigual a usuario)
     *
     * @var string
     */
    const NOMBRE_USUARIO = "nombreUsuario";

    /**
     * Donde se almacena el nasi de la filiación que se está consultando
     *
     * @var string
     */
    const NASI = "nasi";

    /**
     * Donde se almacena la fecha del diagnóstico que se está consultando
     *
     * @var string
     */
    const FECHA_DIAGNOSTICO = "fechaDiagnostico";

    /**
     * Donde se almacena la fecha de la intervención que se está consultando
     *
     * @var string
     */
    const FECHA_INTERVENCION = "fechaIntervencion";

    /**
     * Donde se almacena el mensaje de error
     *
     * @var string
     */
    const ERROR = "error";

    /**
     * Donde se almacena cualqueir otro mensaje pertinente
     *
     * @var string
     */
    const MENSAJE = "mensaje";

    /**
     * Donde se almacena el id de hospital (por ahora no se usa)
     *
     * @var string
     */
    const ID_HOSPITAL = "idHospital";
    //TODO algo con esto

    /**
     * make this private so noone can make one
     * CampoSession constructor.
     *
     * @throws Exception
     */
    private function __construct()
    {
        // throw an exception if someone can get in here (I'm paranoid)
        throw new Exception("Can't get an instance of Errors");
    }

    /**
     * Devuelve las pks, salvo NombreUsuario, ya que puede ser usuario o nombre
     *
     * @return array
     */
    public static function getPKs()
    {
        return array(
            self::NOMBRE_USUARIO,
            self::FECHA_DIAGNOSTICO,
            self::FECHA_INTERVENCION,
            self::NASI,
            self::ID_HOSPITAL
        );
    }
}