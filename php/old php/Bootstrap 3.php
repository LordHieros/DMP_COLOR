<?php
	//etiquetas form y button, juntas al no aparecer en ningún otro sitio
	function make_form_old($form, $editar, $resultado){
		//Iniciamos el string que contendrá el código html del formulario
		$formulario = '<form action="' . $form['action'] . '" method="post"';
		if(array_key_exists('opt', $form)){ //De haberlas añadimos las opciones
			$formulario = $formulario . $form['opt'];
		}
		if(array_key_exists('class', $form)){ //De haberlas añadimos las clases
			$formulario = $formulario . ' class="' . $form['class'] . '"';
		}
		$formulario = $formulario . '>';
		//inicializamos el contenido, que tendrá todos los campos más el boton de submit
		$contenido = '';
		for($i=0; $i<count($form['inputs']); $i++){//Creamos los inputs
			$contenido = $contenido . make_input($form['inputs'][$i], $editar,  $resultado);
		}
		if(!array_key_exists('etiqueta', $form['submit'])){ //Si no se especifica etiqueta para el submit usar el nombre capitalizado
			$form['submit']['etiqueta'] = ucfirst($form['submit']['name']);
		}
		$contenido = $contenido . make_fieldset('<button type="submit" name="' . $form['submit']['name'] . '" value="' . $form['submit']['value'] . '">' . $form['submit']['etiqueta'] . '</button>', '', ''); //Creamos el boton de submit
		$formulario = $formulario . make_fieldset($contenido, $form['legend'], '') . '</form>
		'; //Cerramos el formulario
		return $formulario;
	}
	
	//Permite crear opciones para elegir, permitiendo formatear, mandar como formulario o referencia y crear los botones automáticamente
	function make_opciones($datos){
		$opciones = '';
		if(array_key_exists('opt', $datos)){ //Si hay opciones comunes se guardan en variable y borran
			$allopt = $datos['opt'];
			unset($datos['opt']);
		}
		if(array_key_exists('class', $datos)){ //Si hay clases comunes se guardan en variable y borran
			$mainclass = $datos['class'];
			unset($datos['class']);
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
			if(isset($mainclass)){ //Si hay clases de conjunto se añaden
				$class = ' class="' . $mainclass . '"';
			}
			$opciones = $opciones . '<ul' . $class . '>
			';
			for($i=0; $i<count($datos); $i++){
				$opciones = $opciones . '<li';
				$nombre = $datos[$i]['nombre']; //Se guarda el nombre, pues se usa mucho
				$thisopt = ''; //Se crea almacén de opciones
				if(array_key_exists('class', $datos[$i])){ //Si hay clases específicas se añaden
					$opciones = $opciones .  ' class="' . $datos[$i]['class'] . '"';
				}
				$opciones = $opciones . '>';
				$thisopt = $thisopt;
				if(array_key_exists('opt', $datos[$i])){ //Si hay opciones específicas se añaden
					$thisopt = $thisopt . ' ' . $datos[$i]['opt'] . ' ';
				}
				if(isset($allopt)){ //Si hay opciones de conjunto se añaden
					$thisopt = $thisopt . ' ' . $allopt . ' ';
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
?>