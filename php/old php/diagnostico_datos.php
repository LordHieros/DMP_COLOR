<?php
	include('header.php');
	include('use_database.php');
	
	//Inicializar variables de sesion
	$nasi=$_SESSION[CampoSession::NASI];
	require 'Conexion.php';
	
	if(isset($_POST["cerrar"])){ //En caso de estar editando o cerrando/abriendo comprueba diagnóstico y carga fecha desde sesión
		$fecha = $_SESSION[CampoSession::FECHA_DIAGNOSTICO];
	}
	else{
		if($_POST["editar"]=='true'){
			$fecha = $_SESSION[CampoSession::FECHA_DIAGNOSTICO];
		}
		else{ //En caso contrario solo comprueba NASI y carga fecha desde post y la añade a la sesión
			comprueba_nasi();
			if(isset($_POST['fecha'])){
				$fecha = $_POST['fecha'];
				$stmt = Conexion::getpdo()->prepare('SELECT * FROM Diagnosticos where Filiaciones_NASI=:nasi AND fecha=:fecha;');
				$stmt->execute(['nasi' => $nasi, 'fecha' => $fecha]);
				$resultado = $stmt->fetchAll();
				if (count($resultado) > 0){ //En caso de que se intente introducir una fecha ya existente se devuelve un mensaje de error.
					$_SESSION[CampoSession::ERROR] = "Ya existe un diagnóstico para la filiación " . $nasi . " con fecha " . $fecha;
					header('location: ../panel.php');
					die;
				}
			}
			else{ //En caso de que no esté la fecha en post se imprime mensaje de error y manda a panel
				$_SESSION[CampoSession::ERROR] = "Se ha llegado a la página de introducción de datos de diagnóstico de forma ilegal";
				header('location: ../panel.php');
				die;
			}
			$_SESSION[CampoSession::FECHA_DIAGNOSTICO] = $fecha;
		}
	}
	
	//Caso de querer cerrar expediente (se repite el mismo if, pero lo dejamos separado para simplificar la lectura)
	if(isset($_POST["cerrar"])){
		$stmt = Conexion::getpdo()->prepare('UPDATE Diagnosticos SET cerrado = :cerrado WHERE Filiaciones_NASI = :nasi AND fecha = :fecha;');
		if($_POST["cerrar"]=='true')
			if(verifica_diagnostico()=='OK'){
				$stmt->execute(['cerrado' => 1, 'nasi' => $nasi, 'fecha' => $fecha]);
			}
			else{
				//print error
			}
		else if($_POST["cerrar"]=='false' && $_SESSION[CampoSession::USUARIO])
			$stmt->execute(['cerrado' => 0, 'nasi' => $nasi, 'fecha' => $fecha]);
		else{
			$_SESSION[CampoSession::ERROR] = 'Error de acceso de datos';
			header("location: ../panel.php");
			die;
		}
	}
	
	else{
		//Inicializar variables para introducir/editar
		if(isset($_POST['fechaEndoscopia'])){
			$fechaEndoscopia = $_POST['fechaEndoscopia'];
		}
		if(isset($_POST['fechaBiopsia'])){
			$fechaBiopsia = $_POST['fechaBiopsia'];
		}
		if(isset($_POST['fechaPresentacion'])){
			$fechaPresentacion = $_POST['fechaPresentacion'];
		}
		if(isset($_POST['Adyuvancias_tipo'])){
			$Adyuvancias_tipo = $_POST['Adyuvancias_tipo'];
		}
		if(isset($_POST['Adyuvancias_fechaQuimioterapia'])){
			$Adyuvancias_fechaQuimioterapia = $_POST['Adyuvancias_fechaQuimioterapia'];
		}
		if(isset($_POST['MetastasisHepaticas_CirugiasHepaticas_tipoCirugia'])){
			$MetastasisHepaticas_CirugiasHepaticas_tipoCirugia = $_POST['MetastasisHepaticas_CirugiasHepaticas_tipoCirugia'];
		}
		if(isset($_POST['MetastasisHepaticas_CirugiasHepaticas_tecnicaCirugia'])){
			$MetastasisHepaticas_CirugiasHepaticas_tecnicaCirugia = $_POST['MetastasisHepaticas_CirugiasHepaticas_tecnicaCirugia'];
		}
		if(isset($_POST['MetastasisHepaticas_CirugiasHepaticas_fechaCirugia'])){
			$MetastasisHepaticas_CirugiasHepaticas_fechaCirugia = $_POST['MetastasisHepaticas_CirugiasHepaticas_fechaCirugia'];
		}
		if(isset($_POST['MetástasisPulmonares_CirugiasPulmonares_fechaCirugia'])){
			$MetástasisPulmonares_CirugiasPulmonares_fechaCirugia = $_POST['MetástasisPulmonares_CirugiasPulmonares_fechaCirugia'];
		}
		if(isset($_POST['T'])){
			$T = $_POST['T'];
		}
		if(isset($_POST['N'])){
			$N = $_POST['N'];
		}
		if(isset($_POST['M'])){
			$M = $_POST['M'];
		}
		if(isset($_POST['CanceresRecto_mandard'])){
			$CanceresRecto_mandard = $_POST['CanceresRecto_mandard'];
		}
		if(isset($_POST['CanceresRecto_calidadMesorrecto'])){
			$CanceresRecto_calidadMesorrecto = $_POST['CanceresRecto_calidadMesorrecto'];
		}
		if(isset($_POST['patologia'])){
			$patologia = $_POST['patologia'];
		}
		if(isset($_POST['endoscopia'])){
			$endoscopia = $_POST['endoscopia'];
		}
		if(isset($_POST['biopsia'])){
			$biopsia = $_POST['biopsia'];
		}
		if(isset($_POST['presentacion'])){
			$presentacion = $_POST['presentacion'];
		}
		if(isset($_POST['TumoresSincronicos'])){
			$TumoresSincronicos = $_POST['TumoresSincronicos'];
		}
		if(isset($_POST['CEA'])){
			$CEA = $_POST['CEA'];
		}
		if(isset($_POST['hemoglobina'])){
			$hemoglobina = $_POST['hemoglobina'];
		}
		if(isset($_POST['Adyuvancias'])){
			$Adyuvancias = $_POST['Adyuvancias'];
		}
		if(isset($_POST['MetastasisHepaticas'])){
			$MetastasisHepaticas = $_POST['MetastasisHepaticas'];
		}
		if(isset($_POST['MetastasisPulmonares'])){
			$MetastasisPulmonares = $_POST['MetastasisPulmonares'];
		}
		if(isset($_POST['Metastasis'])){
			$Metastasis = $_POST['Metastasis'];
		}
		if(isset($_POST['histologia'])){
			$histologia = $_POST['histologia'];
		}
		if(isset($_POST['distanciaMargenDistal'])){
			$distanciaMargenDistal = $_POST['distanciaMargenDistal'];
		}
		if(isset($_POST['gradoDiferenciacion'])){
			$gradoDiferenciacion = $_POST['gradoDiferenciacion'];
		}
		if(isset($_POST['CanceresRecto'])){
			$CanceresRecto = $_POST['CanceresRecto'];
		}
		//A partir de aqui arrays (Separados para que sea más facil identificarlos)
		if(isset($_POST['CausasIntervencion_causa'])){
			$CausasIntervencion_causa = $_POST['CausasIntervencion_causa'];
		}
		if(isset($_POST['Comorbilidades_comorbilidad'])){
			$Comorbilidades_comorbilidad = $_POST['Comorbilidades_comorbilidad'];
		}
		if(isset($_POST['FactoresRiesgo_tipo'])){
			$FactoresRiesgo_tipo = $_POST['FactoresRiesgo_tipo'];
		}
		if(isset($_POST['LocalizacionesCancer_localizacion'])){
			$LocalizacionesCancer_localizacion = $_POST['LocalizacionesCancer_localizacion'];
		}
		if(isset($_POST['TumoresSincronicos_localizacion'])){
			$TumoresSincronicos_localizacion = $_POST['TumoresSincronicos_localizacion'];
		}
		if(isset($_POST['Metastasis_localizacion'])){
			$Metastasis_localizacion = $_POST['Metastasis_localizacion'];
		}
		if(isset($_POST['CanceresRecto_Estadificaciones_localizacion'])){
			$CanceresRecto_Estadificaciones_localizacion = $_POST['CanceresRecto_Estadificaciones_localizacion'];
		}
		//Comprobamos si se está editando
		$editar = false;
		if(isset($_POST["editar"])){
			if($_POST["editar"] == 'true'){
				$editar = true;
			}
		}
		//Empezamos con la introduccion de datos
		$base = 'Diagnosticos';
		$claves_principal = ['Filiaciones_NASI', 'fecha'];
		$datos_principal = [$nasi, $fecha];
		if(!$editar){
			$claves[] = 'cerrado';
			$datos[] = 0;
		}
		//Empezamos por los valores obligatorios
		$claves[] = 'patologia';
		$datos[] = $patologia;
		$claves[] = 'histologia';
		$datos[] = $histologia;
		$claves[] = 'T';
		$datos[] = $T;
		$claves[] = 'N';
		$datos[] = $N;
		$claves[] = 'M';
		$datos[] = $M;
		$claves[] = 'gradoDiferenciacion';
		$datos[] = $gradoDiferenciacion;
		$claves[] = 'fechaEndoscopia';
		if($endoscopia == 'Si'){ //Introducimos, de existir, la fecha de la endoscopia
			$datos[] = $fechaEndoscopia;
		}
		else{
			$datos[] = null;
		}
		$claves[] = 'fechaBiopsia';
		if($biopsia == 'Si'){//Introducimos, de existir, la fecha de la biopsia
			$datos[] = $fechaBiopsia;
		}
		else{
			$datos[] = null;
		}
		$claves[] = 'fechaPresentacion';
		if($presentacion == 'Si'){//Introducimos, de existir, la fecha de la presentación en comité CCR
			$datos[] = $fechaPresentacion;
		}
		else{
			$datos[] = null;
		}
		$claves[] = 'CEA';
		if(!empty($CEA)){//Introducimos, de existir, el CEA preoperatorio
			$datos[] = $CEA;
		}
		else{
			$datos[] = null;
		}
		$claves[] = 'hemoglobina';
		if(!empty($hemoglobina)){//Introducimos, de existir, la hemoglobina preoperatoria
			$datos[] = $hemoglobina;
		}
		else{
			$datos[] = null;
		}
		$claves[] = 'distanciaMargenDistal';
		if(!empty($distanciaMargenDistal)){//Introducimos, de existir, la distancia al margen distal
			$datos[] = $distanciaMargenDistal;
		}
		else{
			$datos[] = null;
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Para todo el resto de consultar el nombre de la clave principal, que no su valor, cambia
		$claves_principal = ['Filiaciones_NASI', 'Diagnosticos_fecha'];
		//Introducimos los factores de riesgo
		$base = 'FactoresRiesgo';
		$claves[] = 'tipo';
		if(!empty($FactoresRiesgo_tipo)){
			$datos[] = $FactoresRiesgo_tipo;
		}
		else{
			$datos[] = [null];
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos las localizaciones de las metástasis
		$base = 'Metastasis';
		$claves[] = 'localizacion';
		if(!empty($Metastasis_localizacion)){
			$datos[] = $Metastasis_localizacion;
		}
		else{
			$datos[] = [null];
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos las localizaciones de los tumores sincrónicos
		$base = 'TumoresSincronicos';
		$claves[] = 'localizacion';
		if(!empty($TumoresSincronicos_localizacion)){
			$datos[] = $TumoresSincronicos_localizacion;
		}
		else{
			$datos[] = [null];
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos las localizaciones de cancer
		$base = 'LocalizacionesCancer';
		$claves[] = 'localizacion';
		if(!empty($LocalizacionesCancer_localizacion)){
			$datos[] = $LocalizacionesCancer_localizacion;
		}
		else{
			$datos[] = [null];
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos las causas de la intervencion
		$base = 'CausasIntervencion';
		$claves[] = 'causa';
		if(!empty($CausasIntervencion_causa)){
			$datos[] = $CausasIntervencion_causa;
		}
		else{
			$datos[] = [null];
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos las comorbilidades
		$base = 'Comorbilidades';
		$claves[] = 'comorbilidad';
		if(!empty($Comorbilidades_comorbilidad)){
			$datos[] = $Comorbilidades_comorbilidad;
		}
		else{
			$datos[] = [null];
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos los datos de la adyuvancia, de haber
		$base = 'Adyuvancias';
		$claves[] = 'tipo';
		if($Adyuvancias == "Si"){
			$datos[] = $Adyuvancias_tipo;
			$claves[] = 'fechaQuimioterapia';
			if(!empty($Adyuvancias_fechaQuimioterapia)){
				$datos[] = $Adyuvancias_fechaQuimioterapia;
			}
			else{
				$datos[] = null;
			}
		}
		else{ //en caso de no haber adyuvancia introducimos un aray con nulo para borrarla de estar editando
			$datos[] = [null];
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos los datos del cancer de recto, de haber
		if($CanceresRecto == "Si"){
			$base = 'CanceresRecto';
			$claves[] = 'calidadMesorrecto';
			$datos[] = $CanceresRecto_calidadMesorrecto;
			$claves[] = 'mandard';
			$datos[] = $CanceresRecto_mandard;
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			unset($datos);
			unset($claves);
			//Introducimos las localizaciones de las estadificaciones del cancer de recto
			$base = 'Estadificaciones';
			$claves[] = 'localizacion';
			if(!empty($CanceresRecto_Estadificaciones_localizacion)){
				$datos[] = $CanceresRecto_Estadificaciones_localizacion;
			}
			else{
				$datos[] = [null];
			}
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			unset($datos);
			unset($claves);
		}
		else{ //En caso contrario borramos el cancer de recto (o no lo insertamos, simplemente). En este caso empezamos por Estadificaciones para no borrar el hijo sin borrar el padre.
			$base = 'Estadificaciones';
			$claves[] = 'nada';
			$datos[] = [null];
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			$base = 'CanceresRecto';
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			unset($datos);
			unset($claves);
		}
		//Introducimos la metástasis hepática con sus datos de cirugía, de haber
		if($MetastasisHepaticas == "Si, con cirugía"){
			$claves = array();
			$datos = array();
			$base = 'MetastasisHepaticas';
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			// Introducimos los datos de la cirugía
			$base = 'CirugiasHepaticas';
			$claves[] = 'fechaCirugia';
			$datos[] = $MetastasisHepaticas_CirugiasHepaticas_fechaCirugia;
			$claves[] = 'tipoCirugia';
			$datos[] = $MetastasisHepaticas_CirugiasHepaticas_tipoCirugia;
			$claves[] = 'tecnicaCirugia';
			$datos[] = $MetastasisHepaticas_CirugiasHepaticas_tecnicaCirugia;
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			unset($datos);
			unset($claves);
		}
		else if($MetastasisHepaticas == "Si, sin cirugía"){
			$claves = array();
			$datos = array();
			$base = 'MetastasisHepaticas';
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			$base = 'CirugiasHepaticas';
			$claves[] = 'nada';
			$datos[] = [null];
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			unset($datos);
			unset($claves);
		}
		else{ //En caso contrario borramos la metástasis hepática y sus datos de cirugía, de haber
			$base = 'CirugiasHepaticas';
			$claves[] = 'nada';
			$datos[] = [null];
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			$base = 'MetastasisHepaticas';
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			unset($datos);
			unset($claves);
		}
		//Introducimos la metástasis pulmonar con sus datos de cirugía, de haber
		if($MetastasisPulmonares == "Si, con cirugía"){
			$claves = array();
			$datos = array();
			$base = 'MetastasisPulmonares';
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			// Introducimos los datos de la cirugía
			$base = 'CirugiasPulmonares';
			$claves[] = 'fechaCirugia';
			$datos[] = $MetastasisPulmonares_CirugiasPulmonares_fechaCirugia;
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			unset($datos);
			unset($claves);
		}
		else if($MetastasisPulmonares == "Si, sin cirugía"){
			$claves = array();
			$datos = array();
			$base = 'MetastasisPulmonares';
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			$base = 'CirugiasPulmonares';
			$claves[] = 'nada';
			$datos[] = [null];
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			unset($datos);
			unset($claves);
		}
		else{ //En caso contrario borramos la metástasis pulmonar y sus datos de cirugía, de haber
			$base = 'CirugiasPulmonares';
			$claves[] = 'nada';
			$datos[] = [null];
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			$base = 'MetastasisPulmonares';
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			unset($datos);
			unset($claves);
		}
	}
	header("location: ../consultaDiagnostico.php");
?>