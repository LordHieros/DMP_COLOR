<?php

class CampoServer
{

    /**
     * El documento actual (maomeno)
     *
     * @var string
     */
    const PHP_SELF = "PHP_SELF";

    /**
     * La raíz del documento
     *
     * @var string
     */
    const DOCUMENT_ROOT = "DOCUMENT_ROOT";

    /**
     * make this private so noone can make one
     * CampoServer constructor.
     *
     * @throws Exception
     */
    private function __construct()
    {
        // throw an exception if someone can get in here (I'm paranoid)
        throw new Exception("Can't get an instance of Errors");
    }
}