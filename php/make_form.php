<?php
    final class MakeForm
    {
        //Crea el propio objeto input
        function make_input_tag($input)
        {
            $tag = '<input ';
            if (array_key_exists('class', $input)) {
                $tag = $tag . 'class="' . $input['class'] . '" ';
            }
            $tag = $tag . 'type="' . $input['tipo'] .
                '"' . ' name="' . $input['nombre'] .
                '"' . ' ' . $input['opt'] . '>';
            return $tag;
        }

        //Crea el label que envuelve el objeto
        function make_label($etiqueta, $input)
        {
            $label = '<label>
		';
            if ($input['tipo'] == 'radio' || $input['tipo'] == 'checkbox') {
                $label = $label . make_input_tag($input) . $etiqueta;
            } else {
                if (!array_key_exists('class', $input)) {
                    $input['class'] = '';
                }
                $input['class'] = $input['class'] . ' form-control';
                $label = $label . $etiqueta . make_input_tag($input);
            }
            $label = $label . '
		</label>';
            return $label;
        }

        //Crea multiples labels a partir de items
        function make_labels($items, $leyenda, $class)
        {
            $resultado = '';
            for ($i = 0; $i < count($items); $i++) {
                $contenido = make_label($items[$i]['etiqueta'], $items[$i]['input']) . '
		';
                $resultado = $resultado . make_fieldset($contenido, $leyenda, $class);
            }
            return $resultado;
        }

        //Crea el fieldset que envuelve el campo
        function make_fieldset($contenido, $leyenda, $class)
        {
            $class = $class . ' form-group';
            $fieldset = '<fieldset class="' . $class . '">
		';
            if (!empty($leyenda)) {
                $fieldset = $fieldset . '<legend>' . $leyenda . '</legend>
		';
            }
            $fieldset = $fieldset . $contenido . '</fieldset>
		'; //Finalizar el input
            return $fieldset;
        }

        //Crea un input simple para introducir un valor por teclado
        function make_input_simple($tipo, $nombre, $etiqueta, $oculto, $opt, $class, $edicion)
        {
            $class = '';
            if ($oculto) {
                $class = $class . 'oculto';
            }
            if ($edicion['editar']) {
                $opt = $opt . ' value="' . $edicion['valor'] . '"'; //Si se esta editando poner valor por defecto
            }
            $items[0]['etiqueta'] = $etiqueta;
            $items[0]['input']['tipo'] = $tipo;
            $items[0]['input']['nombre'] = $nombre;
            $items[0]['input']['opt'] = $opt;
            $items[0]['input']['class'] = $class;
            return make_labels($items, '', $class);
        }

        //Crea un input multiopción, como los radio o checkbox
        function make_input_multiple($tipo, $nombre, $etiqueta, $oculto, $opt, $opts, $class, $clases, $hijo, $edicion, $valores, $explicaciones)
        {
            if ($tipo == 'checkbox') { //A los checkbox les convierte el nombre en array
                $thisnombre = $nombre . "[]";
            } else { //Al resto los deja tal cual
                $thisnombre = $nombre;
            }
            $inputm = '';
            for ($i = 0; $i < count($valores); $i++) {
                $items[$i]['etiqueta'] = $valores[$i];
                $items[$i]['input']['tipo'] = $tipo;
                $items[$i]['input']['nombre'] = $thisnombre;
                $items[$i]['input']['class'] = $class;
                $items[$i]['input']['opt'] = $opt . ' value="' . $valores[$i] . '"'; //Añadir el valor a las opciones; usamos "thisopt" para asegurar que no se repitan
                if (is_array($opts)) {
                    if (array_key_exists($i, $opts)) {
                        $items[$i]['input']['opt'] = $items[$i]['input']['opt'] . $opts[$i]; //Si hay opcion personalizada para este indice añadirla
                    }
                }
                if (is_array($clases)) {
                    if (array_key_exists($i, $clases)) {
                        $items[$i]['input']['class'] = $items[$i]['input']['class'] . $clases[$i]; //Si hay clase personalizada para este indice añadirla
                    }
                }
                if ($edicion['editar']) {//Si se está editando
                    for ($j = 0; $j < count($edicion['valores']); $j++) {
                        if (array_key_exists($nombre, $edicion['valores'][$j])) {//Impedir accesos prohibidos
                            if ($edicion['valores'][$j][$nombre] == $valores[$i]) {
                                $items[$i]['input']['opt'] = $items[$i]['input']['opt'] . ' checked '; //En caso de estar editando marcar valor por defecto
                            }
                        }
                    }
                }
                //Añadimos las expliaciones de las etiquetas de haberlas
                if (is_array($explicaciones)) {
                    if (array_key_exists($i, $explicaciones)) {
                        $items[$i]['etiqueta'] = $items[$i]['etiqueta'] . ': ' . $explicaciones[$i];
                    }
                }
            }
            $contenido = make_labels($items, '', $tipo) . $hijo;
            $class = '';
            if ($oculto) {
                $class = $class . 'oculto';
            }
            return make_fieldset($contenido, $etiqueta, $class);
        }

        //Para crear inputs; esta función se encarga de sanear las variables de entrada
        function make_single_input($datos, $editar, $resultado)
        {
            //Tipo y nombre siempre son obligatorios
            $tipo = $datos['tipo'];
            $nombre = $datos['nombre'];
            $required = $datos['required']; //Campo booleano
            $edicion['editar'] = $editar;
            if (array_key_exists('etiqueta', $datos)) { //En caso de no haber etiqueta por defecto poner nombre capitalizado
                $etiqueta = $datos['etiqueta'];
            } else {
                $etiqueta = ucfirst($nombre);
            }
            if (array_key_exists('oculto', $datos)) { //En caso de estar oculto se carga el valor, en caso contrario se asume que no está oculto
                $oculto = $datos['oculto'];
            } else {
                $oculto = false;
            }
            if ($required) { //En caso de ser campo requerido añadirlo a las opciones
                $opt = 'required';
            } else {
                $opt = '';
            }
            if (array_key_exists('opt', $datos)) { //En caso de haber opciones añadirlas
                $opt = $datos['opt'] . ' ' . $opt;
            }
            //En caso de input multiopción (valor es obligatorio)
            if ($tipo == 'checkbox' || $tipo == 'radio') {
                if (array_key_exists('hijo', $datos)) { //En caso de tener que poner un "hijo" para el multiopción poner aqui
                    $hijo = $datos['hijo'];
                } else {
                    $hijo = '';
                }
                if (array_key_exists('opts', $datos)) { //En caso de tener que poner opciones específicas para cada apartado poner aqui
                    $opts = $datos['opts'];
                } else {
                    $opts = '';
                }
                if (array_key_exists('clases', $datos)) { //En caso de haber clases únicas poner aquí
                    $clases = $datos['clases'];
                } else {
                    $clases = '';
                }
                $valores = $datos['valores']; //Pasar opciones del input multicampo
                if ($editar) { //Si se está editando, pasar valores de la base de datos para chequear
                    $edicion['valores'] = $resultado;
                }
                if (array_key_exists('explicaciones', $datos)) {
                    $explicaciones = $datos['explicaciones'];
                } else {
                    $explicaciones = '';
                }
                //TODO añadir class si se ve que hace falta
                $input = make_input_multiple($tipo, $nombre, $etiqueta, $oculto, $opt, $opts, '', $clases, $hijo, $edicion, $valores, $explicaciones);
            } //Resto de casos
            else {
                if ($editar) {
                    if (array_key_exists($nombre, $resultado[0])) {
                        $edicion['valor'] = $resultado[0][$nombre]; //Si se esta editando poner valor por defecto, si existe
                    } else {
                        $edicion['valor'] = '';
                    }
                }
                //TODO añadir class si se ve que hace falta
                $input = make_input_simple($tipo, $nombre, $etiqueta, $oculto, $opt, '', $edicion);
            }
            return $input;
        }

        function make_nested_input($datos, $editar, $resultado)
        {
            $datos['hijo'] = ''; //Crea el string donde se guardarán todos los datos "encapsulados"
            $oculto = false; //Por defecto no se usan las opciones de ocultar el nesteo
            $hijos_requeridos = false;
            if (array_key_exists('oculto', $datos['nest'])) {
                if ($datos['nest']['oculto'] == 'true') {
                    $oculto = true;
                }
                unset($datos['nest']['oculto']);
            }
            for ($i = 0; $i < count($datos['nest']); $i++) {
                if (!array_key_exists('opt', $datos['nest'][$i])) { //Inicializamos las opciones en caso de no existir
                    $datos['nest'][$i]['opt'] = '';
                }
                if (!array_key_exists('class', $datos['nest'][$i])) { //Inicializamos la clase en caso de no existir
                    $datos['nest'][$i]['class'] = '';
                }
                if ($oculto) {
                    $datos['nest'][$i]['oculto'] = true; //Hacemos que todos los hijos pertenezcan a la clase "oculto"
                    if ($datos['nest'][$i]['required']) { //Si los hijos ocultos son requeridos hacemos que pertenezcan a la clase "requerido_si", desmarcamos requerido y  marcamos que existen hijos requeridos
                        $datos['nest'][$i]['required'] = false;
                        $datos['nest'][$i]['class'] = $datos['nest'][$i]['class'] . ' requerido_si';
                        $hijos_requeridos = true;
                    }
                }
                $datos['hijo'] = $datos['hijo'] . make_input($datos['nest'][$i], $editar, $resultado); //Cargamos todos los hijos en el string
            }
            unset($datos['nest']); //Borramos el "nido"
            if ($oculto) {
                for ($i = 0; $i < count($datos['valores']); $i++) { //Añadimos a las opciones específicas de cada valor el mostrar correspondiente
                    if (!array_key_exists('opts', $datos)) {
                        $datos['opts'][$i] = ''; //Iniciamos las opciones específicas del indice de no existir
                    } else if (!array_key_exists($i, $datos['opts'])) {
                        $datos['opts'][$i] = ''; //Iniciamos las opciones específicas del indice de no existir
                    }
                    if (!array_key_exists('clases', $datos)) {
                        $datos['clases'][$i] = ''; //Iniciamos las clases específicas del indice de no existir
                    } else if (!array_key_exists($i, $datos['clases'])) {
                        $datos['clases'][$i] = ''; //Iniciamos las clases específicas del indice de no existir
                    }
                    if ($i == (count($datos['valores']) - 1)) { //Para el último ponemos mostrar a true y si hay hijos requeridos ponemos requerir a true
                        if ($hijos_requeridos == true) {
                            $datos['opts'][$i] = $datos['opts'][$i] . ' onchange="mostrar(this, true); requerir(this, true);"';
                            $datos['clases'][$i] = $datos['clases'][$i] . ' revelador requeridor';
                        } else {
                            $datos['opts'][$i] = $datos['opts'][$i] . ' onchange="mostrar(this, true)"';
                            $datos['clases'][$i] = $datos['clases'][$i] . ' revelador';
                        }
                    } else { //Para el resto mostrar a false y si hay hijos requeridos ponemos requerir a false
                        if ($hijos_requeridos == true) {
                            $datos['opts'][$i] = $datos['opts'][$i] . ' onchange="mostrar(this, false); requerir(this, false);"';
                        } else {
                            $datos['opts'][$i] = $datos['opts'][$i] . ' onchange="mostrar(this, false)"';
                        }
                    }
                }
            }
            return make_input($datos, $editar, $resultado);
        }

        //Centraliza para decidir que funcion llamar
        function make_input($datos, $editar, $resultado)
        {
            if (array_key_exists('nest', $datos)) { //En caso de ser nested, mandar a nested
                return make_nested_input($datos, $editar, $resultado);
            } else if (array_key_exists('tipo', $datos)) { //En caso de no ser nested pero tener tipo mandar a single
                return make_single_input($datos, $editar, $resultado);
            } else if (array_key_exists('etiqueta', $datos) && array_key_exists('hijo', $datos)) { //En caso de ni ser nested ni tener tipo pero tener etiqueta e hijo crear fieldset. Solo se debiera llegar teniendo hijo en caso de que originalmente fuese nested.
                return make_fieldset($datos['hijo'], $datos['etiqueta'], '');
            } else { //En caso de no tener etiqueta e hijo, tipo o nest devolver vacio
                return '';
            }
        }

        /**
         * Crea el html del formulario
         *
         * @param Formulario $form
         * @return string
         */
        function make_form($form)
        {
            //Iniciamos el string que contendrá el código html del formulario
            $formulario = '
		<form action="' . $form->getAction() . '" method="post"';
            if (array_key_exists('opt', $form)) { //De haberlas añadimos las opciones
                $formulario = $formulario . $form['opt'];
            }
            if (array_key_exists('class', $form)) { //De haberlas añadimos las clases
                $formulario = $formulario . ' class="' . $form['class'] . '"';
            }
            $formulario = $formulario . ' data-spy="scroll" data-target=".nav" data-offset="50">
		<div class="row">
		';
            //inicializamos el contenido, que tendrá todos los campos más el boton de submit
            $contenido = '';
            $tabs = '<div class="col-3">
		<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
		';
            for ($i = 0; $i < count($form['inputs']); $i++) {//Creamos los inputs
                $selected = 'false';
                if (array_key_exists('nombre', $form['inputs'][$i])) {
                    $name = $form['inputs'][$i]['nombre'];
                } else {
                    $name = $form['inputs'][$i]['etiqueta'];
                }
                if (array_key_exists('etiqueta', $form['inputs'][$i])) {
                    $label = $form['inputs'][$i]['etiqueta'];
                } else {
                    $label = $form['inputs'][$i]['nombre'];
                }
                $tabs = $tabs . '<a class="nav-link';
                $contenido = $contenido . '<div class="tab-pane fade';
                if ($i == 0) {
                    $contenido = $contenido . ' show active';
                    $tabs = $tabs . ' active';
                    $selected = 'true';
                }
                $tabs = $tabs . '" id="v-pills-' . $name . '-tab" data-toggle="pill" href="#v-pills-' . $name . '" role="tab" aria-controls="v-pills-' . $name . '" aria-selected="' . $selected . '">' . $label . '</a>
			';
                $contenido = $contenido . '" id="v-pills-' . $name . '" role="tabpanel" aria-labelledby="v-pills-' . $name . '-tab">
			';
                $contenido = $contenido . make_input($form['inputs'][$i], $editar, $resultado);
                $contenido = $contenido . '
			</div>
			';
            }
            $tabs = $tabs . '</div>
		</div>
		';
            if (!array_key_exists('etiqueta', $form['submit'])) { //Si no se especifica etiqueta para el submit usar el nombre capitalizado
                $form['submit']['etiqueta'] = ucfirst($form['submit']['name']);
            }
            $contenido = $contenido . make_fieldset('<button type="submit" name="' . $form['submit']['name'] . '" value="' . $form['submit']['value'] . '">' . $form['submit']['etiqueta'] . '</button>', '', ''); //Creamos el boton de submit
            $formulario = $formulario . $tabs . '
		<div class="col-9">
		<div class="tab-content" id="v-pills-tabContent">' . $contenido .
                '</div>
		</div> 
		</div>
		</form>
		'; //Cerramos el formulario
            return $formulario;
        }

        function merge_resultado($resultado_objetivo, $resultado_fuente, $clave_objetivo)
        {
            $cut = explode('_', $clave_objetivo);
            $clave_fuente = $cut[count($cut) - 1];
            for ($i = 0; $i < count($resultado_fuente); $i++) {
                $resultado_objetivo[$i][$clave_objetivo] = $resultado_fuente[$i][$clave_fuente];
            }
            return $resultado_objetivo;
        }
    }
?>