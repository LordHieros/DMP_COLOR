<?php
	//Para parsear find \t'(.*)'; replace \tif\(isset\($_POST\['\1'\]\)\){\n\t\t$\1 = $_POST\['\1'\];\n\t}
	//var_dump($_POST);
	include('header.php');
	include('use_database.php');
	comprueba_intervencion();
	
	//Inicializar variables de sesion
	$nasi=$_SESSION[CampoSession::NASI];
	$fechaDiagnostico = $_SESSION[CampoSession::FECHA_DIAGNOSTICO];
	$fechaIntervencion = $_SESSION[CampoSession::FECHA_INTERVENCION];
	require 'Conexion.php';
	
	//Caso de querer cerrar/abrir expediente
	if(isset($_POST["cerrar"])){
		$stmt = Conexion::getpdo()->prepare('UPDATE Intervenciones SET cerrado = :cerrado WHERE Filiaciones_NASI = :nasi AND Diagnosticos_fecha = :fechaDiagnostico AND fecha = :fechaIntervencion;');
		if($_POST["cerrar"]=='true')
			if(verifica_intervencion()=='OK'){
				$stmt->execute(['cerrado' => 1, 'nasi' => $nasi, 'fechaDiagnostico' => $fechaDiagnostico, 'fechaIntervencion' => $fechaIntervencion]);
			}
			else{
				//print error
			}
		else if($_POST["cerrar"]=='false' && $_SESSION[CampoSession::USUARIO])
			$stmt->execute(['cerrado' => 0, 'nasi' => $nasi, 'fechaDiagnostico' => $fechaDiagnostico, 'fechaIntervencion' => $fechaIntervencion]);
		else{
			$_SESSION[CampoSession::ERROR] = 'Error de acceso de datos';
			header("location: ../panel.php");
			die;
		}
	}	
	else{
		//Inicializar variables para introducir/editar
		if(isset($_POST['duracion'])){
			$duracion = $_POST['duracion'];
		}
		if(isset($_POST['ListaEspera'])){
			$ListaEspera = $_POST['ListaEspera'];
		}
		if(isset($_POST['fechaListaEspera'])){
			$fechaListaEspera = $_POST['fechaListaEspera'];
		}
		if(isset($_POST['fechaIngreso'])){
			$fechaIngreso = $_POST['fechaIngreso'];
		}
		if(isset($_POST['fechaAlta'])){
			$fechaAlta = $_POST['fechaAlta'];
		}
		if(isset($_POST['hayNeoadyuvancia'])){
			$hayNeoadyuvancia = $_POST['hayNeoadyuvancia'];
		}
		if(isset($_POST['neoadyuvancia'])){
			$neoadyuvancia = $_POST['neoadyuvancia'];
		}
		if(isset($_POST['caracter'])){
			$caracter = $_POST['caracter'];
		}
		if(isset($_POST['Urgentes_Motivos_motivo'])){
			$Urgentes_Motivos_motivo = $_POST['Urgentes_Motivos_motivo'];
		}
		if(isset($_POST['Urgentes_hemodinamicamenteEstable'])){
			$Urgentes_hemodinamicamenteEstable = $_POST['Urgentes_hemodinamicamenteEstable'];
		}
		if(isset($_POST['Urgentes_insuficienciaRenal'])){
			$Urgentes_insuficienciaRenal = $_POST['Urgentes_insuficienciaRenal'];
		}
		if(isset($_POST['Urgentes_peritonitis'])){
			$Urgentes_peritonitis = $_POST['Urgentes_peritonitis'];
		}
		if(isset($_POST['codigoCirujano'])){
			$codigoCirujano = $_POST['codigoCirujano'];
		}
		if(isset($_POST['Accesos_acceso'])){
			$Accesos_acceso = $_POST['Accesos_acceso'];
		}
		if(isset($_POST['TiposIntervencion_tipo'])){
			$TiposIntervencion_tipo = $_POST['TiposIntervencion_tipo'];
		}
		if(isset($_POST['tipoReseccion'])){
			$tipoReseccion = $_POST['tipoReseccion'];
		}
		if(isset($_POST['margenAfecto'])){
			$margenAfecto = $_POST['margenAfecto'];
		}
		if(isset($_POST['excision'])){
			$excision = $_POST['excision'];
		}
		if(isset($_POST['tipoExcision'])){
			$tipoExcision = $_POST['tipoExcision'];
		}
		if(isset($_POST['anastomosis'])){
			$Anastomosis = $_POST['anastomosis'];
		}
		if(isset($_POST['Anastomosis_tipo'])){
			$Anastomosis_tipo = $_POST['Anastomosis_tipo'];
		}
		if(isset($_POST['Anastomosis_tecnica'])){
			$Anastomosis_tecnica = $_POST['Anastomosis_tecnica'];
		}
		if(isset($_POST['Anastomosis_modalidad'])){
			$Anastomosis_modalidad = $_POST['Anastomosis_modalidad'];
		}
		if(isset($_POST['rio'])){
			$rio = $_POST['rio'];
		}
		if(isset($_POST['hayEstoma'])){
			$hayEstoma = $_POST['hayEstoma'];
		}
		if(isset($_POST['estoma'])){
			$estoma = $_POST['estoma'];
		}
		if(isset($_POST['ComplicacionesIntraoperatorias'])){
			$ComplicacionesIntraoperatorias = $_POST['ComplicacionesIntraoperatorias'];
		}
		if(isset($_POST['ComplicacionesIntraoperatorias_complicacion'])){
			$ComplicacionesIntraoperatorias_complicacion = $_POST['ComplicacionesIntraoperatorias_complicacion'];
		}
		if(isset($_POST['IntervencionesAsociadas'])){
			$IntervencionesAsociadas = $_POST['IntervencionesAsociadas'];
		}
		if(isset($_POST['IntervencionesAsociadas_intervencion'])){
			$IntervencionesAsociadas_intervencion = $_POST['IntervencionesAsociadas_intervencion'];
		}
		if(isset($_POST['Protesis'])){
			$Protesis = $_POST['Protesis'];
		}
		if(isset($_POST['Protesis_fecha'])){
			$Protesis_fecha = $_POST['Protesis_fecha'];
		}
		if(isset($_POST['Protesis_complicacion'])){
			$Protesis_complicacion = $_POST['Protesis_complicacion'];
		}
		if(isset($_POST['deshicenciaAnastomotica'])){
			$deshicenciaAnastomotica = $_POST['deshicenciaAnastomotica'];
		}
		if(isset($_POST['ComplicacionesCirugia'])){
			$ComplicacionesCirugia = $_POST['ComplicacionesCirugia'];
		}
		if(isset($_POST['ComplicacionesCirugia_complicacion'])){
			$ComplicacionesCirugia_complicacion = $_POST['ComplicacionesCirugia_complicacion'];
		}
		if(isset($_POST['ComplicacionesMedicas'])){
			$ComplicacionesMedicas = $_POST['ComplicacionesMedicas'];
		}
		if(isset($_POST['ComplicacionesMedicas_complicacion'])){
			$ComplicacionesMedicas_complicacion = $_POST['ComplicacionesMedicas_complicacion'];
		}
		if(isset($_POST['Transfusiones'])){
			$Transfusiones = $_POST['Transfusiones'];
		}
		if(isset($_POST['Transfusiones_momento'])){
			$Transfusiones_momento = $_POST['Transfusiones_momento'];
		}
		//Comprobamos si se está editando
		$editar = false;
		if(isset($_POST["editar"])){
			if($_POST["editar"] == 'true'){
				$editar = true;
			}
		}
		//Empezamos con la introduccion de datos
		$base = 'DatosIntervencion';
		$claves_principal = ['Filiaciones_NASI', 'Diagnosticos_fecha', 'Intervenciones_fecha'];
		$datos_principal = [$nasi, $fechaDiagnostico, $fechaIntervencion];
		//Empezamos por los valores obligatorios
		$claves[] = 'duracion';
		$datos[] = $duracion;
		$claves[] = 'fechaIngreso';
		$datos[] = $fechaIngreso;
		$claves[] = 'fechaAlta';
		$datos[] = $fechaAlta;
		$claves[] = 'codigoCirujano';
		$datos[] = $codigoCirujano;
		$claves[] = 'tipoReseccion';
		$datos[] = $tipoReseccion;
		$claves[] = 'rio';
		$datos[] = $rio;
		$claves[] = 'deshicenciaAnastomotica';
		if($deshicenciaAnastomotica == 'Si'){
			$datos[] = 1;
		}
		else{
			$datos[] = 0;
		}
		$claves[] = 'fechaListaEspera';
		if($ListaEspera == 'Si'){ //Introducimos, de existir, la fecha de entrada en lsita de espera
			$datos[] = $fechaListaEspera;
		}
		else{
			$datos[] = null;
		}
		$claves[] = 'neoadyuvancia';
		if($hayNeoadyuvancia == 'Si'){//Introducimos, de existir, la neoadyuvancia
			$datos[] = $neoadyuvancia;
		}
		else{
			$datos[] = null;
		}
		$claves[] = 'margenAfecto';
		if($tipoReseccion == 'R2'){//Introducimos, de existir, el margen afecto (en caso de reseccion R")
			$datos[] = $margenAfecto;
		}
		else{
			$datos[] = null;
		}
		$claves[] = 'tipoExcision';
		if($excision == 'Si'){//Introducimos, de existir, el tipo de excisión
			$datos[] = $tipoExcision;
		}
		else{
			$datos[] = null;
		}
		$claves[] = 'estoma';
		if($hayEstoma == 'Si'){//Introducimos, de existir, el estoma
			$datos[] = $estoma;
		}
		else{
			$datos[] = null;
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos las intervenciones asociadas
		$base = 'IntervencionesAsociadas';
		$claves[] = 'intervencion';
		if(!empty($IntervencionesAsociadas_intervencion)){
			$datos[] = $IntervencionesAsociadas_intervencion;
		}
		else{
			$datos[] = [null];
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos las complicaciones intraoperatorias
		$base = 'ComplicacionesIntraoperatorias';
		$claves[] = 'complicacion';
		if(!empty($ComplicacionesIntraoperatorias_complicacion)){
			$datos[] = $ComplicacionesIntraoperatorias_complicacion;
		}
		else{
			$datos[] = [null];
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos las complicaciones relacionadas con la cirugía
		$base = 'ComplicacionesCirugia';
		$claves[] = 'complicacion';
		if(!empty($ComplicacionesCirugia_complicacion)){
			$datos[] = $ComplicacionesCirugia_complicacion;
		}
		else{
			$datos[] = [null];
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos las complicaciones médicas
		$base = 'ComplicacionesMedicas';
		$claves[] = 'complicacion';
		if(!empty($ComplicacionesMedicas_complicacion)){
			$datos[] = $ComplicacionesMedicas_complicacion;
		}
		else{
			$datos[] = [null];
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos los accesos
		$base = 'Accesos';
		$claves[] = 'acceso';
		if(!empty($Accesos_acceso)){
			$datos[] = $Accesos_acceso;
		}
		else{
			$datos[] = [null];
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos tipos de intervención
		$base = 'TiposIntervencion';
		$claves[] = 'tipo';
		if(!empty($TiposIntervencion_tipo)){
			$datos[] = $TiposIntervencion_tipo;
		}
		else{
			$datos[] = [null];
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos las transfusiones
		$base = 'Transfusiones';
		$claves[] = 'momento';
		if(!empty($Transfusiones_momento)){
			$datos[] = $Transfusiones_momento;
		}
		else{
			$datos[] = [null];
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos los datos de la prótesis, de haber
		$base = 'Protesis';
		$claves[] = 'fecha';
		if($Protesis == 'Si'){
			$datos[] = $Protesis_fecha;
			$claves[] = 'complicacion';
			if($Protesis_complicacion == 'Si'){
				$datos[] = 1;
			}
			else{
				$datos[] = 0;
			}
		}
		else{ //en caso de no haber protesis introducimos un aray con nulo para borrarla de estar editando
			$datos[] = [null];
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos los datos de la anastomosis, de haber
		$base = 'Anastomosis';
		$claves[] = 'tipo';
		if($Anastomosis == 'Si'){
			$datos[] = $Anastomosis_tipo;
			$claves[] = 'modalidad';
			$datos[] = $Anastomosis_modalidad;
			$claves[] = 'tecnica';
			$datos[] = $Anastomosis_tecnica;
		}
		else{ //en caso de no haber anastomosis introducimos un aray con nulo para borrarla de estar editando
			$datos[] = [null];
		}
		use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
		unset($datos);
		unset($claves);
		//Introducimos los datos de la intervención urgente, de serlo
		if($caracter == "Urgente"){
			$base = 'Urgentes';
			$claves[] = 'hemodinamicamenteEstable';
			if($Urgentes_hemodinamicamenteEstable == 'Si'){
				$datos[] = 1;
			}
			else{
				$datos[] = 0;
			}
			$claves[] = 'insuficienciaRenal';
			if($Urgentes_insuficienciaRenal == 'Si'){
				$datos[] = 1;
			}
			else{
				$datos[] = 0;
			}
			$claves[] = 'peritonitis';
			if($Urgentes_peritonitis == "No"){
				$datos[] = null;
			}
			else{
				$datos[] = $Urgentes_peritonitis;
			}
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			unset($datos);
			unset($claves);
			//Introducimos las localizaciones de las estadificaciones del cancer de recto
			$base = 'Motivos';
			$claves[] = 'motivo';
			if(!empty($Urgentes_Motivos_motivo)){
				$datos[] = $Urgentes_Motivos_motivo;
			}
			else{
				$datos[] = [null];
			}
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			unset($datos);
			unset($claves);
		}
		else{ //En caso contrario borramos los datos de "urgente" (o no los insertamos, simplemente). En este caso empezamos por motivos para no borrar el hijo sin borrar el padre.
			$base = 'Motivos';
			$claves[] = 'nada';
			$datos[] = [null];
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			$base = 'Urgentes';
			use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal);
			unset($datos);
			unset($claves);
		}
	}
	header("location: ../consulta_intervencion.php");
?>