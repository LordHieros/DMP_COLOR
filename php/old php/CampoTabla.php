<?php
abstract class CampoTabla{
    // Nombre de la tabla
    const nombreTabla = 'nombreTabla';
    //Claves guardadas en la sesion, inmutables sin navegacion
    const claves = 'claves';
    //Campos (alguno puede ser PK, específicamente en los casos 1:*)
    const campos = 'campos';
}