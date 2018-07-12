<?php
	include('php/header.php');
	
	comprueba_intervencion();
	
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Datos de intervención del paciente';
	//Creamos el cuerpo, imprimiendo y borrando mensaje de error de haber
	if(isset($_SESSION[CampoSession::ERROR])){
		$html['body'] = '<h4 class="error">' . $_SESSION[CampoSession::ERROR] . '</h4>';
		unset($_SESSION[CampoSession::ERROR]);
	}
	else{
		$html['body'] = '';
	}
	//Buscamos el paciente en la base de datos
	require 'php/Conexion.php';
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM Intervenciones WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND fecha=:fechaIntervencion;');
	$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fechaIntervencion' => $_SESSION[CampoSession::FECHA_INTERVENCION]]);
	$resultado = $stmt->fetchAll();
	//Este valor nos permite saber si existen datos del paciente o no, lo que nos permitirá saber que imprimir, que selects hacer...
	$hay_Datos = (count($resultado)>0);
	if($hay_Datos){
		//Este valor nos permite saber si la intervención es editable
		$editable = ($resultado[0]["cerrado"]==0);
		$stmt = Conexion::getpdo()->prepare('SELECT * FROM ComplicacionesIntraoperatorias WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND Intervenciones_fecha=:fechaIntervencion;');
		$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fechaIntervencion' => $_SESSION[CampoSession::FECHA_INTERVENCION]]);
		$resultado_ComplicacionesIntraoperatorias = $stmt->fetchAll();
		$stmt = Conexion::getpdo()->prepare('SELECT * FROM IntervencionesAsociadas WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND Intervenciones_fecha=:fechaIntervencion;');
		$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fechaIntervencion' => $_SESSION[CampoSession::FECHA_INTERVENCION]]);
		$resultado_IntervencionesAsociadas = $stmt->fetchAll();
		$stmt = Conexion::getpdo()->prepare('SELECT * FROM Accesos WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND Intervenciones_fecha=:fechaIntervencion;');
		$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fechaIntervencion' => $_SESSION[CampoSession::FECHA_INTERVENCION]]);
		$resultado_Accesos = $stmt->fetchAll();
		$stmt = Conexion::getpdo()->prepare('SELECT * FROM Urgentes WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND Intervenciones_fecha=:fechaIntervencion;');
		$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fechaIntervencion' => $_SESSION[CampoSession::FECHA_INTERVENCION]]);
		$resultado_Urgentes = $stmt->fetchAll();
		$stmt = Conexion::getpdo()->prepare('SELECT * FROM Motivos WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND Intervenciones_fecha=:fechaIntervencion;');
		$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fechaIntervencion' => $_SESSION[CampoSession::FECHA_INTERVENCION]]);
		$resultado_Urgentes_Motivos = $stmt->fetchAll();
		$stmt = Conexion::getpdo()->prepare('SELECT * FROM Transfusiones WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND Intervenciones_fecha=:fechaIntervencion;');
		$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fechaIntervencion' => $_SESSION[CampoSession::FECHA_INTERVENCION]]);
		$resultado_Transfusiones = $stmt->fetchAll();
		$stmt = Conexion::getpdo()->prepare('SELECT * FROM ComplicacionesCirugia WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND Intervenciones_fecha=:fechaIntervencion;');
		$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fechaIntervencion' => $_SESSION[CampoSession::FECHA_INTERVENCION]]);
		$resultado_ComplicacionesCirugia = $stmt->fetchAll();
		$stmt = Conexion::getpdo()->prepare('SELECT * FROM ComplicacionesMedicas WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND Intervenciones_fecha=:fechaIntervencion;');
		$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fechaIntervencion' => $_SESSION[CampoSession::FECHA_INTERVENCION]]);
		$resultado_ComplicacionesMedicas = $stmt->fetchAll();
		$stmt = Conexion::getpdo()->prepare('SELECT * FROM Protesis WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND Intervenciones_fecha=:fechaIntervencion;');
		$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fechaIntervencion' => $_SESSION[CampoSession::FECHA_INTERVENCION]]);
		$resultado_Protesis = $stmt->fetchAll();
		$stmt = Conexion::getpdo()->prepare('SELECT * FROM Anastomosis WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND Intervenciones_fecha=:fechaIntervencion;');
		$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fechaIntervencion' => $_SESSION[CampoSession::FECHA_INTERVENCION]]);
		$resultado_Anastomosis = $stmt->fetchAll();
		$stmt = Conexion::getpdo()->prepare('SELECT * FROM TiposIntervencion WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND Intervenciones_fecha=:fechaIntervencion;');
		$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fechaIntervencion' => $_SESSION[CampoSession::FECHA_INTERVENCION]]);
		$resultado_TiposIntervencion = $stmt->fetchAll();
	}
	$consultas = array();
	//NASI
	$datos['nombre'] = 'NASI';
	$datos['resultado'] = $_SESSION[CampoSession::NASI];
	$consultas[] = $datos;
	unset($datos);
	//Fecha de diagnóstico
	$datos['nombre'] = 'Fecha de diagnóstico';
	$datos['resultado'] = $_SESSION[CampoSession::FECHA_DIAGNOSTICO];
	$consultas[] = $datos;
	unset($datos);
	//Fecha de intervención
	$datos['nombre'] = 'Fecha de intervención';
	$datos['resultado'] = $_SESSION[CampoSession::FECHA_INTERVENCION];
	$consultas[] = $datos;
	unset($datos);
	if($hay_Datos){
		//Duración de la intervención
		$datos['nombre'] = 'Duración (minutos)';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'duracion';
		$consultas[] = $datos;
		unset($datos);
		//Fecha de ingreso
		$datos['nombre'] = 'Fecha de ingreso';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'fechaIngreso';
		$consultas[] = $datos;
		unset($datos);
		//Fecha de alta
		$datos['nombre'] = 'Fecha de alta';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'fechaAlta';
		$consultas[] = $datos;
		unset($datos);
		//Dias de ingreso
		$tiempo = date_diff(date_create($resultado[0]['fechaAlta']), date_create($resultado[0]['fechaIngreso']), false);
		$datos['resultado'] = 'Días de ingreso: ' . $tiempo->days;
		$consultas[] = $datos;
		unset($datos);
		//Codigo cirujano
		$datos['nombre'] = 'Código del cirujano que realizó la intervención';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'codigoCirujano';
		$consultas[] = $datos;
		unset($datos);
		//Urgente
		if(!empty($resultado_Urgentes)){
			$datos['resultado'] = '<span class="negrita">Intervención Urgente</span>';
			$consultas[] = $datos;
			unset($datos);
			if($resultado_Urgentes[0]['hemodinamicamenteEstable'] == 1){
				$datos['resultado'] = 'Hemodinámicamente estable';
			}
			else{
				$datos['resultado'] = 'No hemodinámicamente estable';
			}
			$consultas[] = $datos;
			unset($datos);
			if($resultado_Urgentes[0]['insuficienciaRenal'] == 1){
				$datos['resultado'] = 'Con insuficiencia renal';
			}
			else{
				$datos['resultado'] = 'Sin insuficiencia renal';
			}
			$consultas[] = $datos;
			unset($datos);
			$datos['nombre'] = 'Peritonitis';
			$datos['resultado'] = $resultado_Urgentes;
			$datos['clave'] = 'peritonitis';
			$datos['vacio'] = 'Sin peritonitis';
			$consultas[] = $datos;
			unset($datos);
			$datos['nombre'] = 'Motivos para la intervención urgente';
			$datos['resultado'] = $resultado_Urgentes_Motivos;
			$datos['clave'] = 'motivo';
			$datos['vacio'] = 'no se han especificado motivos para la intervención urgente';
		}
		else{
			$datos['resultado'] = 'Se trata de una intervención programada';
		}
		$consultas[] = $datos;
		unset($datos);
		//Tipo resección
		$datos['nombre'] = 'Tipo de resección';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'tipoReseccion';
		$consultas[] = $datos;
		unset($datos);
		//Margen afecto (solo si tipo de resección R2
		if($resultado[0]['tipoReseccion'] == "R2"){
			$datos['nombre'] = 'Margen Afecto';
			$datos['resultado'] = $resultado;
			$datos['clave'] = 'margenAfecto';
			$consultas[] = $datos;
			unset($datos);
		}
		//RIO
		$datos['nombre'] = 'RIO';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'rio';
		$consultas[] = $datos;
		unset($datos);
		//Deshicencia anastomótica
		if($resultado[0]['deshicenciaAnastomotica'] == 1){
			$datos['resultado'] = '<span class="negrita">Deshicencia anastomótica</span>: Si';
		}
		else{
			$datos['resultado'] = 'Sin deshicencia anastomótica';
		}
		$consultas[] = $datos;
		unset($datos);
		//Fecha lista de espera
		$datos['nombre'] = 'Fecha de entrada en lista de espera';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'fechaListaEspera';
		$datos['vacio'] = 'No se ha puesto en lista de espera';
		$consultas[] = $datos;
		unset($datos);
		//Neoadyuvancia
		$datos['nombre'] = 'Tipo de neoadyuvancia';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'neoadyuvancia';
		$datos['vacio'] = 'No hay neoadyuvancia';
		$consultas[] = $datos;
		unset($datos);
		//Tipo excisión
		$datos['nombre'] = 'Tipo de excisión mesorrectal';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'tipoExcisión';
		$datos['vacio'] = 'No hay excisión mesorrectal';
		$consultas[] = $datos;
		unset($datos);
		//Estoma
		$datos['nombre'] = 'Tipo de estoma';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'estoma';
		$datos['vacio'] = 'No hay estoma';
		$consultas[] = $datos;
		unset($datos);
		//Complicaciones Intraoperatorias
		$datos['nombre'] = 'Complicaciones intraoperatorias';
		$datos['resultado'] = $resultado_ComplicacionesIntraoperatorias;
		$datos['clave'] = 'complicacion';
		$datos['vacio'] = 'No ha habido complicaciones intraoperatorias';
		$consultas[] = $datos;
		unset($datos);
		//Complicaciones Médicas
		$datos['nombre'] = 'Complicaciones médicas';
		$datos['resultado'] = $resultado_ComplicacionesMedicas;
		$datos['clave'] = 'complicacion';
		$datos['vacio'] = 'No ha habido complicaciones médicas';
		$consultas[] = $datos;
		unset($datos);
		//Complicaciones relacionadas con la cirugía
		$datos['nombre'] = 'Complicaciones relacionadas con la cirugía';
		$datos['resultado'] = $resultado_ComplicacionesCirugia;
		$datos['clave'] = 'complicacion';
		$datos['vacio'] = 'No ha habido complicaciones relacionadas con la cirugía';
		$consultas[] = $datos;
		unset($datos);
		//Accesos
		$datos['nombre'] = 'Tipos de acceso';
		$datos['resultado'] = $resultado_Accesos;
		$datos['clave'] = 'acceso';
		$datos['vacio'] = 'No se han especificado tipos de acceso';
		$consultas[] = $datos;
		unset($datos);
		//Tipos de intervenciones realizadas
		$datos['nombre'] = 'Tipos de intervenciones realizadas';
		$datos['resultado'] = $resultado_TiposIntervencion;
		$datos['clave'] = 'tipo';
		$datos['vacio'] = 'No se ha especificado el tipo de intervenciones realizadas';
		$consultas[] = $datos;
		unset($datos);
		//Transfusiones
		$datos['nombre'] = 'Transfusiones';
		$datos['resultado'] = $resultado_TiposIntervencion;
		$datos['clave'] = 'tipo';
		$datos['vacio'] = 'No se han realizado transfusiones';
		$consultas[] = $datos;
		unset($datos);
		//Intervenciones asociadas
		$datos['nombre'] = 'Intervenciones asociadas';
		$datos['resultado'] = $resultado_IntervencionesAsociadas;
		$datos['clave'] = 'intervencion';
		$datos['vacio'] = 'No se han realizado intervenciones asociadas';
		$consultas[] = $datos;
		unset($datos);
		//Protesis
		if(!empty($resultado_Protesis)){
			$datos['nombre'] = 'Fecha de instalación de la prótesis STENT';
			$datos['resultado'] = $resultado_Protesis;
			$datos['clave'] = 'fecha';
			$consultas[] = $datos;
			unset($datos);
			if($resultado_Protesis[0]['complicacion'] == 1){
				$datos['resultado'] = 'Ha habido complicaciones relacionadas con la prótesis';
			}
			else{
				$datos['resultado'] = 'Sin complicaciones relacionadas con la prótesis';
			}
		}
		else{
			$datos['resultado'] = 'Sin prótesis STENT';
		}
		$consultas[] = $datos;
		unset($datos);
		//Anastomosis
		if(!empty($resultado_Anastomosis)){
			$datos['nombre'] = 'Tipo de anastomosis';
			$datos['resultado'] = $resultado_Anastomosis;
			$datos['clave'] = 'tipo';
			$consultas[] = $datos;
			unset($datos);
			$datos['nombre'] = 'Técnica de anastomosis';
			$datos['resultado'] = $resultado_Anastomosis;
			$datos['clave'] = 'tecnica';
			$consultas[] = $datos;
			unset($datos);
			$datos['nombre'] = 'Modalidad de anastomosis';
			$datos['resultado'] = $resultado_Anastomosis;
			$datos['clave'] = 'modalidad';
		}
		else{
			$datos['resultado'] = 'Sin anastomosis';
		}
		$consultas[] = $datos;
		unset($datos);
	}
	$html['body'] = $html['body'] . make_consultas($consultas);
	//En caso de que el expediente esté cerrado 
	if(!$editable){
		$html['body'] = $html['body'] . '<p> Estos datos no pueden ser editados sin permiso de un administrador </p>';
	}
	//Crear las opciones
	$datos[0]['tipo'] = 'redirección';
	$datos[0]['objetivo'] = 'consultaDiagnostico.php';
	$datos[0]['nombre'] = 'Volver';
	if($editable){ //Si es editable se puede editar o cerrar el expediente
		$datos[1]['tipo'] = 'redirección';
		$datos[1]['objetivo'] = 'intervencion.php';
		$datos[1]['nombre'] = 'Editar';
		$datos[2]['tipo'] = 'post';
		$datos[2]['objetivo'] = 'php/intervencion_datos.php';
		$datos[2]['valor'] = 'true';
		$datos[2]['mensaje'] = 'Cerrar intervención';
		$datos[2]['nombre'] = 'cerrar';
	}
	else if($_SESSION[CampoSession::ADMINISTRADOR]){ //Si no es editable pero se es administrador se puede abrir el expediente
		$datos[1]['tipo'] = 'post';
		$datos[1]['objetivo'] = 'php/intervencion_datos.php';
		$datos[1]['valor'] = 'false';
		$datos[1]['mensaje'] = 'Habilitar edición de intervención';
		$datos[1]['nombre'] = 'cerrar';
	}
	$html['body'] = $html['body'] . make_navbar($datos);	
	unset($datos);
	echo make_html($html);
?>
