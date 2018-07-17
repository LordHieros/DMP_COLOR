<?php
include_once 'Utils.php';

final class Informes
{

    /**
     * Devuelve el valor del informe, de haber
     *
     * @param ItemFormulario $item
     * @param DatosTabla[] $datos
     * @return string
     * @throws Exception
     */
    public static function getInforme($item, $datos){
        $res = '';
        switch ($item->getNombre()){
            case ItemFormulario::CLS()->getNombre():
                $res = self::informeCLS($datos);
                break;
            case ItemFormulario::AL()->getNombre():
                $res = self::informeAL($datos);
                break;
            case ItemFormulario::estadioTNM()->getNombre():
                $res = self::informeEstadioTNM($datos);
                break;
            case ItemFormulario::IMC()->getNombre():
                $res = self::informeIMC($datos);
                break;
        }
        return $res;
    }

    /**
     * Devuelve el valor del informe de CLS, de ser posible calcularlo
     *
     * @param DatosTabla[] $datos
     * @return string
     * @throws Exception
     */
    private static function informeCLS($datos){
        $columnasNecesarias = [Columna::edad(), Columna::sexo(), Columna::asa(), Columna::talla(), Columna::peso(),
            Columna::distanciaMargenDistal(), Columna::duracion()];
        $columnas = array_merge($columnasNecesarias, [Columna::intoxicaciones(), Columna::neoadyuvancia(),
            Columna::motivos_Urgente(), Columna::intervencionesAsociadas()]);
        $campos = self::getCampos($datos, $columnas);
        $faltan = self::faltanColumnas($campos, $columnasNecesarias);
        if(empty($faltan)){
            return self::calculoCLS($campos[Columna::edad()->getNombre()], $campos[Columna::sexo()->getNombre()], $campos[Columna::asa()->getNombre()],
            $campos[Columna::talla()->getNombre()], $campos[Columna::peso()->getNombre()], $campos[Columna::intoxicaciones()->getNombre()],
            $campos[Columna::neoadyuvancia()->getNombre()], $campos[Columna::motivos_Urgente()->getNombre()], $campos[Columna::distanciaMargenDistal()->getNombre()],
            $campos[Columna::intervencionesAsociadas()->getNombre()], 0, $campos[Columna::duracion()->getNombre()]);
        }
        else{
            return 'Faltan campos necesarios: ' . implode(', ', $faltan);
        }
    }

    /**
     * Obtiene el CLS
     *
     * FIXME perdida de sangre (en cc) no aparece, se pone siempre a 0
     * @param int $edad
     * @param string $sexo
     * @param string $asa
     * @param int $talla
     * @param int $peso
     * @param $intoxicaciones
     * @param string $neoadyuvancia
     * @param string[] $motivos
     * @param int $distanciaMagenDistal
     * @param string[] $intervencionesAsociadas
     * @param int $perdidaSangre
     * @param int $duracion
     * @throws Exception
     * @return int
     */
    private static function calculoCLS($edad, $sexo, $asa, $talla, $peso, $intoxicaciones, $neoadyuvancia, $motivos, $distanciaMagenDistal, $intervencionesAsociadas, $perdidaSangre, $duracion){
        $cls = 0;
        if($edad>=80){
            $cls+=4;
        } else if ($edad>=70){
            $cls+=2;
        } else if ($edad>=60){
            $cls+=1;
        }
        if($sexo == ItemFormulario::SEXO_VARON){
            $cls+=1;
        }
        switch ($asa){
            case ItemFormulario::ASA_II:
                $cls+=1;
                break;
            case ItemFormulario::ASA_III:
                $cls+=3;
                break;
            case ItemFormulario::ASA_IV:
                $cls+=6;
                break;
        }
        $imc = self::calculoIMC($talla, $peso);
        if($imc>30 || $imc<19){
            $cls+=3;
        } else if ($imc>=25) {
            $cls+=1;
        }
        if(in_array(ItemFormulario::INTOXICACIONES_ALCOHOL, $intoxicaciones)){
            $cls+=1;
        }
        if(in_array(ItemFormulario::INTOXICACIONES_TABACO, $intoxicaciones)){
            $cls+=1;
        }
        if(in_array(ItemFormulario::INTOXICACIONES_ESTEROIDES, $intoxicaciones)){
            $cls+=4;
        }
        switch ($neoadyuvancia){
            case ItemFormulario::NEOADYUVANCIA_RT:
            case ItemFormulario::NEOADYUVANCIA_RT_CORTO:
                $cls+=1;
                break;
            case ItemFormulario::NEOADYUVANCIA_QT:
                $cls+=2;
                break;
            case ItemFormulario::NEOADYUVANCIA_RT_CORTO_QT:
            case ItemFormulario::NEOADYUVANCIA_RT_LARGO_QT:
                $cls+=3;
                break;
        }
        if(in_array(ItemFormulario::MOTIVOS_URGENTE_HEMORRAGIA, $motivos)){
            $cls+=2;
        }
        if(in_array(ItemFormulario::MOTIVOS_URGENTE_OBSTRUCCION, $motivos)){
            $cls+=3;
        }
        if(in_array(ItemFormulario::MOTIVOS_URGENTE_PERFORACION, $motivos)){
            $cls+=4;
        }
        if($distanciaMagenDistal<5){
            $cls+=6;
        } else if($distanciaMagenDistal<=10){
            $cls+=3;
        }
        if(!empty($intervencionesAsociadas)){
            $cls+=1;
        }
        if($perdidaSangre>2000){
            $cls+=6;
        } else if($perdidaSangre>1000){
            $cls+=3;
        } else if($perdidaSangre>500){
            $cls+=1;
        }
        if($duracion>=240){
            $cls+=4;
        } else if($duracion>=180){
            $cls+=2;
        } else if($duracion>=120){
            $cls+=1;
        }
        return $cls;
    }

    /**
     * Devuelve el valor del informe de AL, de ser posible calcularlo
     *
     * @param DatosTabla[] $datos
     * @throws Exception
     * @return string
     */
    private static function informeAL($datos){
        $columnasNecesarias = [Columna::anticoagulantes(), Columna::complicacionesIntraoperatorias(), Columna::peso(),
            Columna::talla(), Columna::albumina(), Columna::sexo(), Columna::numeroCamas()];
        $columnas = $columnasNecesarias;
        $campos = self::getCampos($datos, $columnas);
        $faltan = self::faltanColumnas($campos, $columnasNecesarias);
        if(empty($faltan)){
            return self::calculoAL($campos[Columna::anticoagulantes()->getNombre()], $campos[Columna::complicacionesIntraoperatorias()->getNombre()],
                $campos[Columna::peso()->getNombre()], $campos[Columna::talla()->getNombre()], $campos[Columna::albumina()->getNombre()],
                $campos[Columna::sexo()->getNombre()], $campos[Columna::numeroCamas()->getNombre()]);
        }
        else{
            return 'Faltan campos necesarios: ' . implode(', ', $faltan);
        }
    }

    /**
     * Devuelve la probabilidad de fuga anastomótica
     *
     * @param boolean $anticoagulantes
     * @param array $complicaciones
     * @param int $peso
     * @param int $talla
     * @param float $albumina
     * @param string $sexo
     * @param int $camas
     * @return string
     */
    private static function calculoAL($anticoagulantes, $complicaciones, $peso, $talla, $albumina, $sexo, $camas){
        $puntuacion = 0;
        if ($sexo == ItemFormulario::SEXO_VARON) {
            $puntuacion += 24;
        }
        if (self::calculoIMC($talla, $peso) > 30) {
            $puntuacion += 37;
        }
        if ($anticoagulantes) {
            $puntuacion += 22.5;
        }
        if (!empty($complicaciones)) {
            $puntuacion += 34;
        }
        if($albumina<=4) {
                $puntuacion += 91;
        } else if($albumina<=4.5) {
                $puntuacion += 83;
        } else if($albumina<=5) {
                $puntuacion += 76;
        } else if($albumina<=5.5) {
                $puntuacion += 67;
        } else if($albumina<=6) {
                $puntuacion += 58;
        } else if($albumina<=6.5) {
                $puntuacion += 50;
        } else if($albumina<=7) {
                $puntuacion += 41;
        } else if($albumina<=7.5) {
                $puntuacion += 33;
        } else if($albumina<=8) {
                $puntuacion += 24;
        }
        $puntuacion += (((1500 - $camas) / 100) * 1.429);
        $puntuacion = round($puntuacion);
        $previous = "<5";
        $probabilityLookup = [24 => "<5", 57 => "<5", 60 => "5", 67 => "6", 74 => "7", 80 => "8", 87 => "9", 93 => "10",
            96 => "11", 100 => "12", 104 => "13", 107 => "14", 111 => "15", 114 => "16", 118 => "17", 122 => "18",
            125 => "19", 128 => "20", 131 => "21", 133 => "22", 136 => "23", 138 => "24", 140 => "25", 143 => "26",
            145 => "27", 148 => "28", 150 => "29", 152 => "30", 155 => "31", 157 => "32", 159 => "33", 161 => "34",
            163 => "35", 166 => "36", 168 => "37", 170 => "38", 172 => "39", 174 => "40", 176 => "41", 178 => "42",
            181 => "43", 183 => "44", 185 => "45", 187 => "46", 189 => "47", 191 => "48", 193 => "49", 195 => "50" ];
        foreach (array_keys($probabilityLookup) as $key){
            if($puntuacion<$key){
                return  $previous . '%';
            }
            $previous = $probabilityLookup[$key];
        }
        return  $previous . '%';
    }

    /**
     * Devuelve el valor del informe de Estadio TNM, de ser posible calcularlo
     *
     * @param DatosTabla[] $datos
     * @throws Exception
     * @return string
     */
    private static function informeEstadioTNM($datos)
    {
        $columnasNecesarias = [Columna::T(), Columna::N(), Columna::M()];
        $columnas = $columnasNecesarias;
        $campos = self::getCampos($datos, $columnas);
        $faltan = self::faltanColumnas($campos, $columnasNecesarias);
        if(empty($faltan)){
            return self::consultaTNM($campos[Columna::T()->getNombre()], $campos[Columna::N()->getNombre()], $campos[Columna::M()->getNombre()]);
        }
        else{
            return 'Faltan campos necesarios: ' . implode(', ', $faltan);
        }
    }

    /**
     * Calcula el estadio de TNM
     *
     * @param string $T
     * @param string $N
     * @param string $M
     * @return string
     */
    private static function consultaTNM($T, $N, $M){
        if ($M == "M1") {
            return "Estadio: IV";
        } else if ($M == "M1a") {
            return "Estadio: IVA";
        } else if ($M == "M1b") {
            return "Estadio: IVB";
        } else if ($T == "TX" || $N == "NX") {
            return "Si no hay metástasis distante y no se pueden evaluar el tumor primario o los nodos linfáticos regionales no se puede calcular el estadio";
        } else if ($T == "Tis" && $N == "N0") {
            return "Estadio: 0";
        } else if ($T == "Tis" || $T == "T0") {
            return "*** CONSULTAR CON FERNANDO ***";
        } else if (($T == "T1" || $T == "T2") && $N == "N0") {
            return "Estadio: I";
        } else if ($T == "T3" && $N == "N0") {
            return "Estadio: IIA";
        } else if ($T == "T4a" && $N == "N0") {
            return "Estadio: IIB";
        } else if ($T == "T4b" && $N == "N0") {
            return "Estadio: IIC";
        } else if (!($T == "T4b") && $N == "N2") {
            return "Estadio: III, no se puede concluir si A, B o C sin especificar más la metástasis de los nodos linfáticos regionales";
        } else if ((($T == "T1" || $T == "T2") && ($N == "N1" || $N == "N1a" || $N == "N1b" || $N == "N1c")) || ($T == "T1" && $N == "N2a")) {
            return "Estadio: IIIA";
        } else if ((($T == "T3" || $T == "T4a") && ($N == "N1" || $N == "N1a" || $N == "N1b" || $N == "N1c")) || (($T == "T2" || $T == "T3") && $N == "N2a") || (($T == "T1" || $T == "T2") && $N == "N2b")) {
            return "Estadio: IIIB";
        } else if (($T == "T4a" && $N == "N2a") || (($T == "T3" || $T == "T4a") && $N == "N2b") || ($T == "T4b" && !($N == "N0" || $N == "NX"))) {
            return "Estadio: IIIC";
        } else {
            return "Error extraño, consultar admin";
        }
    }

    /**
     * Devuelve el valor del informe deIMC, de ser posible calcularlo
     *
     * @param DatosTabla[] $datos
     * @return string
     * @throws Exception
     */
    private static function informeIMC($datos){
        $columnasNecesarias = [Columna::talla(), Columna::peso()];
        $columnas = array_merge($columnasNecesarias, [Columna::edad()]);
        $campos = self::getCampos($datos, $columnas);
        $faltan = self::faltanColumnas($campos, $columnasNecesarias);
        if(empty($faltan)){
            return self::consultaIMC($campos[Columna::talla()->getNombre()], $campos[Columna::peso()->getNombre()], $campos[Columna::edad()->getNombre()]);
        }
        else{
            return 'Faltan campos necesarios: ' . implode(', ', $faltan);
        }
    }

    /**
     * Calcula el IMC comentado
     *
     * @param int $talla
     * @param int $peso
     * @param int $edad
     * @return string
     */
    private static function consultaIMC($talla, $peso, $edad){
        $res = self::calculoIMC($talla, $peso);
        if(empty($edad)){
            return $res . ". Sin especificar la edad no se conoce la relevancia de este índice.";
        }
        else if($edad > 20){
            if ($res<18.5){
                return $res . ". Este índice de masa corporal indica un peso bajo.";
            }
            else if ($res<25){
                return $res . ". Este índice de masa corporal indica un peso normal.";
            }
            else if ($res<30){
                return $res . ". Este índice de masa corporal indica un sobrepeso.";
            }
            else {
                return $res . ". Este índice de masa corporal indica obesidad.";
            }
        }
        else if ($edad <= 20 && $edad >= 0){
            return $res . ". Para conocer la relevancia de este índice antes de los veinte años hay que comparar con los IMCs relevantes para individuos de su edad y sexo.";
        }
        else {
            return $res . ". La edad es negativa. Consulte con un administrador ";
        }
    }

    /**
     * Calcula el IMC con dos dígitos de precisión
     *
     * @param int $talla
     * @param int $peso
     * @return float
     */
    private static function calculoIMC($talla, $peso){
        return round ( $peso/($talla*$talla/10000), 2 ); // res con dos dígitos de precision
    }

    /**
     * @param DatosTabla[] $datos
     * @param Columna[] $columnas
     * @throws Exception
     * @return DatosTabla[]
     */
    private static function completeDatos($datos, $columnas){
        foreach($columnas as $columna) {
            if(!array_key_exists($columna->getTabla()->getNombreTabla(), $datos)){
                Utils::console_log("Adding " . $columna->getTabla()->getNombreTabla());
                $datos[$columna->getTabla()->getNombreTabla()] = AccesoBD::loadTabla(DatosTabla::makeWithSessionKeys($columna->getTabla()));
            }
        }
        return $datos;
    }

    /**
     * @param DatosTabla[] $datos
     * @param Columna[] $columnas
     * @throws Exception
     * @return array
     */
    private static function getCampos($datos, $columnas){
        $datos = self::completeDatos($datos, $columnas);
        $campos = array();
        foreach ($columnas as $columna) {
            Utils::console_log("Looking " . $columna->getTabla()->getNombreTabla() . $columna->getNombre());
            if(!empty($datos[$columna->getTabla()->getNombreTabla()])) {
                $campo = $datos[$columna->getTabla()->getNombreTabla()]->getCampo($columna);
                if (!empty($campo)) {
                    $campos[$columna->getNombre()] = $campo;
                }
                else{
                    $campos[$columna->getNombre()] = null;
                }
            }
            else{
                $campos[$columna->getNombre()] = null;
            }
        }
        return $campos;
    }

    /**
     * @param array $campos
     * @param Columna[] $columnas
     * @throws Exception
     * @return string[]
     */
    private static function faltanColumnas($campos, $columnas)
    {
        $faltan = array();
        foreach ($columnas as $columna) {
            if ($columna->getTipo() !== TipoColumna::multString() && $columna->getTipo() !== TipoColumna::bool()) {
                if (empty($campos[$columna->getNombre()])) {
                    $faltan[] = $columna->getNombre();
                }
            }
        }
        return $faltan;
    }
}