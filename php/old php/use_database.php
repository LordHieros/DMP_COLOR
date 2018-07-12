<?php	
	function database_insert($base, $claves, $datos, $claves_principal, $datos_principal){
		$preparacion_into = 'INSERT INTO ' . $base . ' (' . $claves_principal[0];
		$preparacion_values = 'VALUES (:' . $claves_principal[0]; 
		$ejecucion[$claves_principal[0]] = $datos_principal[0];
		for($i=1; $i<count($claves_principal); $i++){
			$preparacion_into = $preparacion_into . ', ' . $claves_principal[$i];
			$preparacion_values = $preparacion_values . ', :' . $claves_principal[$i];
			$ejecucion[$claves_principal[$i]] = $datos_principal[$i];
		}
		if(!is_array($datos[0])){ //En caso de estar introduciendo una única entrada en la tabla
			for($i=0; $i<count($claves); $i++){
				if($datos[$i] !== null){ //Si se va a insertar un valor nulo se ignora para reducir carga en la base de datos; ponemos el comparado !== para que no considere nulos los valores de 0
					$preparacion_into = $preparacion_into . ', ' . $claves[$i];
					$preparacion_values = $preparacion_values . ', :' . $claves[$i];
					$ejecucion[$claves[$i]] = $datos[$i];
				}
			}
			$preparacion = $preparacion_into . ') ' . $preparacion_values . ');';
			$stmt = Conexion::getpdo()->prepare($preparacion);
			$stmt->execute($ejecucion);
		}
		else{
			if(count($claves)>0){ //Comprobamos si se han introducido datos y si no mandamos mensaje de error
				if($datos[0][0] !== null){ //Si el primer valor del array es nulo no se insertan valores
					$preparacion = $preparacion_into . ', ' . $claves[0] . ') ' . $preparacion_values . ', :' . $claves[0] . ');';
					for($i=0; $i<count($datos[0]); $i++){
						$ejecucion[$claves[0]] = $datos[0][$i];
						$stmt = Conexion::getpdo()->prepare($preparacion);
						$stmt->execute($ejecucion);
					}
				}
			}
			else{
				$_SESSION[CampoSession::ERROR]='Se ha intentado hacer una insercion múltiple sin especificar datos a insertar';
				header('location: ' . $_SERVER["DOCUMENT_ROOT"] . '/panel.php');
			}
		}
	}
	
	function database_update($base, $claves, $datos, $claves_principal, $datos_principal){
		if(!is_array($datos[0])){ //En caso de estar editando una única entrada de la tabla
			$preparacion = 'UPDATE ' . $base . ' SET ' . $claves[0] . ' = :' . $claves[0];
			$ejecucion[$claves[0]] = $datos[0];
			for($i=1; $i<count($claves); $i++){
				$preparacion = $preparacion . ', ' . $claves[$i] . ' = :' . $claves[$i];
				$ejecucion[$claves[$i]] = $datos[$i];
			}
			$preparacion = $preparacion . ' WHERE ' . $claves_principal[0] . ' = :' . $claves_principal[0];
			$ejecucion[$claves_principal[0]] = $datos_principal[0];
			for($i=1; $i<count($claves_principal); $i++){
				$preparacion = $preparacion . ' AND ' . $claves_principal[$i] . ' = :' . $claves_principal[$i];
				$ejecucion[$claves_principal[$i]] = $datos_principal[$i];
			}
			$preparacion = $preparacion . ';';
			$stmt = Conexion::getpdo()->prepare($preparacion);
			$stmt->execute($ejecucion);
		}
		else{ //Borramos todas las entradas con la clave principal especificada
			$preparacion = 'DELETE FROM ' . $base . ' WHERE ' . $claves_principal[0] . ' = :' . $claves_principal[0];
			$ejecucion[$claves_principal[0]] = $datos_principal[0];
			for($i=1; $i<count($claves_principal); $i++){
				$preparacion = $preparacion . ' AND ' . $claves_principal[$i] . ' = :' . $claves_principal[$i];
				$ejecucion[$claves_principal[$i]] = $datos_principal[$i];
			}
			$preparacion = $preparacion . ';';
			$stmt = Conexion::getpdo()->prepare($preparacion);
			$stmt->execute($ejecucion);
			// En cuanto al resto es como un insert múltiple, mismo código
			if($datos[0][0] !== null){ //Si el primer valor del array es nulo no se insertan valores
				$preparacion_into = 'INSERT INTO ' . $base . ' (' . $claves_principal[0];
				$preparacion_values = 'VALUES (:' . $claves_principal[0]; 
				$ejecucion[$claves_principal[0]] = $datos_principal[0];
				for($i=1; $i<count($claves_principal); $i++){
					$preparacion_into = $preparacion_into . ', ' . $claves_principal[$i];
					$preparacion_values = $preparacion_values . ', :' . $claves_principal[$i];
					$ejecucion[$claves_principal[$i]] = $datos_principal[$i];
				}
				$preparacion = $preparacion_into . ', ' . $claves[0] . ') ' . $preparacion_values . ', :' . $claves[0] . ');';
				for($i=0; $i<count($datos[0]); $i++){
					$ejecucion[$claves[0]] = $datos[0][$i];
					$stmt = Conexion::getpdo()->prepare($preparacion);
					$stmt->execute($ejecucion);
				}
			}
		}
	}
	
	//En caso de que datos[0] sea un conjunto se ignoran el resto de datos y solo se usa el conjunto datos[0] con clave claves[0]
	function use_database($editar, $base, $claves, $datos, $claves_principal, $datos_principal){
		if(count($claves) == count($datos) && count($claves_principal) == count($datos_principal)){ //En caso de que no haya un mismo numero de clavees y datos, mensaje de error
			if(count($claves_principal)>0){ //En caso de que no haya clave principal, mensaje de error
				if($editar){
					$preparacion = 'SELECT * FROM ' . $base . ' WHERE ' . $claves_principal[0] . ' = :' . $claves_principal[0];
					$ejecucion[$claves_principal[0]] = $datos_principal[0];
					for($i=1; $i<count($claves_principal); $i++){
						$preparacion = $preparacion . ' AND ' . $claves_principal[$i] . ' = :' . $claves_principal[$i];
						$ejecucion[$claves_principal[$i]] = $datos_principal[$i];
					}
					$preparacion = $preparacion . ';';
					$stmt = Conexion::getpdo()->prepare($preparacion);
					$stmt->execute($ejecucion);
					$resultado = $stmt->fetchAll();
					if(count($resultado) == 0){ //Comprobamos si se está intentando editar una tabla inexistente y de ser así pasamos a insertar
						database_insert(Conexion::getpdo(), $base, $claves, $datos, $claves_principal, $datos_principal);
					}
					else{
						if(count($claves)>0){ //En caso de estar editando y no haber datos a editar, mensaje de error
							database_update(Conexion::getpdo(), $base, $claves, $datos, $claves_principal, $datos_principal);
						}
						else{
							$_SESSION[CampoSession::ERROR]='Se ha intentado hacer un UPDATE sin tener datos que insertar';
							header('location: ' . $_SERVER["DOCUMENT_ROOT"] . '/panel.php');
						}
					}
				}
				else{ //Preparamos el insert con las claves principales (ya que esta aprte es igual independientemente de si tratamos con un insert simple o múltiple
					database_insert(Conexion::getpdo(), $base, $claves, $datos, $claves_principal, $datos_principal);
				}
			}
			else{
				$_SESSION[CampoSession::ERROR]='Se ha intentado hacer un INSERT o UPDATE sin tener claves principales';
				header('location: ' . $_SERVER["DOCUMENT_ROOT"] . '/panel.php');
			}
		}
		else{
			$_SESSION[CampoSession::ERROR]='Se ha intentado hacer un INSERT o UPDATE teniendo una cantidad distinta de datos y claves';
			header('location: ' . $_SERVER["DOCUMENT_ROOT"] . '/panel.php');
		}
	}
?>