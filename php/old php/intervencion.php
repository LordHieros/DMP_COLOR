<?php
//encontrar con \$datos(\['nest'\]\[[0-9]\])?\['nombre'\] =
	include('php/header.php');
	include('php/make_form.php');
	
	comprueba_intervencion();
	
	require 'php/Conexion.php';
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM Intervenciones WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND fecha=:fechaIntervencion;');
	$res = $stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fechaIntervencion' => $_SESSION[CampoSession::FECHA_INTERVENCION]]);
	$resultado = $stmt->fetchAll();
	//Si se llega a una intervencion cerrada se redirige a la consulta 
	$stmt = Conexion::getpdo()->prepare('SELECT * FROM DatosIntervencion WHERE Filiaciones_NASI=:nasi AND Diagnosticos_fecha=:fechaDiagnostico AND Intervenciones_fecha=:fechaIntervencion;');
	$stmt->execute(['nasi' => $_SESSION[CampoSession::NASI], 'fechaDiagnostico' => $_SESSION[CampoSession::FECHA_DIAGNOSTICO], 'fechaIntervencion' => $_SESSION[CampoSession::FECHA_INTERVENCION]]);
	$resultado = $stmt->fetchAll();
	if($resultado[0]["cerrado"]==1){
		header("location: consulta_intervencion.php");
		die;
	}
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
		
	//Añadimos opciones de cabecera
	$html['head'] = '<script src="./js/Mostrar.js"> </script>';
	//EMPIEZA EL HTML
	//Ponemos el título de la página
	if($editar){
		$html['titulo'] = 'Editar datos de la intervención del ' . $_SESSION[CampoSession::FECHA_INTERVENCION] . ' correspondiente al diagnóstico del ' . $_SESSION[CampoSession::FECHA_DIAGNOSTICO] . ' de la filiación ' . $_SESSION[CampoSession::NASI];
	}
	else{
		$html['titulo'] = 'Crear datos de la intervención del ' . $_SESSION[CampoSession::FECHA_INTERVENCION] . ' correspondiente al diagnóstico del ' . $_SESSION[CampoSession::FECHA_DIAGNOSTICO] . ' de la filiación ' . $_SESSION[CampoSession::NASI];
	}
	//Creamos el cuerpo
	$html['body'] = '';
	//Introducimos los datos del formulario
	$form['action'] = 'php/intervencion_datos.php';
	$form['legend'] = 'Intervención';
	//EMPIEZAN LOS INPUTS DEL FORMULARIO
	//Crear el contenedor de los inputs
	$form['inputs'] = array();
	//Primer grupo de inputs: principal
	$datos['etiqueta'] = 'Principal';
	//Duracion
	$nest['tipo'] = 'number';
	$nest['nombre'] = 'duracion';
	$nest['etiqueta'] = 'Duración (minutos)';
	$nest['required'] = true;
	$datos['nest'][] = $nest;
	unset($nest);
	//Fecha cirujano pone en lista de espera
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'hayListaEspera';
	$nest['etiqueta'] = 'Puesto en lista de espera';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'date';
	$nest['nest'][0]['nombre'] = 'fechaListaEspera';
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
	//Intervención realizada
	$nest['tipo'] = 'checkbox';
	$nest['nombre'] = 'tiposIntervencion';
	$nest['etiqueta'] = 'Tipo de intervención realizada';
	$nest['valores'] = ['Hemicolectomía derecha', 'HCD ampliada', 'Colectomía transversa', 'Hemicolectomía izquierda', 'Sigmoidectomía', 'Colectomía abdominal total', 'Proctocolectomía', 'Resección ANT alta', 'Resección ANT baja', 'AAP', 'AAP extendida en prono', 'Hartmann', 'Derivación', 'Colostomía', 'Ileostomía', 'Laparotomía exploradora', 'Laparoscopia exploradora', 'Transanal (TAMIS)', 'TaTME', 'Transanal de Parks', 'Resección ileocecal', 'Colectomía segmentaria (atípica)', 'Otras'];
	$nest['required'] = false;
	if($editar){ //Añadimos los valores del checkbox al resultado
		$resultado = merge_resultado($resultado, $resultado_TiposIntervencion, $nest['nombre']);
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Código del cirujano
	$nest['tipo'] = 'number';
	$nest['nombre'] = 'codigoCirujano';
	$nest['etiqueta'] = 'Código del cirujano que realiza la intervención';
	$nest['required'] = true;
	$datos['nest'][] = $nest;
	unset($nest);
	//Carácter
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'hayUrgente';
	$nest['etiqueta'] = 'Carácter';
	$nest['valores'] = ['Programada', 'Urgente'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'checkbox';
	$nest['nest'][0]['nombre'] = 'motivos_Urgente';
	$nest['nest'][0]['etiqueta'] = 'Motivo';
	$nest['nest'][0]['valores'] = ['Obstrucción', 'Perforación', 'Hemorragia', 'Sépsis', 'Otros'];
	$nest['nest'][0]['required'] = false;
	$nest['nest'][1]['tipo'] = 'radio';
	$nest['nest'][1]['nombre'] = 'hemodinamicamenteEstableUrgente';
	$nest['nest'][1]['etiqueta'] = 'Hemodinamicamente estable';
	$nest['nest'][1]['valores'] = ['No', 'Si'];
	$nest['nest'][1]['required'] = true;
	$nest['nest'][2]['tipo'] = 'radio';
	$nest['nest'][2]['nombre'] = 'insuficienciaRenalUrgente';
	$nest['nest'][2]['etiqueta'] = 'Insuficiencia renal';
	$nest['nest'][2]['valores'] = ['No', 'Si'];
	$nest['nest'][2]['required'] = true;
	$nest['nest'][3]['tipo'] = 'radio';
	$nest['nest'][3]['nombre'] = 'peritonitisUrgente';
	$nest['nest'][3]['etiqueta'] = 'Peritonitis';
	$nest['nest'][3]['valores'] = ['No', 'Purulenta', 'Fecaloidea'];
	$nest['nest'][3]['required'] = true;
	if($editar){ //Si estamos editando
		if(empty($resultado_Urgentes)){ //Si no existe marcamos que es programada
			$resultado[0][$nest['nombre']] = $nest['valores'][0];
		} 
		else{ //Si existe marcamos que es urgente
			$resultado[0][$nest['nombre']] = $nest['valores'][1];
			if(!empty($resultado_Urgentes_Motivos)){ //Si se ha especificado motivo para que sea urgente lo marcamos
				$resultado = merge_resultado($resultado, $resultado_Urgentes_Motivos, $nest['nest'][0]['nombre']); 
			}
			if($resultado_Urgentes[0]['hemodinamicamenteEstable'] == 1){ //hemodinamicamente estable
				$resultado[0][$nest['nest'][1]['nombre']] = 'Si';
			}
			else{
				$resultado[0][$nest['nest'][1]['nombre']] = 'No';
			}
			if($resultado_Urgentes[0]['insuficienciaRenal'] == 1){ //hemodinamicamente estable
				$resultado[0][$nest['nest'][2]['nombre']] = 'Si';
			}
			else{
				$resultado[0][$nest['nest'][2]['nombre']] = 'No';
			}
			if(!empty($resultado_Urgentes[0]['peritonitis'])){ //En caso de haber peritonitis la marcamos, en caso contrario marcamos "no"
				$resultado = merge_resultado($resultado, $resultado_Urgentes, $nest['nest'][3]['nombre']);
			}
			else{
				$resultado[0][$nest['nest'][3]['nombre']] = $nest['nest'][3]['valores'][0];
			}
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Acceso
	$nest['tipo'] = 'checkbox';
	$nest['nombre'] = 'accesos';
	$nest['etiqueta'] = 'Acceso';
	$nest['valores'] = ['Laparoscopia', 'Laparotomía', 'Conversión', 'Laparoscopia asistida', 'Transanal', 'Estoma', 'Perineal'];
	$nest['required'] = false;
	if($editar){ //Añadimos los valores del checkbox al resultado
		$resultado = merge_resultado($resultado, $resultado_Accesos, $nest['nombre']);
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Tipo de resección
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'tipoReseccion';
	$nest['etiqueta'] = 'Tipo de resección';
	$nest['valores'] = ['R0', 'R1', 'R2'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'radio';
	$nest['nest'][0]['nombre'] = 'margenAfecto';
	$nest['nest'][0]['etiqueta'] = 'Margen Afecto';
	$nest['nest'][0]['valores'] = ['Margen distal afecto', 'Margen proximal afecto', 'Margen circunferencial afecto'];
	$nest['nest'][0]['required'] = true;
	$datos['nest'][] = $nest;
	unset($nest);
	//Excisión mesorrectal
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'hayExcision';
	$nest['etiqueta'] = 'Excisión mesorrectal';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'radio';
	$nest['nest'][0]['nombre'] = 'excision';
	$nest['nest'][0]['etiqueta'] = 'Tipo de excisión mesorrectal';
	$nest['nest'][0]['valores'] = ['Subtotal', 'Total (ETM)'];
	$nest['nest'][0]['required'] = true;
	if($editar){ //Si estamos editando
		if(empty($resultado[0][$nest['nest'][0]['nombre']])){ //Si no existe una excisión marcamos el no en el cuestionario
			$resultado[0][$nest['nombre']] = 'No';
		}
		else{ //Si existe una excisión marcamos el si en el cuestionario
			$resultado[0][$nest['nombre']] = 'Si';
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Anastomosis
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'hayAnastomosis';
	$nest['etiqueta'] = 'Anastomosis';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'radio';
	$nest['nest'][0]['nombre'] = 'tipoAnastomosis';
	$nest['nest'][0]['etiqueta'] = 'Tipo';
	$nest['nest'][0]['valores'] = ['Ileo-cólica', 'Ileo-rectal', 'Colo-cólica', 'Colo-rectal', 'Colo-anal', 'Ileo-anal con reservorio en j'];
	$nest['nest'][0]['required'] = true;
	$nest['nest'][1]['tipo'] = 'radio';
	$nest['nest'][1]['nombre'] = 'tecnicaAnastomosis';
	$nest['nest'][1]['etiqueta'] = 'Técnica';
	$nest['nest'][1]['valores'] = ['Manual', 'Mecánica'];
	$nest['nest'][1]['required'] = true;
	$nest['nest'][2]['tipo'] = 'radio';
	$nest['nest'][2]['nombre'] = 'modalidadAnastomosis';
	$nest['nest'][2]['etiqueta'] = 'Modalidad';
	$nest['nest'][2]['valores'] = ['Termino-terminal', 'Termino-lateral', 'Latero-lateral', 'Latero-terminal', 'Reservorio en j', 'Coloplastia'];
	$nest['nest'][2]['required'] = true;
	if($editar){ //Si estamos editando
		if(empty($resultado_Anastomosis)){ //Si no existe marcamos que el no
			$resultado[0][$nest['nombre']] = $nest['valores'][0];
		} 
		else{ //Si existe marcamos que el si
			$resultado[0][$nest['nombre']] = $nest['valores'][1];
			// Y añadimos los valores al resultado
			$resultado = merge_resultado($resultado, $resultado_Anastomosis, $nest['nest'][0]['nombre']); 
			$resultado = merge_resultado($resultado, $resultado_Anastomosis, $nest['nest'][1]['nombre']); 
			$resultado = merge_resultado($resultado, $resultado_Anastomosis, $nest['nest'][2]['nombre']);
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//RIO
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'rio';
	$nest['etiqueta'] = 'RIO';
	$nest['valores'] = ['No', 'Abortada', 'Si'];
	$nest['required'] = true;
	$datos['nest'][] = $nest;
	unset($nest);
	//Estoma
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'hayEstoma';
	$nest['etiqueta'] = 'Estoma';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'radio';
	$nest['nest'][0]['nombre'] = 'estoma';
	$nest['nest'][0]['etiqueta'] = 'Tipo';
	$nest['nest'][0]['valores'] = ['Colostomía', 'Ileostomía', 'Lateral, sobre soporte o cañón de escopeta', 'De asas desfuncionalizadas', 'Fístula Mucosa'];
	$nest['nest'][0]['required'] = true;
	if($editar){ //Si estamos editando
		if(empty($resultado[0][$nest['nest'][0]['nombre']])){ //Si no se ha introducido neoadyuvancia marcamos el no en el cuestionario
			$resultado[0][$nest['nombre']] = 'No';
		}
		else{ //Si se ha introducido marcamos el si en el cuestionario
			$resultado[0][$nest['nombre']] = 'Si';
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Resección (Intervenciones asociadas)
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'hayIntervencionesAsociadas';
	$nest['etiqueta'] = 'Resección de órganos adyacentes afectos';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'checkbox';
	$nest['nest'][0]['nombre'] = 'intervencionesAsociadas';
	$nest['nest'][0]['etiqueta'] = 'Intervenciones asociadas';
	$nest['nest'][0]['valores'] = ['Resección de vagina', 'Resección de útero', 'Resección de trompas', 'Resección de ovarios', 'Resección de próstata', 'Resección parcial de vejiga', 'Cistectomía total', 'Resección de coccis', 'Resección de sacro (S3-S4)', 'Resección de intestino delgado', 'Resección de vesículas seminales', 'Resección de pared abdominal', 'Otra resección'];
	$nest['nest'][0]['required'] = false;
	if($editar){ //Si estamos editando
		if(empty($resultado_IntervencionesAsociadas)){ //Si no existe ninguno marcamos el no en el cuestionario
			$resultado[0][$nest['nombre']] = 'No';
		}
		else{ //Si existe marcamos el si en el cuestionario
			$resultado[0][$nest['nombre']] = 'Si';
			$resultado = merge_resultado($resultado, $resultado_IntervencionesAsociadas, $nest['nest'][0]['nombre']); // Y añadimos los valores al resultado
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//***************************************************************************************************************************************
	$form['inputs'][] = $datos;
	unset($datos);
	//Segundo grupo de inputs: Postoperatorio
	$datos['etiqueta'] = 'Postoperatorio';
	//Fecha de alta
	$nest['tipo'] = 'date';
	$nest['nombre'] = 'fechaAlta';
	$nest['etiqueta'] = 'Fecha de alta';
	$nest['required'] = true;
	$datos['nest'][] = $nest;
	unset($nest);
	//Complicaciones intraoperatorias
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'hayComplicacionesIntraoperatorias';
	$nest['etiqueta'] = 'Complicaciones intraoperatorias';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'checkbox';
	$nest['nest'][0]['nombre'] = 'complicacionesIntraoperatorias';
	$nest['nest'][0]['etiqueta'] = 'Complicación';
	$nest['nest'][0]['valores'] = ['Contaminación intraperitoneal', 'Lesión intestinal', 'Lesión uretral', 'Lesión vesical', 'Lesión vascular', 'Lesión vaginal', 'Lesión nerviosa', 'Otra lesión', 'Otras complicaciones'];
	$nest['nest'][0]['required'] = false;
	if($editar){ //Si estamos editando
		if(empty($resultado_ComplicacionesIntraoperatorias)){ //Si no existe ninguno marcamos el no en el cuestionario
			$resultado[0][$nest['nombre']] = 'No';
		}
		else{ //Si existe marcamos el si en el cuestionario
			$resultado[0][$nest['nombre']] = 'Si';
			$resultado = merge_resultado($resultado, $resultado_ComplicacionesIntraoperatorias, $nest['nest'][0]['nombre']); // Y añadimos los valores al resultado
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Complicaciones relacionadas con la cirugía
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'hayComplicacionesCirugia';
	$nest['etiqueta'] = 'Complicaciones relacionadas con la cirugía';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'checkbox';
	$nest['nest'][0]['nombre'] = 'complicacionesCirugia';
	$nest['nest'][0]['etiqueta'] = 'Complicación';
	$nest['nest'][0]['valores'] = ['Infección superficial del sitio quirúrgico', 'Infección profunda del sitio quirúrgico', 'Infección de la herida perineal', 'Absceso intraabdominal', 'Absceso pélvico', 'Peritonitis purulenta', 'Peritonitis fecaloidea', 'Sépsis', 'Hemoperitóneo' , 'Hemorragia digestiva', 'Ileo paralítico prolongado', 'Obstrucción intestinal', 'Isquemia intestinal', 'Evisceración', 'Necrosis estoma', 'Prolapso estoma', 'Estenosis estoma', 'Dehiscencia anastomótica clínica', 'Dehiscencia anastomótica radiológica sin clínica'];
	$nest['nest'][0]['required'] = false;
	if($editar){ //Si estamos editando
		if(empty($resultado_ComplicacionesCirugia)){ //Si no existe ninguno marcamos el no en el cuestionario
			$resultado[0][$nest['nombre']] = 'No';
		}
		else{ //Si existe marcamos el si en el cuestionario
			$resultado[0][$nest['nombre']] = 'Si';
			$resultado = merge_resultado($resultado, $resultado_ComplicacionesCirugia, $nest['nest'][0]['nombre']); // Y añadimos los valores al resultado
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Complicaciones médicas
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'hayComplicacionesMedicas';
	$nest['etiqueta'] = 'Complicaciones médicas';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'checkbox';
	$nest['nest'][0]['nombre'] = 'complicacionesMedicas';
	$nest['nest'][0]['etiqueta'] = 'Complicación';
	$nest['nest'][0]['valores'] = ['Evento cardíaco', 'Evento respiratorio', 'Evento neurológico', 'Evento nefrológico', 'Evento hematológico', 'Evento endocrino-metabólico', 'TVP', 'Flebitis', 'Infección de vía central', 'Infección urinaria', 'Precisó sondaje urinario', 'RAO', 'Fiebre de origen desconocido'];
	$nest['nest'][0]['required'] = false;
	if($editar){ //Si estamos editando
		if(empty($resultado_ComplicacionesMedicas)){ //Si no existe ninguno marcamos el no en el cuestionario
			$resultado[0][$nest['nombre']] = 'No';
		}
		else{ //Si existe marcamos el si en el cuestionario
			$resultado[0][$nest['nombre']] = 'Si';
			$resultado = merge_resultado($resultado, $resultado_ComplicacionesMedicas, $nest['nest'][0]['nombre']); // Y añadimos los valores al resultado
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Transfusiones
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'Transfusiones';
	$nest['etiqueta'] = 'hayTransfusiones';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'checkbox';
	$nest['nest'][0]['nombre'] = 'transfusiones';
	$nest['nest'][0]['etiqueta'] = 'Momento';
	$nest['nest'][0]['valores'] = ['En preoperatorio', 'Intraoperatoria', 'En postoperatorio'];
	$nest['nest'][0]['required'] = false;
	if($editar){ //Si estamos editando
		if(empty($resultado_Transfusiones)){ //Si no existe ninguno marcamos el no en el cuestionario
			$resultado[0][$nest['nombre']] = 'No';
		}
		else{ //Si existe marcamos el si en el cuestionario
			$resultado[0][$nest['nombre']] = 'Si';
			$resultado = merge_resultado($resultado, $resultado_Transfusiones, $nest['nest'][0]['nombre']); // Y añadimos los valores al resultado
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Neoadyuvancia
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'hayNeoadyuvancia';
	$nest['etiqueta'] = 'Neoadyuvancia';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'radio';
	$nest['nest'][0]['nombre'] = 'neoadyuvancia';
	$nest['nest'][0]['etiqueta'] = 'Tipo';
	$nest['nest'][0]['valores'] = ['RT ciclo largo + QT', 'RT ciclo corto + QT', 'RT ciclo corto', 'RT', 'QT'];
	$nest['nest'][0]['required'] = true;
	if($editar){ //Si estamos editando
		if(empty($resultado[0][$nest['nest'][0]['nombre']])){ //Si no se ha introducido neoadyuvancia marcamos el no en el cuestionario
			$resultado[0][$nest['nombre']] = 'No';
		}
		else{ //Si se ha introducido marcamos el si en el cuestionario
			$resultado[0][$nest['nombre']] = 'Si';
		}
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Reingreso antes de 30 días
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'reingreso30Dias';
	$nest['etiqueta'] = 'Reingreso antes de los 30 días';
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
	//Tercer grupo de inputs: Estudio Anatomopatológico
	$datos['etiqueta'] = 'Estudio Anatomopatológico';
	$datos['nombre'] = 'Estudio_Anatomopatologico';
	//Histología
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'histologia';
	$nest['etiqueta'] = 'Histología';
	$nest['valores'] = ['Adenocarcinoma', 'ADC mucinoso (inf 50%)', 'GIST', 'Neuroendocrino', 'Linfoma', 'Adenocarcinoide', 'Carcinoide', 'Células en anillo de sello', 'Carcinoma medular', 'Otro'];
	$nest['required'] = true;
	$datos['nest'][] = $nest;
	unset($nest);
	//TNM
	$nest['etiqueta'] = 'TNM';
	$nest['nest'][0]['tipo'] = 'radio';
	$nest['nest'][0]['nombre'] = 'T';
	$nest['nest'][0]['etiqueta'] = 'Tumor principal (T)';
	$nest['nest'][0]['valores'] = ['TX', 'T0', 'Tis', 'T1', 'T2', 'T3', 'T4a', 'T4b'];
	$nest['nest'][0]['explicaciones'] = ['El tumor principal no puede ser evaluado', 'Sin evidencia de tumor principal', 'Carcinoma <i>in situ</i>: intraepitelial o invasion de <i>lamina propria</i>', 'Tumor invade submucosa', 'Tumor invade <i>muscularis propria</i>', 'Tumor invade los tejidos pericolorrectales a través de la <i>muscularis propria</i>', 'Tumor penetra a la superficie del peritóneo visceral', 'Tumor directamente invade o esta adherente a otros órganos o estructuras'];
	$nest['nest'][0]['required'] = true;
	$nest['nest'][1]['tipo'] = 'radio';
	$nest['nest'][1]['nombre'] = 'N';
	$nest['nest'][1]['etiqueta'] = 'Nodos linfáticos regionales (N)';
	$nest['nest'][1]['valores'] = ['NX', 'N0', 'N1', 'N1a', 'N1b', 'N1c', 'N2', 'N2a', 'N2b'];
	$nest['nest'][1]['explicaciones'] = ['Nodos linfáticos regionales no pueden ser evaluados', 'Sin metástasis en los nodos linfáticos regionales', 'Metástasis en 1-3 nodos linfáticos regionales', 'Metástasis en un nodo linfático regional', 'Metástasis en 2-3 nodos linfáticos regionales', 'Depósito o depósitos en la subserosa, mesenteria o pericolónico no peritonizado; o tejidos perirrectales sin metástasis nodal regional', 'Metástasis en cuatro o más nodos linfáticos regionales', 'Metástasis en 4-6 nodos linfáticos regionales', 'Metástasis en siete o más nodos linfáticos regionales'];
	$nest['nest'][1]['required'] = true;
	$nest['nest'][2]['tipo'] = 'radio';
	$nest['nest'][2]['nombre'] = 'M';
	$nest['nest'][2]['etiqueta'] = 'Metástasis distante (M)';
	$nest['nest'][2]['valores'] = ['M0', 'M1', 'M1a', 'M1b'];
	$nest['nest'][2]['explicaciones'] = ['Sin metástasis distante', 'Con metástasis distante', 'Metástasis confinada a un organo o lugar (ej.: riñón, pulmón, ovario, nodo no regional)', 'Metástasis en más de un órgano o lugar o en el peritoneo'];
	$nest['nest'][2]['required'] = true;
	$datos['nest'][] = $nest;
	unset($nest);
	//Distancia al margen distal en mm
	$nest['tipo'] = 'number';
	$nest['nombre'] = 'distanciaMargenDistal';
	$nest['etiqueta'] = 'Distancia al margen distal (mm)';
	$nest['opt'] = 'min="0"';
	$nest['required'] = false;
	$datos['nest'][] = $nest;
	unset($nest);
	//Grado de diferenciación
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'gradoDiferenciacion';
	$nest['etiqueta'] = 'Grado de diferenciación';
	$nest['valores'] = ['Bien diferenciado', 'Moderadamente diferenciado', 'Mal diferenciado o indiferenciado'];
	$nest['required'] = true;
	$datos['nest'][] = $nest;
	unset($nest);
	//Factores de riesgo histológico
	$nest['tipo'] = 'checkbox';
	$nest['nombre'] = 'FactoresRiesgo_tipo';
	$nest['etiqueta'] = 'Factores de riesgo histológico';
	$nest['valores'] = ['Infiltración perivascular', 'Infiltración perineural', 'Infiltración perilinfática', 'Crohn like', 'Frente de invasión'];
	$nest['required'] = false;
	if($editar){ //Añadimos los valores del checkbox al resultado
		$resultado = merge_resultado($resultado, $resultado_FactoresRiesgo, $nest['nombre']);
	}
	$datos['nest'][] = $nest;
	unset($nest);
	//Cancer de recto
	$nest['tipo'] = 'radio';
	$nest['nombre'] = 'hayCancerRecto';
	$nest['etiqueta'] = 'Cancer de recto';
	$nest['valores'] = ['No', 'Si'];
	$nest['required'] = true;
	$nest['nest']['oculto'] = true;
	$nest['nest'][0]['tipo'] = 'checkbox';
	$nest['nest'][0]['nombre'] = 'estadificaciones_CancerRecto';
	$nest['nest'][0]['etiqueta'] = 'Estadificación local por';
	$nest['nest'][0]['valores'] = ['Eco Endoanal', 'TAC', 'RMN pélvica', 'PET'];
	$nest['nest'][0]['required'] = false;
	$nest['nest'][1]['tipo'] = 'radio';
	$nest['nest'][1]['nombre'] = 'mandardCancerRecto';
	$nest['nest'][1]['etiqueta'] = 'Mandard';
	$nest['nest'][1]['valores'] = ['Respuesta patológica completa', 'Células tumorales aislados', 'Predominio de fibrosis', 'Predominio de nidos tumorales', 'Ausencia de regresión'];
	$nest['nest'][1]['required'] = true;
	$nest['nest'][2]['tipo'] = 'radio';
	$nest['nest'][2]['nombre'] = 'calidadMesorrectoCancerRecto';
	$nest['nest'][2]['etiqueta'] = 'Calidad del mesorrecto';
	$nest['nest'][2]['valores'] = ['Óptima', 'Subóptima', 'Insatisfactoria / mala'];
	$nest['nest'][2]['required'] = true;
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
	$datos[0]['objetivo'] = 'consulta_intervencion.php'; 
	$datos[0]['nombre'] = 'Volver';
	$html['body'] = $html['body'] . make_navbar($datos);
	echo make_html($html);
?>
