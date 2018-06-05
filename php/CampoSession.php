<?php

final class CampoSession
{

    /**
     * Donde se almacena con que usuario se está conectado
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
     * Donde se almacena el usuario cuyos datos se están consultando (solo administradores)
     *
     * @var string
     */
    const NOMBRE = "nombre";

    /**
     * Donde se almacena el nasi del paciente que se está consultando
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
    public static function getPKs(){
        return array(self::ID_HOSPITAL, self::FECHA_DIAGNOSTICO, self::FECHA_INTERVENCION, self::NASI);
    }
}