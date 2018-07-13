<?php

    final class Utils
    {

        /**
         * Imprime los datos especificados por consola
         *
         * @param string $data
         */
        public static function console_log( $data ){
            echo '<script>';
            echo 'console.log('. json_encode( $data ) .')';
            echo '</script>';
        }

        /**
         * Limpia la sesion, solo para panel (por ahora)
         */
        public static function flushSession(){
            $tmp1=$_SESSION[CampoSession::USUARIO]; //Cuando se entra a panel se borran los datos de sesión que no sean nombre de usuario, estado de administrador y mensaje de error
            $tmp2=$_SESSION[CampoSession::ADMINISTRADOR];
            $tmp3=$_SESSION[CampoSession::ID_HOSPITAL];
            if(isset($_SESSION[CampoSession::ERROR])) $tmp4=$_SESSION[CampoSession::ERROR];
            session_destroy();
            session_start();
            $_SESSION[CampoSession::USUARIO]=$tmp1;
            $_SESSION[CampoSession::ADMINISTRADOR]=$tmp2;
            $_SESSION[CampoSession::ID_HOSPITAL]=$tmp3;
            if(isset($tmp4)){
                $_SESSION[CampoSession::ERROR]=$tmp4;
                unset($tmp4);
            }
            unset($tmp1);
            unset($tmp2);
        }

        /**
         * Devuelve la traza de excepcion completa
         *
         * @param Exception $exception
         * @return string
         */
        private static function getExceptionTraceAsString($exception) {
            $rtn = "";
            $count = 0;
            foreach ($exception->getTrace() as $frame) {
                $args = "";
                if (isset($frame['args'])) {
                    $args = array();
                    foreach ($frame['args'] as $arg) {
                        if (is_string($arg)) {
                            $args[] = "'" . $arg . "'";
                        } elseif (is_array($arg)) {
                            $args[] = "Array";
                        } elseif (is_null($arg)) {
                            $args[] = 'NULL';
                        } elseif (is_bool($arg)) {
                            $args[] = ($arg) ? "true" : "false";
                        } elseif (is_object($arg)) {
                            $args[] = get_class($arg);
                        } elseif (is_resource($arg)) {
                            $args[] = get_resource_type($arg);
                        } else {
                            $args[] = $arg;
                        }
                    }
                    $args = join(", ", $args);
                }
                $rtn .= sprintf( "#%s %s(%s): %s(%s)\n",
                    $count,
                    $frame['file'],
                    $frame['line'],
                    $frame['function'],
                    $args );
                $count++;
            }
            return $rtn;
        }

        /**
         * Añade la excepción a Session Error e imprime la traza por consola
         *
         * @param Exception $e
         */
        public static function manageException($e){
            Utils::console_log($e->getMessage() . self::getExceptionTraceAsString($e));
            self::setError($e->getMessage());
        }

        /**
         * Devuelve el cuerpo básico del documento
         * Imprime, de haber error, borrandolo después (junto a mensaje)
         * Imprime, de haber, mensaje, borrándolo despues, solo si no hay error
         *
         * @return string
         */
        public static function getBasicBody(){
            $res = '';
            if(isset($_SESSION[CampoSession::ERROR])){ //En caso de que exista un mensaje de error, imprimirlo y borrarlo
                $res = '<h4 class="text-danger">' . $_SESSION[CampoSession::ERROR] . '</h4>';
                unset($_SESSION[CampoSession::ERROR]);
                unset($_SESSION[CampoSession::MENSAJE]);
            }
            else if(isset($_SESSION[CampoSession::MENSAJE])){
                $res = '<h4>' . $_SESSION[CampoSession::MENSAJE] . '</h4>';
                unset($_SESSION[CampoSession::MENSAJE]);
            }
            return $res;
        }

        /**
         * Manda a la localización, un header(location) mas seguro
         *
         * @param string $location
         * @param string $error
         */
        public static function headTo($location, $error)
        {
            if (!empty($error)) {
                self::setError($error);
            }
            header('location:' . $location);
            exit();
        }

        private static function setError($error){
            $error = $error . ' -- from:' . $_SERVER[CampoServer::PHP_SELF];
            if(!array_key_exists('CampoSession::ERROR', $_SESSION)) {
                $_SESSION[CampoSession::ERROR] = $error;
            }
            else {
                $_SESSION[CampoSession::ERROR] = $_SESSION[CampoSession::ERROR] . '<br/>' . $error;
            }
        }

        /**
         * Crea un array de claves a partir de los campos SESSION
         *
         * @return array
         */
        public static function getSessionClaves()
        {
            $claves = array();
            foreach (CampoSession::getPKs() as $PK) {
                if (array_key_exists($PK, $_SESSION)) {
                    $claves[$PK] = $_SESSION[$PK];
                }
            }
            return $claves;
        }

        /**
         * Permite la impresión de date en un formato más bonito
         *
         * @param $date
         * @return string
         */
        public static function readableDate($date){
            $res = '';
            if(!empty($date)) {
                if($date != TipoColumna::NO_DATE) {
                    $dia = $date[8] . $date[9];
                    $mes = $date[5] . $date[6];
                    $anho = $date[0] . $date[1] . $date[2] . $date[3];
                    $res = $dia . '/' . $mes . '/' . $anho;
                }
            }
            return $res;
        }
    }