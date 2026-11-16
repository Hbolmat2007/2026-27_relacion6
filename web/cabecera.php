<?php 
include_once(dirname(__FILE__) . "/aplicacion/clases/curso2026/MuebleBase.php");
include_once(dirname(__FILE__) . "/aplicacion/clases/curso2026/MuebleTradicional.php");
include_once(dirname(__FILE__) . "/aplicacion/clases/curso2026/MuebleReciclado.php");
include_once(dirname(__FILE__) . "/aplicacion/clases/curso2026/Caracteristicas.php");
include_once(dirname(__FILE__) . "/aplicacion/clases/curso2026/Materiales.php");

define("RUTABASE", dirname(__FILE__));
//define("MODO_TRABAJO","produccion"); //en "produccion o en desarrollo
define("MODO_TRABAJO","desarrollo"); //en "produccion o en desarrollo

if (MODO_TRABAJO=="produccion")
    error_reporting(0);
    else 
        error_reporting(E_ALL);  

spl_autoload_register(function ($clase){
    $ruta=RUTABASE."/aplicacion/clases/curso2026";
    $fichero=$ruta."$clase.php";
    
    if (file_exists($fichero))
        {
            require_once($fichero);
        }
      else
        {
            throw new Exception("La clase $clase no se ha encontrado.");
        }
});

include(RUTABASE."/aplicacion/plantilla/plantilla.php");

//creo todos los objetos que necesita mi aplicación
