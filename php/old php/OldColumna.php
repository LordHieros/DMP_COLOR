<?php

abstract class OldColumna
{

    // Propios de Hospital
    const idHospital = array(
        CampoColumna::nombre => 'idHospital',
        CampoColumna::tabla => Tabla::Hospitales,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'idHospital'
    );

    const numeroCamas = array(
        CampoColumna::nombre => 'numeroCamas',
        CampoColumna::tabla => Tabla::Hospitales,
        CampoColumna::tipo => TipoColumna::INT,
        CampoColumna::columna => 'numeroCamas'
    );

    // Propios de Usuario
    const nombreUsuario = array(
        CampoColumna::nombre => 'nombreUsuario',
        CampoColumna::tabla => Tabla::Usuarios,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'nombreUsuario'
    );

    const contrasenha = array(
        CampoColumna::nombre => 'contrasenha',
        CampoColumna::tabla => Tabla::Usuarios,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'contrasenha'
    );

    const administrador = array(
        CampoColumna::nombre => 'administrador',
        CampoColumna::tabla => Tabla::Usuarios,
        CampoColumna::tipo => TipoColumna::BOOL,
        CampoColumna::columna => 'administrador'
    );

    // Propios de Filiacion
    const nasi = array(
        CampoColumna::nombre => 'nasi',
        CampoColumna::tabla => Tabla::Filiaciones,
        CampoColumna::tipo => TipoColumna::INT,
        CampoColumna::columna => 'NASI'
    );

    const nhc = array(
        CampoColumna::nombre => 'nhc',
        CampoColumna::tabla => Tabla::Filiaciones,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'NHC'
    );

    // Propios de Diagnostico
    const fechaDiagnostico = array(
        CampoColumna::nombre => 'fechaDiagnostico',
        CampoColumna::tabla => Tabla::Diagnosticos,
        CampoColumna::tipo => TipoColumna::DATE,
        CampoColumna::columna => 'fechaDiagnostico'
    );

    const sexo = array(
        CampoColumna::nombre => 'sexo',
        CampoColumna::tabla => Tabla::Diagnosticos,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'sexo'
    );

    const edad = array(
        CampoColumna::nombre => 'edad',
        CampoColumna::tabla => Tabla::Diagnosticos,
        CampoColumna::tipo => TipoColumna::INT,
        CampoColumna::columna => 'edad'
    );

    const talla = array(
        CampoColumna::nombre => 'talla',
        CampoColumna::tabla => Tabla::Diagnosticos,
        CampoColumna::tipo => TipoColumna::INT,
        CampoColumna::columna => 'talla'
    );

    const peso = array(
        CampoColumna::nombre => 'peso',
        CampoColumna::tabla => Tabla::Diagnosticos,
        CampoColumna::tipo => TipoColumna::INT,
        CampoColumna::columna => 'peso'
    );

    const fechaIngreso = array(
        CampoColumna::nombre => 'fechaIngreso',
        CampoColumna::tabla => Tabla::Diagnosticos,
        CampoColumna::tipo => TipoColumna::DATE,
        CampoColumna::columna => 'fechaIngreso'
    );

    const fechaEndoscopia = array(
        CampoColumna::nombre => 'fechaEndoscopia',
        CampoColumna::tabla => Tabla::Diagnosticos,
        CampoColumna::tipo => TipoColumna::DATE,
        CampoColumna::columna => 'fechaEndoscopia'
    );

    const fechaBiopsia = array(
        CampoColumna::nombre => 'fechaBiopsia',
        CampoColumna::tabla => Tabla::Diagnosticos,
        CampoColumna::tipo => TipoColumna::DATE,
        CampoColumna::columna => 'fechaBiopsia'
    );

    const fechaPresentacion = array(
        CampoColumna::nombre => 'fechaPresentacion',
        CampoColumna::tabla => Tabla::Diagnosticos,
        CampoColumna::tipo => TipoColumna::DATE,
        CampoColumna::columna => 'fechaPresentacion'
    );

    const cea = array(
        CampoColumna::nombre => 'cea',
        CampoColumna::tabla => Tabla::Diagnosticos,
        CampoColumna::tipo => TipoColumna::INT,
        CampoColumna::columna => 'CEA'
    );

    const hemoglobina = array(
        CampoColumna::nombre => 'hemoglobina',
        CampoColumna::tabla => Tabla::Diagnosticos,
        CampoColumna::tipo => TipoColumna::FLOAT,
        CampoColumna::columna => 'hemoglobina'
    );

    const albumina = array(
        CampoColumna::nombre => 'albumina',
        CampoColumna::tabla => Tabla::Diagnosticos,
        CampoColumna::tipo => TipoColumna::FLOAT,
        CampoColumna::columna => 'albumina'
    );

    const asa = array(
        CampoColumna::nombre => 'asa',
        CampoColumna::tabla => Tabla::Diagnosticos,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'ASA'
    );

    const anticoagulantes = array(
        CampoColumna::nombre => 'anticoagulantes',
        CampoColumna::tabla => Tabla::Diagnosticos,
        CampoColumna::tipo => TipoColumna::BOOL,
        CampoColumna::columna => 'anticoagulantes'
    );

    const diagnosticoCerrado = array(
        CampoColumna::nombre => 'diagnosticoCerrado',
        CampoColumna::tabla => Tabla::Diagnosticos,
        CampoColumna::tipo => TipoColumna::BOOL,
        CampoColumna::columna => 'cerrado'
    );

    // Dependientes de Diagnostico, 1:1-0
    const hayMetastasisHepatica = array(
        CampoColumna::nombre => 'hayMetastasisHepatica',
        CampoColumna::tabla => Tabla::MetastasisHepaticas,
        CampoColumna::tipo => TipoColumna::TABLE_PRESENT,
        CampoColumna::columna => '1'
    );

    const hayCirugiaHepatica = array(
        CampoColumna::nombre => 'hayCirugiaHepatica',
        CampoColumna::tabla => Tabla::CirugiasHepaticas,
        CampoColumna::tipo => TipoColumna::TABLE_PRESENT,
        CampoColumna::columna => '1'
    );

    const tipoCirugiaHepatica = array(
        CampoColumna::nombre => 'tipoCirugiaHepatica',
        CampoColumna::tabla => Tabla::CirugiasHepaticas,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'tipo'
    );

    const tecnicaCirugiaHepatica = array(
        CampoColumna::nombre => 'tecnicaCirugiaHepatica',
        CampoColumna::tabla => Tabla::CirugiasHepaticas,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'tecnica'
    );

    const fechaCirugiaHepatica = array(
        CampoColumna::nombre => 'fechaCirugiaHepatica',
        CampoColumna::tabla => Tabla::CirugiasHepaticas,
        CampoColumna::tipo => TipoColumna::DATE,
        CampoColumna::columna => 'fecha'
    );

    const hayMetastasisPulmonar = array(
        CampoColumna::nombre => 'hayMetastasisPulmonar',
        CampoColumna::tabla => Tabla::MetastasisPulmonares,
        CampoColumna::tipo => TipoColumna::TABLE_PRESENT,
        CampoColumna::columna => '1'
    );

    const hayCirugiaPulmonar = array(
        CampoColumna::nombre => 'hayCirugiaPulmonar',
        CampoColumna::tabla => Tabla::CirugiasPulmonares,
        CampoColumna::tipo => TipoColumna::TABLE_PRESENT,
        CampoColumna::columna => '1'
    );

    const fechaCirugiaPulmonar = array(
        CampoColumna::nombre => 'fechaCirugiaPulmonar',
        CampoColumna::tabla => Tabla::CirugiasPulmonares,
        CampoColumna::tipo => TipoColumna::DATE,
        CampoColumna::columna => 'fecha'
    );

    const hayProtesis = array(
        CampoColumna::nombre => 'hayProtesis',
        CampoColumna::tabla => Tabla::Protesis,
        CampoColumna::tipo => TipoColumna::TABLE_PRESENT,
        CampoColumna::columna => '1'
    );

    const fechaProtesis = array(
        CampoColumna::nombre => 'fechaProtesis',
        CampoColumna::tabla => Tabla::Protesis,
        CampoColumna::tipo => TipoColumna::DATE,
        CampoColumna::columna => 'fecha'
    );

    const complicacionProtesis = array(
        CampoColumna::nombre => 'complicacionProtesis',
        CampoColumna::tabla => Tabla::Protesis,
        CampoColumna::tipo => TipoColumna::BOOL,
        CampoColumna::columna => 'complicacion'
    );

    // Dependientes de Diagnostico, 1:*
    const comorbilidades = array(
        CampoColumna::nombre => 'comorbilidades',
        CampoColumna::tabla => Tabla::Comorbilidades,
        CampoColumna::tipo => TipoColumna::MULT_STRING,
        CampoColumna::columna => 'comorbilidad'
    );

    const metastasis = array(
        CampoColumna::nombre => 'metastasis',
        CampoColumna::tabla => Tabla::Metastasis,
        CampoColumna::tipo => TipoColumna::MULT_STRING,
        CampoColumna::columna => 'localizacion'
    );

    const causasIntervencion = array(
        CampoColumna::nombre => 'causasIntervencion',
        CampoColumna::tabla => Tabla::CausasIntervencion,
        CampoColumna::tipo => TipoColumna::MULT_STRING,
        CampoColumna::columna => 'causa'
    );

    const localizacionesCancer = array(
        CampoColumna::nombre => 'localizacionesCancer',
        CampoColumna::tabla => Tabla::LocalizacionesCancer,
        CampoColumna::tipo => TipoColumna::MULT_STRING,
        CampoColumna::columna => 'localizacion'
    );

    const tumoresSincronicos = array(
        CampoColumna::nombre => 'tumoresSincronicos',
        CampoColumna::tabla => Tabla::TumoresSincronicos,
        CampoColumna::tipo => TipoColumna::MULT_STRING,
        CampoColumna::columna => 'localizacion'
    );

    const intoxicaciones = array(
        CampoColumna::nombre => 'intoxicaciones',
        CampoColumna::tabla => Tabla::Intoxicaciones,
        CampoColumna::tipo => TipoColumna::MULT_STRING,
        CampoColumna::columna => 'intoxicacion'
    );

    // Propios de Intervencion
    const fechaIntervencion = array(
        CampoColumna::nombre => 'fechaIntervencion',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::DATE,
        CampoColumna::columna => 'fechaIntervencion'
    );

    const duracion = array(
        CampoColumna::nombre => 'duracion',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::INT,
        CampoColumna::columna => 'duracion'
    );

    const fechaListaEspera = array(
        CampoColumna::nombre => 'fechaListaEspera',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::DATE,
        CampoColumna::columna => 'fechaListaEspera'
    );

    const fechaAlta = array(
        CampoColumna::nombre => 'fechaAlta',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::DATE,
        CampoColumna::columna => 'fechaAlta'
    );

    const neoadyuvancia = array(
        CampoColumna::nombre => 'neoadyuvancia',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'neoadyuvancia'
    );

    const codigoCirujano = array(
        CampoColumna::nombre => 'codigoCirujano',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::INT,
        CampoColumna::columna => 'codigoCirujano'
    );

    const tipoReseccion = array(
        CampoColumna::nombre => 'tipoReseccion',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'tipoReseccion'
    );

    const margenAfecto = array(
        CampoColumna::nombre => 'margenAfecto',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'margenAfecto'
    );

    const excision = array(
        CampoColumna::nombre => 'excision',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'excision'
    );

    const rio = array(
        CampoColumna::nombre => 'rio',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'rio'
    );

    const estoma = array(
        CampoColumna::nombre => 'estoma',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'estoma'
    );

    const reingreso30Dias = array(
        CampoColumna::nombre => 'reingreso30Dias',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::BOOL,
        CampoColumna::columna => 'reingreso30Dias'
    );

    const histologia = array(
        CampoColumna::nombre => 'histologia',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'histologia'
    );

    const T = array(
        CampoColumna::nombre => 'T',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'T'
    );

    const N = array(
        CampoColumna::nombre => 'N',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'N'
    );

    const M = array(
        CampoColumna::nombre => 'M',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'M'
    );

    const distanciaMargenDistal = array(
        CampoColumna::nombre => 'distanciaMargenDistal',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::INT,
        CampoColumna::columna => 'distanciaMargenDistal'
    );

    const gradoDiferenciacion = array(
        CampoColumna::nombre => 'gradoDiferenciacion',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'gradoDiferenciacion'
    );

    const intervencionCerrado = array(
        CampoColumna::nombre => 'intervencionCerrado',
        CampoColumna::tabla => Tabla::Intervenciones,
        CampoColumna::tipo => TipoColumna::BOOL,
        CampoColumna::columna => 'cerrado'
    );

    // Dependientes de Intervencion, 1:1-0
    const hayAnastomosis = array(
        CampoColumna::nombre => 'hayAnastomosis',
        CampoColumna::tabla => Tabla::Anastomosis,
        CampoColumna::tipo => TipoColumna::TABLE_PRESENT,
        CampoColumna::columna => '1'
    );

    const tipoAnastomosis = array(
        CampoColumna::nombre => 'tipoAnastomosis',
        CampoColumna::tabla => Tabla::Anastomosis,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'tipo'
    );

    const tecnicaAnastomosis = array(
        CampoColumna::nombre => 'tecnicaAnastomosis',
        CampoColumna::tabla => Tabla::Anastomosis,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'tecnica'
    );

    const modalidadAnastomosis = array(
        CampoColumna::nombre => 'modalidadAnastomosis',
        CampoColumna::tabla => Tabla::Anastomosis,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'modalidad'
    );

    const hayCancerRecto = array(
        CampoColumna::nombre => 'hayCancerRecto',
        CampoColumna::tabla => Tabla::CanceresRecto,
        CampoColumna::tipo => TipoColumna::TABLE_PRESENT,
        CampoColumna::columna => '1'
    );

    const mandardCancerRecto = array(
        CampoColumna::nombre => 'mandardCancerRecto',
        CampoColumna::tabla => Tabla::CanceresRecto,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'mandard'
    );

    const calidadMesorrectoCancerRecto = array(
        CampoColumna::nombre => 'calidadMesorrectoCancerRecto',
        CampoColumna::tabla => Tabla::CanceresRecto,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'calidadMesorrecto'
    );

    const estadificaciones_CancerRecto = array(
        CampoColumna::nombre => 'estadificaciones_CancerRecto',
        CampoColumna::tabla => Tabla::Estadificaciones,
        CampoColumna::tipo => TipoColumna::MULT_STRING,
        CampoColumna::columna => 'localizacion'
    );

    const hayUrgente = array(
        CampoColumna::nombre => 'hayUrgente',
        CampoColumna::tabla => Tabla::Urgentes,
        CampoColumna::tipo => TipoColumna::TABLE_PRESENT,
        CampoColumna::columna => '1'
    );

    const hemodinamicamenteEstableUrgente = array(
        CampoColumna::nombre => 'hemodinamicamenteEstableUrgente',
        CampoColumna::tabla => Tabla::Urgentes,
        CampoColumna::tipo => TipoColumna::BOOL,
        CampoColumna::columna => 'hemodinamicamenteEstable'
    );

    const insuficienciaRenalUrgente = array(
        CampoColumna::nombre => 'insuficienciaRenalUrgente',
        CampoColumna::tabla => Tabla::Urgentes,
        CampoColumna::tipo => TipoColumna::BOOL,
        CampoColumna::columna => 'insuficienciaRenal'
    );

    const peritonitisUrgente = array(
        CampoColumna::nombre => 'peritonitisUrgente',
        CampoColumna::tabla => Tabla::Urgentes,
        CampoColumna::tipo => TipoColumna::STRING,
        CampoColumna::columna => 'peritonitis'
    );

    const motivos_Urgente = array(
        CampoColumna::nombre => 'motivos_Urgente',
        CampoColumna::tabla => Tabla::Motivos,
        CampoColumna::tipo => TipoColumna::MULT_STRING,
        CampoColumna::columna => 'motivo'
    );

    // Dependientes de Intervencion, 1:*
    const tiposIntervencion = array(
        CampoColumna::nombre => 'tiposIntervencion',
        CampoColumna::tabla => Tabla::TiposIntervencion,
        CampoColumna::tipo => TipoColumna::MULT_STRING,
        CampoColumna::columna => 'tipo'
    );

    const complicacionesMedicas = array(
        CampoColumna::nombre => 'complicacionesMedicas',
        CampoColumna::tabla => Tabla::ComplicacionesMedicas,
        CampoColumna::tipo => TipoColumna::MULT_STRING,
        CampoColumna::columna => 'complicacion'
    );

    const complicacionesCirugia = array(
        CampoColumna::nombre => 'complicacionesCirugia',
        CampoColumna::tabla => Tabla::ComplicacionesCirugia,
        CampoColumna::tipo => TipoColumna::MULT_STRING,
        CampoColumna::columna => 'complicacion'
    );

    const transfusiones = array(
        CampoColumna::nombre => 'transfusiones',
        CampoColumna::tabla => Tabla::Transfusiones,
        CampoColumna::tipo => TipoColumna::MULT_STRING,
        CampoColumna::columna => 'momento'
    );

    const complicacionesIntraoperatorias = array(
        CampoColumna::nombre => 'complicacionesIntraoperatorias',
        CampoColumna::tabla => Tabla::ComplicacionesIntraoperatorias,
        CampoColumna::tipo => TipoColumna::MULT_STRING,
        CampoColumna::columna => 'complicacion'
    );

    const factoresRiesgo = array(
        CampoColumna::nombre => 'factoresRiesgo',
        CampoColumna::tabla => Tabla::FactoresRiesgo,
        CampoColumna::tipo => TipoColumna::MULT_STRING,
        CampoColumna::columna => 'tipo'
    );

    const intervencionesAsociadas = array(
        CampoColumna::nombre => 'intervencionesAsociadas',
        CampoColumna::tabla => Tabla::IntervencionesAsociadas,
        CampoColumna::tipo => TipoColumna::MULT_STRING,
        CampoColumna::columna => 'intervencion'
    );

    const accesos = array(
        CampoColumna::nombre => 'accesos',
        CampoColumna::tabla => Tabla::Accesos,
        CampoColumna::tipo => TipoColumna::MULT_STRING,
        CampoColumna::columna => 'acceso'
    );
}