<?php
abstract class CampoTabla{
    // Nombre de la tabla
    const nombreTabla = 'nombreTabla';
    //Claves guardadas en la sesion, inmutables sin navegacion
    const claves = 'claves';
    //Campos (alguno puede ser PK, espec�ficamente en los casos 1:*)
    const campos = 'campos';
}