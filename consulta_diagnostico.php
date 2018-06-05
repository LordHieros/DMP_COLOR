<?php
	include('php/header.php');
	
	comprueba_diagnostico();
	
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	$html['titulo'] = 'Datos de diagnóstico del paciente';
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
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM Diagnosticos WHERE Filiaciones_NASI=:nasi AND fecha=:fecha;');
	$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO]]);
	$resultado = $stmt->fetchAll();
	//Este valor nos permite saler si la intervención es editable
	$editable = ($resultado[0]["cerrado"]==0);
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM CausasIntervencion WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fecha;');
	$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO]]);
	$resultado_CausasIntervencion = $stmt->fetchAll();
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM Comorbilidades WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fecha;');
	$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO]]);
	$resultado_Comorbilidades = $stmt->fetchAll();
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM LocalizacionesCancer WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fecha;');
	$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO]]);
	$resultado_LocalizacionesCancer = $stmt->fetchAll();
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM TumoresSincronicos WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fecha;');
	$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO]]);
	$resultado_TumoresSincronicos = $stmt->fetchAll();
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM Adyuvancias WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fecha;');
	$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO]]);
	$resultado_Adyuvancias = $stmt->fetchAll();
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM MetastasisHepaticas WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fecha;');
	$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO]]);
	$resultado_MetastasisHepaticas = $stmt->fetchAll();
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM CirugiasHepaticas WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fecha;');
	$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO]]);
	$resultado_MetastasisHepaticas_CirugiasHepaticas = $stmt->fetchAll();
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM MetastasisPulmonares WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fecha;');
	$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO]]);
	$resultado_MetastasisPulmonares = $stmt->fetchAll();
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM CirugiasPulmonares WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fecha;');
	$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO]]);
	$resultado_MetastasisPulmonares_CirugiasPulmonares = $stmt->fetchAll();
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM Metastasis WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fecha;');
	$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO]]);
	$resultado_Metastasis = $stmt->fetchAll();
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM FactoresRiesgo WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fecha;');
	$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO]]);
	$resultado_FactoresRiesgo = $stmt->fetchAll();
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM CanceresRecto WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fecha;');
	$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO]]);
	$resultado_CanceresRecto = $stmt->fetchAll();	
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM Estadificaciones WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fecha;');
	$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO]]);
	$resultado_CanceresRecto_Estadificaciones = $stmt->fetchAll();
	if (count($resultado) > 0) { //Comprobar si existe paciente
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
		//Causas intervención
		$datos['nombre'] = 'Causas para la intervención';
		$datos['resultado'] = $resultado_CausasIntervencion;
		$datos['clave'] = 'causa';
		$datos['vacio'] = 'No se han especificado causas de intervención';
		$consultas[] = $datos;
		unset($datos);
		//Comorbilidades
		$datos['nombre'] = 'Comorbilidades';
		$datos['resultado'] = $resultado_Comorbilidades;
		$datos['clave'] = 'comorbilidad';
		$datos['vacio'] = 'No se han especificado comorbilidades';
		$consultas[] = $datos;
		unset($datos);
		//Patología
		$datos['nombre'] = 'Patología';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'patologia';
		$consultas[] = $datos;
		unset($datos);
		//Endoscopia diagnostica + fecha
		$datos['nombre'] = 'Fecha endoscopia';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'fechaEndoscopia';
		$datos['vacio'] = 'No se ha realizado endoscopia';
		$consultas[] = $datos;
		unset($datos);
		//Biopsia + fecha
		$datos['nombre'] = 'Fecha biopsia';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'fechaBiopsia';
		$datos['vacio'] = 'No se ha realizado biopsia';
		$consultas[] = $datos;
		unset($datos);
		//Presentación en comité CCR + fecha
		$datos['nombre'] = 'Fecha presentacion en comité CCR';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'fechaPresentacion';
		$datos['vacio'] = 'No se ha realizado una presentación en el comité CCR';
		$consultas[] = $datos;
		unset($datos);
		//Localización del cáncer
		$datos['nombre'] = 'Localización del cáncer';
		$datos['resultado'] = $resultado_LocalizacionesCancer;
		$datos['clave'] = 'localizacion';
		$datos['vacio'] = 'No se han especificado ninguna localización de cáncer';
		$consultas[] = $datos;
		unset($datos);
		//Tumores Sincrónicos
		$datos['nombre'] = 'Tumores sincrónicos';
		$datos['resultado'] = $resultado_TumoresSincronicos;
		$datos['clave'] = 'localizacion';
		$datos['vacio'] = 'No se han encontrado tumores sincrónicos';
		$consultas[] = $datos;
		unset($datos);
		//CEA preoperatorio
		$datos['nombre'] = 'CEA preoperatorio';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'CEA';
		$datos['vacio'] = 'No se ha especificado el CEA en preoperatorio';
		$consultas[] = $datos;
		unset($datos);
		//Hemoglobina preoperatoria
		$datos['nombre'] = 'Hemoglobina preoperatoria (en analítica del preoperatorio) (g/L)';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'hemoglobina';
		$datos['vacio'] = 'No se ha especificado el nivel de hemoglobina en la analítica del preoperatorio';
		$consultas[] = $datos;
		unset($datos);
		//Adyuvancia
		$datos['nombre'] = 'Tipo de adyuvancia';
		$datos['resultado'] = $resultado_Adyuvancias;
		$datos['clave'] = 'tipo';
		$datos['vacio'] = 'No se ha especificado tipo de adyuvancia';
		$consultas[] = $datos;
		unset($datos);
		$datos['nombre'] = 'Fecha quimioterapia';
		$datos['resultado'] = $resultado_Adyuvancias;
		$datos['clave'] = 'fechaQuimioterapia';
		$datos['vacio'] = 'No se ha realizado quimioterapia';
		$consultas[] = $datos;
		unset($datos);
		//Metástasis hepáticas
		if(!empty($resultado_MetastasisHepaticas)){
			if(!empty($resultado_MetastasisHepaticas_CirugiasHepaticas)){
				$datos['nombre'] = 'Tipo de cirugía de las metástasis hepáticas';
				$datos['resultado'] = $resultado_MetastasisHepaticas_CirugiasHepaticas;
				$datos['clave'] = 'tipoCirugia';
				$datos['vacio'] = 'Se ha realizado cirugía de las metástasis hepáticas, pero no se ha especificado el tipo';
				$consultas[] = $datos;
				unset($datos);
				$datos['nombre'] = 'Técnica de cirugía de las metástasis hepáticas';
				$datos['resultado'] = $resultado_MetastasisHepaticas_CirugiasHepaticas;
				$datos['clave'] = 'tecnicaCirugia';
				$datos['vacio'] = 'Se ha realizado cirugía de las metástasis hepáticas, pero no se ha especificado la técnica';
				$consultas[] = $datos;
				unset($datos);
				$datos['nombre'] = 'Fecha de cirugía de las metástasis hepáticas';
				$datos['resultado'] = $resultado_MetastasisHepaticas_CirugiasHepaticas;
				$datos['clave'] = 'fechaCirugia';
				$datos['vacio'] = 'Se ha realizado cirugía de las metástasis hepáticas, pero no se ha especificado la fecha';
			}
			else{
				$datos['resultado'] = 'Hay metástasis hepáticas, pero no se ha realizado cirugía';
			}
		}
		else{
			$datos['resultado'] = 'No se han encontrado metástasis hepáticas';
		}
		$consultas[] = $datos;
		unset($datos);
		//Metástasis pulmonares
		if(!empty($resultado_MetastasisPulmonares)){
			if(!empty($resultado_MetastasisPulmonares_CirugiasPulmonares)){
				$datos['nombre'] = 'Fecha de cirugía de las metástasis pulmonares';
				$datos['resultado'] = $resultado_MetastasisPulmonares_CirugiasPulmonares;
				$datos['clave'] = 'fechaCirugia';
				$datos['vacio'] = 'Se ha realizado cirugía de las metástasis pulmonares, pero no se ha especificado la fecha';
				$consultas[] = $datos;
				unset($datos);
			}
			else{
				$datos['resultado'] = 'Hay metástasis pulmonares, pero no se ha realizado cirugía';
				$consultas[] = $datos;
				unset($datos);
			}
		}
		else{
			$datos['resultado'] = 'No se han encontrado metástasis pulmonares';
			$consultas[] = $datos;
			unset($datos);
		}
		//Otras metástasis
		$datos['nombre'] = 'Localización de otras metástasis';
		$datos['resultado'] = $resultado_Metastasis;
		$datos['clave'] = 'localizacion';
		$datos['vacio'] = 'No hay otras metástasis';
		$consultas[] = $datos;
		unset($datos);
		//Histología
		$datos['nombre'] = 'Histología';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'histologia';
		$consultas[] = $datos;
		unset($datos);
		//TNM
		$datos['nombre'] = 'T';
		$datos['resultado'] = $resultado;
		$consultas[] = $datos;
		unset($datos);
		$datos['nombre'] = 'N';
		$datos['resultado'] = $resultado;
		$consultas[] = $datos;
		unset($datos);
		$datos['nombre'] = 'M';
		$datos['resultado'] = $resultado;
		$consultas[] = $datos;
		unset($datos);
		//Estadio
		$datos['nombre'] = 'Estadio';
		$datos['resultado'] = calculoTNM($resultado[0]['T'], $resultado[0]['N'], $resultado[0]['M']);
		$consultas[] = $datos;
		unset($datos);
		//Distancia al margen distal en mm
		$datos['nombre'] = 'Distancia al margen distal en mm';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'distanciaMargenDistal';
		$datos['vacio'] = 'No se ha especificada la distancia al margen distal';
		$consultas[] = $datos;
		unset($datos);
		//Grado de diferenciación
		$datos['nombre'] = 'Grado de diferenciación';
		$datos['resultado'] = $resultado;
		$datos['clave'] = 'gradoDiferenciacion';
		$consultas[] = $datos;
		unset($datos);
		//Factores de riesgo histológico
		$datos['nombre'] = 'Tipos de factores de riesgo';
		$datos['resultado'] = $resultado_FactoresRiesgo;
		$datos['clave'] = 'tipo';
		$datos['vacio'] = 'No hay factores de riesgo';
		$consultas[] = $datos;
		unset($datos);
		//Cancer de recto
		if(!empty($resultado_CanceresRecto)){
			$datos['nombre'] = 'Estadificaciones del cancer de recto';
			$datos['resultado'] = $resultado_CanceresRecto_Estadificaciones;
			$datos['clave'] = 'localizacion';
			$datos['vacio'] = 'El cancer de recto no presenta estadificaciones';
			$consultas[] = $datos;
			unset($datos);
			$datos['nombre'] = 'Mandard del cancer de recto';
			$datos['resultado'] = $resultado_CanceresRecto;
			$datos['clave'] = 'mandard';
			$datos['vacio'] = 'No hay información sobre el mandard del cancer de recto';
			$consultas[] = $datos;
			unset($datos);
			$datos['nombre'] = 'Calidad del mesorrecto';
			$datos['resultado'] = $resultado_CanceresRecto;
			$datos['clave'] = 'calidadMesorrecto';
			$datos['vacio'] = 'No hay información sobre la calidad del mesorrecto';
			$consultas[] = $datos;
			unset($datos);
		}
		else{
			$datos['resultado'] = 'No hay cancer de recto';
			$consultas[] = $datos;
			unset($datos);
		}
		$html['body'] = $html['body'] . make_consultas($consultas);
		//En caso de que el expediente esté cerrado 
		if(!$editable){
			$html['body'] = $html['body'] . '<p> Estos datos no pueden ser editados sin permiso de un administrador </p>';
		}
	}
	else{ //Si no hay datos de paciente
		$html['body'] = $html['body'] . '<p>No existe diagnóstico; contactar con administrador</p>';
	}
	//Mostrar intervenciones
	$stmt = Conexion::getpdo()->prepare('SELECT fecha FROM Intervenciones WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico;');
	$res=$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO]]);
	$datos['resultado'] = $stmt->fetchAll();
	$datos['titulo'] = 'Intervenciones:';
	$datos['clave'] = 'fecha';
	$datos['nombre'] = 'fechaIntervencion';
	$datos['destino'] = 'consulta_intervencion.php';
	$datos['vacio'] = 'No hay intervenciones programadas o realizadas para este diagnóstico';
	$html['body'] = $html['body'] . make_eleccion($datos);
	unset($datos);
	//Crear las opciones
	$i=0;
	$datos[$i]['tipo'] = 'redirección';
	$datos[$i]['objetivo'] = 'consulta_filiacion.php';
	$datos[$i]['nombre'] = 'Volver';
	$i++;
	if($editable){ //Si es editable se puede editar o cerrar el expediente
		$datos[$i]['tipo'] = 'redirección';
		$datos[$i]['objetivo'] = 'diagnostico.php';
		$datos[$i]['nombre'] = 'Editar';
		$i++;
		$datos[$i]['tipo'] = 'post';
		$datos[$i]['objetivo'] = 'php/diagnostico_datos.php';
		$datos[$i]['valor'] = 'true';
		$datos[$i]['mensaje'] = 'Cerrar diagnóstico';
		$datos[$i]['nombre'] = 'cerrar';
		$i++;
	}
	else if($_SESSION[CampoSession::ADMINISTRADOR]){ //Si no es editable pero se es administrador se puede abrir el expediente
		$datos[$i]['tipo'] = 'post';
		$datos[$i]['objetivo'] = 'php/diagnostico_datos.php';
		$datos[$i]['valor'] = 'false';
		$datos[$i]['mensaje'] = 'Habilitar edición de diagnóstico';
		$datos[$i]['nombre'] = 'cerrar';
		$i++;
	}
	$datos[$i]['tipo'] = 'redirección';
	$datos[$i]['objetivo'] = 'programar.php';
	$datos[$i]['nombre'] = 'Programar intervención';
	$i++;
	$datos[$i]['tipo'] = 'redirección';
	$datos[$i]['objetivo'] = 'consulta_seguimiento.php';
	$datos[$i]['nombre'] = 'Consultar seguimiento';
	$html['body'] = $html['body'] . make_navbar($datos);	
	unset($datos);
	echo make_html($html);
?>
