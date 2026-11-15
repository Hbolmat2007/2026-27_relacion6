<?php
include_once(dirname(__FILE__) . "/../../aplicacion/clases/curso2026/MuebleReciclado.php");
include_once(dirname(__FILE__) . "/../../aplicacion/clases/curso2026/MuebleTradicional.php");
include_once(dirname(__FILE__) . "/../../aplicacion/clases/curso2026/MuebleBase.php");
include_once(dirname(__FILE__) . "/../../aplicacion/clases/curso2026/Caracteristicas.php");


return [
    1 => new MuebleReciclado("Mueble reciclado 1", "FaMu:IKEA", "JAPÓN", 2021, "01/01/2021", "01/01/2022", 50, 50),
    2 => new MuebleReciclado("Mueble reciclado 2", "FaMu:IKEA", "BANGLADÉS", 2021, "01/01/2022", "01/01/2023", 40, 60),
    3 => new MuebleTradicional("Mueble Tradicional 1"),
    4 => new MuebleTradicional("Mueble Tradicional 2","Muebles Alfarnate", "ESPAÑA", 2020),
    5 => new MuebleReciclado("Mueble Reciclado 3")
];


