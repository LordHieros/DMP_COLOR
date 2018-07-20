<?php
    include_once "CampoSession.php";
    include_once "Utils.php";
	if(!isset($_SESSION)){
		session_start(); // Starting Session
	}
	
	//Mirar
	function make_boton_post($nombre, $objetivo, $valor, $mensaje, $opt){
	    $action = '';
	    if(!empty($objetivo)){
            $action = 'action="' . $objetivo . '"';
        }
		return '<a' . $opt . '><form ' . $action . ' method="post">
		<input type="hidden" name="' . $nombre . '" value="' . $valor . '">
		<input type="submit" class="looklikelink" value="' . $mensaje . '">
		</form></a>
		'; //Por cada usuario crear un boton, si se clica se manda a la página indicada con el valor del botón clicado como "POST"
	}
	
	//Imprimir botones que redirigen a la página objetivo
	function make_boton_redireccion($nombre, $objetivo, $opt){
		return '<a href="' . $objetivo . '" ' . $opt . '>
		' . $nombre . '
		</a>
		';
	}
	
	//Permite crear opciones para elegir, permitiendo formatear, mandar como formulario o referencia y crear los botones automáticamente
	function make_opciones($datos){
		$opciones = '';
		$mainclass = '';
		$vacio = '';
		if(array_key_exists('nav', $datos)){ //Si hay opciones comunes se guardan en variable y borran
			$nav = $datos['nav'];
			unset($datos['nav']);
		}
		else{
			$nav = false;
		}
		if(array_key_exists('opt', $datos)){ //Si hay opciones comunes se guardan en variable y borran
			$allopt = $datos['opt'];
			unset($datos['opt']);
		}
		if(array_key_exists('class', $datos)){ //Si hay clases comunes se guardan en variable y borran
			$mainclass = $mainclass . $datos['class'];
			unset($datos['class']);
		}
		if($nav){
			$mainclass = $mainclass . ' navbar-nav';
		}
		if(array_key_exists('vacio', $datos)){ //Si hay mensaje en caso de conjunto vacio se guarda en variable y borra
			$vacio = $datos['vacio'];
			unset($datos['vacio']);
		}
		if(array_key_exists('titulo', $datos)){ //Si hay un título especificado se imprime y borra
			$titulo = $datos['titulo'];
			unset($datos['titulo']);
		}
		if(count($datos) > 0){ //En caso de que haya algo aparte del título
			if(isset($titulo)){ //Si hay un título especificado se imprime y borra
				$opciones = $opciones . '<h4>' . $titulo . '</h4>
				';
			}
			$class = '';
			if(!empty($mainclass)){ //Si hay clases de conjunto se añaden
				$class = ' class="' . $mainclass . '"';
			}
			$opciones = $opciones . '<ul' . $class . '>
			';
			for($i=0; $i<count($datos); $i++){
				$opciones = $opciones . '<li';
				$nombre = $datos[$i]['nombre']; //Se guarda el nombre, pues se usa mucho
				$thisopt = ''; //Se crea almacén de opciones
				$thisclass = ''; //Se crea almacén de clases
				if(array_key_exists('class', $datos[$i])){ //Si hay clases específicas se añaden
					$thisclass = $thisclass . $datos[$i]['class'];
				}
				if($nav){
					$thisclass = $thisclass . ' nav-item';
				}
				if(!empty($thisclass)){ //Si hay clases específicas se añaden
					$opciones = $opciones .  ' class="' . $thisclass . '"';
				}
				$opciones = $opciones . '>';
				if(array_key_exists('opt', $datos[$i])){ //Si hay opciones específicas se añaden
					$thisopt = $thisopt . ' ' . $datos[$i]['opt'] . ' ';
				}
				if(isset($allopt)){ //Si hay opciones de conjunto se añaden
					$thisopt = $thisopt . ' ' . $allopt . ' ';
				}
				if($nav){
					$thisopt = $thisopt . ' class="nav-link"';
				}
				if($datos[$i]['tipo'] == 'redirección'){ //Si es botón de redirección se crea
					$opciones = $opciones . make_boton_redireccion($nombre, $datos[$i]['objetivo'], $thisopt);
				}
				else if ($datos[$i]['tipo'] == 'post'){
					if(!array_key_exists('mensaje', $datos[$i])){//Si no se ha especificado mensaje se asume que el mensaje es el valor
						$datos[$i]['mensaje'] = $datos[$i]['valor'];
					}
					$opciones = $opciones . make_boton_post($nombre, $datos[$i]['objetivo'], $datos[$i]['valor'], $datos[$i]['mensaje'], $thisopt);
				}
				$opciones = $opciones . '</li>
				';
			}
			$opciones = $opciones . '</ul>
			';
		}
		else{//En caso de conjunto vacio imprime mensaje, sin poner título
			$opciones = $opciones . '<p>' . $vacio . '</p>'; 
		}
		return $opciones;
	}
	
	//Para imprimir los botones de control. Pasa los parámetros prefijados a make opciones
	function make_controles() {
		$datos['nav'] = true;
		$datos[0]['tipo'] = 'redirección';
		$datos[0]['nombre'] = 'Cerrar sesión';
		$datos[0]['objetivo'] = 'php/logout.php';
		if(basename($_SERVER['PHP_SELF'])!='panel.php'){
			$datos[1]['tipo'] = 'redirección';
			$datos[1]['nombre'] = 'Volver al panel de usuario';
			$datos[1]['objetivo'] = 'panel.php';
		}
		$datos['class'] = 'ml-auto';
		return make_opciones($datos);
	}
	
	//Para las consultas en las que haya que elegir una opción a la que ir, introducir resultado de la base de datos, clave de la variable que se desea imprimir, destino al que hay que ir en caso de hacer click y mensaje en caso de que no haya resultados. Pasa los parámetros a make opciones
	function make_eleccion($datos) {
        $i=0;
	    foreach (array_keys($datos['resultado']) as $clave){
			if(array_key_exists('nombre', $datos)){//En caso de haber especificado un nombre para el post se carga
				$datos[$i]['nombre'] = $datos['nombre'];
			}
			else{ //En caso contrario se usa la propia clave
				$datos[$i]['nombre'] = $datos['clave'];
			}
			$datos[$i]['objetivo'] = $datos['destino'];
			$datos[$i]['tipo'] = 'post';
            $datos[$i]['valor'] = $clave;
            $datos[$i]['mensaje'] = $datos['resultado'][$clave];
            $i++;
		}
		unset($datos['nombre']);
		unset($datos['clave']);
		unset($datos['destino']);
		unset($datos['resultado']);
		return make_opciones($datos);
	}
	
	//Para crear la barra de navegación
	function make_navbar($datos){
		$datos['nav'] = true;
		$datos['class'] = 'mr-auto';
		return '<nav class="navbar navbar-expand-lg bg-dark navbar-dark fixed-top">
			<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNavDropdown">
			<div class="navbar-header">
			  <span class="navbar-brand">DMP-COLOR</span>
			</div>
			' . make_opciones($datos) . make_controles() . '
			</div>
		</nav>';
	}
	
	//Para centralizar la impresión de datos y poder cambiar estilo o similares con facilidad
	function make_consulta($contenido){
		if(empty($contenido)) return ''; //En caso de que se pase un string vacío no se salta la línea
		return '<p>' . $contenido . '</p>';
	}
	
	//Para obtener los resultados de una consulta y presentarlos de forma ordenada
	function make_resultados($nombre, $resultado, $clave) {
		$consulta = $resultado[0][$clave];
		for($i=1; $i<count($resultado); $i++){
			$consulta = $consulta . ', ' . $resultado[$i][$clave];
		} //Imprimimos con el nombre en negrita tras concatenar los resultados, separados por comas de haber varios
		return make_consulta('<span class="negrita">' . $nombre . '</span>: ' . $consulta);
	}
	
	//Para centralizar la impresión de datos de consulta
	function make_consultas($datos) {
		$consultas = '';
		for($i=0; $i<count($datos); $i++){
			if(array_key_exists('resultado' ,$datos[$i])){ //Si se han introducido los datos mínimos
				if(array_key_exists('nombre', $datos[$i])){
					if(!empty($datos[$i]['resultado'])){ //Si el resultado no está vacio
						if(is_array($datos[$i]['resultado'])){ //Si el resultado es un conjunto de resultados
							if(array_key_exists('clave', $datos[$i])){ //Si se ha especificado clave se pasa
								$clave = $datos[$i]['clave'];
							}
							else{ //Si no se ha especificado clave se pasa nombre como clave
								$clave = $datos[$i]['nombre'];
							}
							if(!empty($datos[$i]['resultado'][0][$clave])){ //Si el resultado correspondiente a la clave no está vacío se pasa a make consulta
								$consultas = $consultas . make_resultados($datos[$i]['nombre'], $datos[$i]['resultado'], $clave);
							}
							else{ //Si está vacío
								if(!empty($datos[$i]['vacio'])){ //Si se ha especificado mensaje en caso de no haber resultados se imprime
									 $consultas = $consultas . make_consulta($datos[$i]['vacio']);
								}	
								else{ //Si no se ha especificado se imprime un mensaje de error
									$consultas = $consultas . '<p class="error">Los resultados de la consulta están vacíos y no hay mensaje previsto</p>';
								}
							}
						}
						else{ //Si el resultado no es un conjunto se imprime directamente, con el nombre en negrita
							$consultas = $consultas . make_consulta('<span class="negrita">' . $datos[$i]['nombre'] . '</span>: ' . $datos[$i]['resultado']);
						}
					}
					else{ //En caso de no tener resultados
						if(!empty($datos[$i]['vacio'])){ //Si se ha especificado mensaje en caso de no haber resultados se imprime
							 $consultas = $consultas . make_consulta($datos[$i]['vacio']);
						}	
						else{ //Si no se ha especificado se imprime un mensaje de error
							$consultas = $consultas . '<p class="error">Los resultados de la consulta están vacíos y no hay mensaje previsto</p>';
						}
					}
				}
				else{ //Si el resultado no es un conjunto se imprime directamente
					$consultas = $consultas . make_consulta($datos[$i]['resultado']);
				}
			}
			else{ //Si no se han especificado los datos mínimos se imprime error
				$consultas = $consultas . '<p class="error">No se ha introducido nombre y resultado para la consulta</p>';
			}
		}
		return $consultas;
	}
	
	//Dado un título, un cuerpo y un encabezado opcional crea la arquitectura de la página
	function make_html($html){
		//Antiguo estilo, sustituído por bootstrap js/css <link rel="stylesheet" type="text/css" href="./css/Estilo.css">
		//El <div> container fluid tambien es parte de bootstrap
        $corrections = './css/BootstrapCorrections.css'; //Para evitar warnings
		$bootstrap = '
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://bootswatch.com/4/cerulean/bootstrap.min.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="' . $corrections . '">';
		$pagina = '<!DOCTYPE html>
		<html lang="es">
		<head>
		<meta charset="UTF-8">'
		. $bootstrap .
		'<title>' . $html['titulo'] . '</title>';
		if(array_key_exists('head', $html)){
			$pagina = $pagina . '
			' . $html['head'];
		}
		$pagina = $pagina . '
		</head>
		<body>
		<div class="container-fluid">
		<h2>' . $html['titulo'] . '</h2>
		';
		if(array_key_exists('encabezado', $html)){
			$pagina = $pagina . $html['encabezado'];
		}
		$pagina = $pagina . $html['body'];
		$pagina = $pagina . 
		'</div>
		</body>
		</html>';
        Utils::console_log('SESSION: ' . print_r($_SESSION, true), true);
		return $pagina;
	}