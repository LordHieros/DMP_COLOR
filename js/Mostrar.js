//Para activar/desactivar los elementos correspondientes cuando se clica en el radio. Se va tres pasos atras (de input a label, de label a div, de div a fieldset y otra vez), se encuentran los elementos con clase "oculto" y se activan/desactivan, dependiendo de lo que se prefiera
function mostrar(elem, mostrar){
	var elementos = elem.parentNode.parentNode.parentNode.getElementsByClassName("oculto");
	var i;
	for(i=0; i<elementos.length; i++){
		if(mostrar){
			elementos[i].disabled = false;
		}
		else{
			elementos[i].disabled = true;
		}
	}
}
//Para requerir/"desrequerir" los elementos correspondientes cuando se clica en el radio.
function requerir(elem, requerir){
	var elementos = elem.parentNode.parentNode.parentNode.getElementsByClassName("requerido_si");
	var i;
	for(i=0; i<elementos.length; i++){
		if(requerir){
			elementos[i].required = true;
		}
		else{
			elementos[i].required = false;
		}
	}
}
//Para requerir/ocultar en caso de que el elemento esté marcado por defecto (al cargar los datos de la base de datos).
function load(){
	var revelando=document.getElementsByClassName("revelador");
	var requiriendo=document.getElementsByClassName("requeridor");
	console.log('loadeando, ' + revelando.length);
	console.log(document);
	for(i=0;i<revelando.length;i++){ //Este bucle nos permite mostrar si el elemento revelador está marcado
		if(revelando[i].checked){
			mostrar(revelando[i],true);
		}
		else{
			mostrar(revelando[i],false);
		}
	}
	for(i=0;i<requiriendo.length;i++){ //Este bucle nos permite requerir si el elemento requeridor está marcado
		if(requiriendo[i].checked){
			requerir(requiriendo[i],true);
		}
		else{
			requerir(requiriendo[i],false);
		}
	}
}
$(document).ready(function() {
    load();
});
