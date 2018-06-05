function calculoIMC() {
	var theForm = document.forms["datos"];
	var talla = theForm.elements["talla"];
	var edad = theForm.elements["edad"];
	var peso = theForm.elements["peso"];
	var IMC = document.getElementById("IMC");
	var numtalla = Number.parseFloat(talla.value);
	var numpeso = Number.parseFloat(peso.value);
	var numedad = Number.parseInt(edad.value);
	var numIMC;
	
	
	if(talla.value=="" || peso.value==""){	
		IMC.innerHTML = "IMC: Introduzca talla y peso.";
	}
	
	else if(isNaN(numtalla) || isNaN(numpeso)){
		IMC.innerHTML = "IMC: Se deben de introducir números reales como talla y peso.";
	}
		
	else{
		numIMC = numpeso/(numtalla*numtalla/10000); // Talla en centímetros
		IMC.innerHTML ="IMC = " + numIMC + ". ";
		if(numedad > 20){
			if (numIMC<18.5){
				IMC.innerHTML += "Este índice de masa corporal indica un peso bajo.";
			}
			if (numIMC>=18.5 && numIMC<25){
				IMC.innerHTML += "Este índice de masa corporal indica un peso normal.";
			}
			if (numIMC>=25 && numIMC<30){
				IMC.innerHTML += "Este índice de masa corporal indica un sobrepeso.";
			}
			if (numIMC>=30){
				IMC.innerHTML += "Este índice de masa corporal indica obesidad.";
			}
		}
		else if (numedad <= 20 && numedad >= 0){
			IMC.innerHTML += "Para conocer la relevancia de este índice hay que comparar con los IMCs relevantes para niños de su edad y sexo.";
		}
		else{
			IMC.innerHTML += "Sin conocer la edad la relevancia de este índice es desconocida.";
		}
	}
}

function calculoDIngreso() {
	var theForm = document.forms["datos"];
	var Ingreso = theForm.elements["fIngreso"].value;
	var Alta = theForm.elements["fAlta"].value
	var fIngreso = new Date(Ingreso);
	var fAlta = new Date(Alta);
	var DIngreso = document.getElementById("DIngreso");
	var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
	var diffDays = Math.ceil(Math.abs((fIngreso.getTime() - fAlta.getTime())/(oneDay)))
	
	
	if(Ingreso == "" || Alta == ""){	
		DIngreso.innerHTML = "Dias de ingreso: Introduzca fechas correctas de ingreso y alta.";
	}
	
	else if (fIngreso > fAlta){
		DIngreso.innerHTML ="La fecha de alta debe de ser posterior a la de ingreso.";
	}
	
	else{
		DIngreso.innerHTML ="Dias de ingreso: " + diffDays + ".";
	}
}

function calculoTNM(){
	var theForm = document.forms["datos"];
	var T = theForm.elements["TNM_T"].value;
	var N = theForm.elements["TNM_N"].value;
	var M = theForm.elements["TNM_M"].value;
	var TNM = document.getElementById("TNM");
	
	if(T == "" || N == "" || M == ""){
		TNM.innerHTML = "Introduzca los valores de T, N y M para calcular el estadio.";
	}
	else if(M == "M1"){
		TNM.innerHTML = "Estadio: IV.";
	}
	else if(M == "M1a"){
		TNM.innerHTML = "Estadio: IVA.";
	}
	else if(M == "M1b"){
		TNM.innerHTML = "Estadio: IVB.";
	}
	else if(T == "TX" || N == "NX"){
		TNM.innerHTML = "Si no hay metástasis distante y no se pueden evaluar el tumor primario o los nodos linfáticos regionales no se puede calcular el estadio";
	}
	else if(T == "Tis" && N == "N0"){
		TNM.innerHTML = "Estadio: 0.";
	}
	else if(T == "Tis" || T == "T0"){
		TNM.innerHTML = "*** CONSULTAR CON FERNANDO ***.";
	}
	else if((T == "T1" || T == "T2") && N == "N0"){
		TNM.innerHTML = "Estadio: I.";
	}
	else if(T == "T3" && N == "N0"){
		TNM.innerHTML = "Estadio: IIA.";
	}
	else if(T == "T4a" && N == "N0"){
		TNM.innerHTML = "Estadio: IIB.";
	}
	else if(T == "T4b" && N == "N0"){
		TNM.innerHTML = "Estadio: IIC.";
	}
	else if(!(T == "T4b") && N == "N2"){
		TNM.innerHTML = "Estadio: III, no se puede concluir si A, B o C sin especificar más la metástasis de los nodos linfáticos regionales.";
	}
	else if(((T == "T1" || T == "T2") && (N == "N1" || N == "N1a" || N == "N1b" || N == "N1c")) || (T == "T1" && N == "N2a")){
		TNM.innerHTML = "Estadio: IIIA.";
	}
	else if(((T == "T3" || T == "T4a") && (N == "N1" || N == "N1a" || N == "N1b" || N == "N1c")) || ((T == "T2" || T == "T3") && N == "N2a") || ((T == "T1" || T == "T2") && N == "N2b")){
		TNM.innerHTML = "Estadio: IIIB.";
	}
	else if((T == "T4a" && N == "N2a") || ((T == "T3" || T == "T4a") && N == "N2b") || (T == "T4b" && !(N == "N0" || N == "NX"))){
		TNM.innerHTML = "Estadio: IIIC.";
	}
}