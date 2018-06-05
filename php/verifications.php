<?php

	function verifica_diagnostico(){
		$nasi=$_SESSION[CampoSession::NASI];
		$fechaDiagnostico = $_SESSION[CampoSession::FECHA_DIAGNOSTICO];
		return 'OK';
	}
	
	function verifica_intervencion(){
		$nasi=$_SESSION[CampoSession::NASI];
		$fechaDiagnostico = $_SESSION[CampoSession::FECHA_DIAGNOSTICO];
		$fechaIntervencion = $_SESSION[CampoSession::FECHA_INTERVENCION];
		return 'OK';
	}

?>