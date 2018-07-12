<?php
	include('php/header.php');
	include('php/make_form.php');
	
	comprueba_nasi();
	
	require 'php/Conexion.php';
	if(isset($_SESSION[CampoSession::FECHA_DIAGNOSTICO])){
		$stmt = Conexion::getpdo()->prepare('SELECT * FROM Diagnosticos WHERE Filiaciones_NASI=:nasi AND fecha=:fecha;');
		$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fecha' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO]]);
		$resultado = $stmt->fetchAll();
		if (count($resultado) == 0) { //Si se llega a un expediente inexistente se redirige a panel con un mensaje de error
			$_SESSION[CampoSession::ERROR] = 'Error de acceso de datos';
			header("location: panel.php");
			die;
		}
		else if($resultado[0]["cerrado"]==1){ //Si se llega a un expediente cerrado se redirige a la consulta 
			header("location: consultaDiagnostico.php");
			die;
		}
		else{
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
			$editar = true;
		}
	}
	else{
		$editar = false;
		$resultado = '';
	}
	//Añadimos opciones de cabecera
	$html['head'] = '<script src="./js/Mostrar.js"> </script>';
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	if($editar){
		$html['titulo'] = 'Editar datos de diagnóstico del paciente ' . $_SESSION[CampoSession::NASI];
	}
	else{
		$html['titulo'] = 'Crear datos de diagnóstico del paciente ' . $_SESSION[CampoSession::NASI];
	}
	//Creamos el cuerpo
	$html['body'] = '';
	//Introducimos los datos del formulario
	$form['action'] = 'php/diagnostico_datos.php';
	$form['legend'] = 'Diagnóstico';
	//EMPIEZAN LOS INPUTS DEL FORMULARIO
	//Crear el contenedor de los inputs
	$form['inputs'] = array();
	//Primer grupo de inputs: principal
	$datos['etiqueta'] = 'Principal';
	//Sexo
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'sexo';
	$nest['valores'] = ['Varón', 'Mujer', 'Otro'];
	$nest['required'] = true;
	$datos['nest'][] = $nest;
	unset($nest);
	//Edad
	$nest['tipo'] = 'number';
	$nest['nombre'] = 'edad';
	$nest['opt'] = 'min="0" max="200"';
	$nest['required'] = true;
	$datos['nest'][] = $nest;
	unset($nest);
	//Talla
	$nest['tipo'] = 'number';
	$nest['etiqueta'] = 'Talla (cm)';
	$nest['nombre'] = 'talla';
	$nest['opt'] = 'min="10" max="300"';
	$nest['required'] = true;
	$datos['nest'][] = $nest;
	unset($nest);
	//Peso
	$nest['tipo'] = 'number';
	$nest['etiqueta'] = 'Peso (Kg)';
	$nest['nombre'] = 'peso';
	$nest['opt'] = 'min="1" max="1000"';
	$nest['required'] = true;
	$datos['nest'][] = $nest;
	unset($nest);
	//Fecha de ingreso
	$nest['tipo'] = 'date';
	$nest['nombre'] = 'fechaIngreso';
	$nest['etiqueta'] = 'Fecha de ingreso';
	$nest['required'] = true;
	$datos['nest'][] = $nest;
	unset($nest);
	//Diagnóstico que causa intervención
	$nest['tipo'] = 'checkbox';
	$nest['nombre'] = 'causasIntervencion';
	$nest['etiqueta'] = 'Diagnóstico que causa intervención';
	$nest['valores'] = ['Cancer de recto (hasta 15 cm del OECA)', 'Cancer de ano', 'Cancer de colon (más de 15 cm del OECA)', 'Enfermedad de CROHN', 'Enfermedad de CROHN perineal', 'C.U.', 'Enfermedad diverticular aguda', 'Enfermedad diverticular crónica', 'Estoma / Colostomia', 'Estoma / Ileostomia', 'Vólvulo', 'Suelo pélvico / Celes', 'Incontinencia Fecal', 'Poliposis', 'Isquemia', 'Traumatismos', 'Cuerpos extraños', 'Ogilvie'];
	$nest['required'] = false;
	if($editar){ //Añadimos los valores del checkbox al resultado
		$resultado = merge_resultado($resultado, $resultado_CausasIntervencion, $nest['nombre']);
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Anticoagulantes orales
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'anticoagulantes';
	$nest['etiqueta'] = 'Anticoagulantes Orales';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	if($editar){ //Si estamos editando
		if($resultado[0][$nest['nombre']] == 1){ 
			$resultado[0][$nest['nombre']] = 'Si';
		}
		else{
			$resultado[0][$nest['nombre']] = 'No';
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//***************************************************************************************************************************************
	$form['inputs'][] = $datos;
	unset($datos);
	//Segundo grupo de inputs: Metástasis
	$datos['etiqueta'] = 'Metástasis';
	$datos['nombre'] = 'Metastasis';
	//Metástasis hepáticas
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'hayMetastasisHepatica';
	$nest['etiqueta'] = 'Metástasis hepáticas';
	$nest['valores'] = ['No', 'Si, sin cirugía', 'Si, con cirugía'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'radio';
	$nest['nest'][0]['nombre'] = 'tipoCirugiaHepatica';
	$nest['nest'][0]['etiqueta'] = 'Tipo de cirugía de las metástasis hepáticas';
	$nest['nest'][0]['valores'] = ['Sincrónica laparoscópica', 'Sincrónica laparotomía', 'Terapia inversa', 'Terapia secuencial'];
	$nest['nest'][0]['required'] = true;
	$nest['nest'][1]['tipo'] = 'radio';
	$nest['nest'][1]['nombre'] = 'tecnicaCirugiaHepatica';
	$nest['nest'][1]['etiqueta'] = 'Técnica de cirugía de las metástasis hepáticas';
	$nest['nest'][1]['valores'] = ['Metastasectomía', 'Segmentectomía', 'Hepatectomía', 'Embilización', 'Alcoholización', 'Ligadura porta'];
	$nest['nest'][1]['required'] = true;
	$nest['nest'][2]['tipo'] = 'date';
	$nest['nest'][2]['nombre'] = 'fechaCirugiaHepatica';
	$nest['nest'][2]['etiqueta'] = 'Fecha de cirugía de las metástasis hepáticas';
	$nest['nest'][2]['required'] = true;
	if($editar){ //Si estamos editando
		if(!empty($resultado_MetastasisHepaticas)){ //Si existe marcamos el si en el cuestionario
			if(!empty($resultado_MetastasisHepaticas_CirugiasHepaticas)){ //Comprobamos si hubo cirugía
				$resultado[0][$nest['nombre']] = 'Si, con cirugía';
				$resultado = merge_resultado($resultado, $resultado_MetastasisHepaticas_CirugiasHepaticas, $nest['nest'][0]['nombre']); // Y añadimos los valores al resultado
				$resultado = merge_resultado($resultado, $resultado_MetastasisHepaticas_CirugiasHepaticas, $nest['nest'][1]['nombre']); // Y añadimos los valores al resultado
				$resultado = merge_resultado($resultado, $resultado_MetastasisHepaticas_CirugiasHepaticas, $nest['nest'][2]['nombre']); // Y añadimos los valores al resultado
			}
			else{
				$resultado[0][$nest['nombre']] = 'Si, sin cirugía';
			}
		}
		else{ //Si no existe ninguno marcamos el no en el cuestionario
			$resultado[0][$nest['nombre']] = 'No';
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Metástasis pulmonares
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'hayMetastasisPulmonar';
	$nest['etiqueta'] = 'Metástasis pulmonares';
	$nest['valores'] = ['No', 'Si, sin cirugía', 'Si, con cirugía'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'date';
	$nest['nest'][0]['nombre'] = 'fechaCirugiaPulmonar';
	$nest['nest'][0]['etiqueta'] = 'Fecha de cirugía de las metástasis pulmonares';
	$nest['nest'][0]['required'] = true;
	if($editar){ //Si estamos editando
		if(!empty($resultado_MetastasisPulmonares)){ //Si existe marcamos el si en el cuestionario
			if(!empty($resultado_MetastasisPulmonares_CirugiasPulmonares)){ //Comprobamos si hubo cirugía
				$resultado[0][$nest['nombre']] = 'Si, con cirugía';
				$resultado = merge_resultado($resultado, $resultado_MetastasisPulmonares_CirugiasPulmonares, $nest['nest'][0]['nombre']); // Y añadimos los valores al resultado
			}
			else{
				$resultado[0][$nest['nombre']] = 'Si, sin cirugía';
			}
		}
		else{ //Si no existe ninguno marcamos el no en el cuestionario
			$resultado[0][$nest['nombre']] = 'No';
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Otras metástasis
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'hayMetastasis';
	$nest['etiqueta'] = 'Otras metástasis';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'checkbox';
	$nest['nest'][0]['nombre'] = 'metastasis';
	$nest['nest'][0]['etiqueta'] = 'Localización';
	$nest['nest'][0]['valores'] = ['Óseas', 'Cerebrales', 'Carcinomatosis', 'Otra'];
	$nest['nest'][0]['required'] = false;
	if($editar){ //Si estamos editando
		if(empty($resultado_Metastasis)){ //Si no existe ninguno marcamos el no en el cuestionario
			$resultado[0][$nest['nombre']] = 'No';
		}
		else{ //Si existe marcamos el si en el cuestionario
			$resultado[0][$nest['nombre']] = 'Si';
			$resultado = merge_resultado($resultado, $resultado_Metastasis, $nest['nest'][0]['nombre']); // Y añadimos los valores al resultado
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//***************************************************************************************************************************************
	$form['inputs'][] = $datos;
	unset($datos);	
	//Tercer grupo de inputs: preoperatorio
	$datos['etiqueta'] = 'Preoperatorio';
	//CEA preoperatorio
	$nest['tipo'] = 'number';
	$nest['nombre'] = 'cea';
	$nest['etiqueta'] = 'CEA preoperatorio';
	$nest['required'] = false;
	$datos['nest'][] = $nest;
	unset($nest);
	//Hemoglobina preoperatoria
	$nest['tipo'] = 'number';
	$nest['nombre'] = 'hemoglobina';
	$nest['etiqueta'] = 'Hemoglobina preoperatoria (en analítica del preoperatorio) (g/L)';
	$nest['opt'] = 'min="0"';
	$nest['required'] = false;
	$datos['nest'][] = $nest;
	unset($nest);
	//Albúmina
	$nest['tipo'] = 'number';
	$nest['nombre'] = 'albumina';
	$nest['etiqueta'] = 'Albúmina (g/dL)';
	$nest['opt'] = 'min="0"';
	$nest['required'] = false;
	$datos['nest'][] = $nest;
	unset($nest);
	//ASA
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'asa';
	$nest['etiqueta'] = 'ASA';
	$nest['valores'] = ['I', 'II', 'III', 'IV'];
	$nest['required'] = true;
	$datos['nest'][] = $nest;
	unset($nest);
	//Intoxicación Alcohol, tabaco o esteroides
	$nest['tipo'] = 'checkbox';
	$nest['nombre'] = 'intoxicaciones';
	$nest['etiqueta'] = 'Intoxicación';
	$nest['valores'] = ['Alcohol', 'Tabaco', 'Esteroides'];
	$nest['required'] = false;
	if($editar){ //Añadimos los valores del checkbox al resultado
		$resultado = merge_resultado($resultado, $resultado_Intoxicaciones, $nest['nombre']);
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Comorbilidades
	$nest['tipo'] = 'checkbox';
	$nest['nombre'] = 'comorbilidades';
	$nest['etiqueta'] = 'Comorbilidades';
	$nest['valores'] = ['Cardiológica Valvular', 'Cardiológica Coronaria', 'Anticoagulado', 'Arritmia', 'Hematológica', 'Respiratoria', 'HTA', 'Diabetes Mellitus', 'Neurológica', 'Renal', 'Inmunodeprimido', 'Aterosclerosis Periférica', 'Hepática'];
	$nest['required'] = false;
	if($editar){ //Añadimos los valores del checkbox al resultado
		$resultado = merge_resultado($resultado, $resultado_Comorbilidades, $nest['nombre']);
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Endoscopia diagnostica
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'endoscopia';
	$nest['etiqueta'] = 'Endoscopia diagnóstica';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'date';
	$nest['nest'][0]['nombre'] = 'fechaEndoscopia';
	$nest['nest'][0]['etiqueta'] = 'Fecha';
	$nest['nest'][0]['required'] = true;
	if($editar){ //Si estamos editando
		if(empty($resultado[0][$nest['nest'][0]['nombre']])){ //Si no existe una fecha marcamos el no en el cuestionario
			$resultado[0][$nest['nombre']] = 'No';
		}
		else{ //Si existe una fecha marcamos el si en el cuestionario
			$resultado[0][$nest['nombre']] = 'Si';
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Biopsia
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'biopsia';
	$nest['etiqueta'] = 'Biopsia';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'date';
	$nest['nest'][0]['nombre'] = 'fechaBiopsia';
	$nest['nest'][0]['etiqueta'] = 'Fecha';
	$nest['nest'][0]['required'] = true;
	if($editar){ //Si estamos editando
		if(empty($resultado[0][$nest['nest'][0]['nombre']])){ //Si no existe una fecha marcamos el no en el cuestionario
			$resultado[0][$nest['nombre']] = 'No';
		}
		else{ //Si existe una fecha marcamos el si en el cuestionario
			$resultado[0][$nest['nombre']] = 'Si';
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Presentación en comité CCR
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'presentacion';
	$nest['etiqueta'] = 'Presentación en comité CCR';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'date';
	$nest['nest'][0]['nombre'] = 'fechaPresentacion';
	$nest['nest'][0]['etiqueta'] = 'Fecha';
	$nest['nest'][0]['required'] = true;
	if($editar){ //Si estamos editando
		if(empty($resultado[0][$nest['nest'][0]['nombre']])){ //Si no existe una fecha marcamos el no en el cuestionario
			$resultado[0][$nest['nombre']] = 'No';
		}
		else{ //Si existe una fecha marcamos el si en el cuestionario
			$resultado[0][$nest['nombre']] = 'Si';
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Localización del cancer
	$nest['tipo'] = 'checkbox';
	$nest['nombre'] = 'localizacionesCancer';
	$nest['etiqueta'] = 'Localización del cancer';
	$nest['valores'] = ['Margen anal', 'Canal anal', 'Tercio inferior de recto (0 a 5 cm)', 'Tercio medio de recto (6 a 10 cm)', 'Tercio superior de recto (11 a 15 cm)', 'Unión rectosigmoidea (16 a 20 cm)', 'Sigma (21 hasta 28 cm)', 'Colon izquierdo', 'Flexura esplénica', 'Colon Transverso', 'Flexura hepática', 'Colon derecho', 'Ciego', 'Apéndice'];
	$nest['required'] = false;
	if($editar){ //Añadimos los valores del checkbox al resultado
		$resultado = merge_resultado($resultado, $resultado_LocalizacionesCancer, $nest['nombre']);
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Tumor Sincrónico
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'TumoresSincronicos';
	$nest['etiqueta'] = 'Tumor Sincrónico';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'checkbox';
	$nest['nest'][0]['nombre'] = 'tumoresSincronicos';
	$nest['nest'][0]['etiqueta'] = 'Localización (más proximal)';
	$nest['nest'][0]['valores'] = ['Colon derecho', 'Colon trasverso', 'Colon izquierdo', 'Colon sigmoide', 'Unión rectosigmoidea', 'Recto'];
	$nest['nest'][0]['required'] = false;
	if($editar){ //Si estamos editando
		if(empty($resultado_TumoresSincronicos)){ //Si no existe ninguno marcamos el no en el cuestionario
			$resultado[0][$nest['nombre']] = 'No';
		}
		else{ //Si existe marcamos el si en el cuestionario
			$resultado[0][$nest['nombre']] = 'Si';
			$resultado = merge_resultado($resultado, $resultado_TumoresSincronicos, $nest['nest'][0]['nombre']); // Y añadimos los valores al resultado
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Prótesis
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'hayProtesis';
	$nest['etiqueta'] = 'Prótesis';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'date';
	$nest['nest'][0]['nombre'] = 'fechaProtesis';
	$nest['nest'][0]['etiqueta'] = 'Fecha de la prótesis';
	$nest['nest'][0]['required'] = true;
	$nest['nest'][1]['tipo'] = 'radio';
	$nest['nest'][1]['nombre'] = 'complicacionProtesis';
	$nest['nest'][1]['etiqueta'] = 'Complicación de la prótesis';
	$nest['nest'][1]['valores'] = ['No', 'Si'];
	$nest['nest'][1]['required'] = true;
	if($editar){ //Si estamos editando
		if(empty($resultado_Protesis)){ //Si no existe ninguno marcamos el no en el cuestionario
			$resultado[0][$nest['nombre']] = 'No';
		}
		else{ //Si existe marcamos el si en el cuestionario
			$resultado[0][$nest['nombre']] = 'Si';
			$resultado = merge_resultado($resultado, $resultado_Protesis, $nest['nest'][0]['nombre']); // Y añadimos los valores al resultado
			if($resultado_Protesis[0]['complicacion'] == 1){ //complicacion
				$resultado[0][$nest['nest'][1]['nombre']] = 'Si';
			}
			else{
				$resultado[0][$nest['nest'][1]['nombre']] = 'No';
			}
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//***************************************************************************************************************************************
	$form['inputs'][] = $datos;
	unset($datos);
	//Botones de "submit"
	$form['submit']['name'] = 'editar';
	if($editar){ //Si se está editando Actualizar, si no Enviar
		$form['submit']['value'] = 'true';
		$form['submit']['etiqueta'] = 'Actualizar';
	}
	else{
		$form['submit']['value'] = 'false';
		$form['submit']['etiqueta'] = 'Enviar';
	}
	$html['body'] = $html['body'] . make_form($form, $editar, $resultado); 
	//Crear las opciones
	$datos[0]['tipo'] = 'redirección';
	if($editar){ //Si se está editando se devuelve a consulta diagnostico con volver
		$datos[0]['objetivo'] = 'consultaDiagnostico.php';
	}
	else{ //Si se está creando se devuelve a consulta filiacion con volver
		$datos[0]['objetivo'] = 'consultaFiliacion.php';
	}
	$datos[0]['nombre'] = 'Volver';
	$html['body'] = $html['body'] . make_navbar($datos);
	echo make_html($html);
?>
