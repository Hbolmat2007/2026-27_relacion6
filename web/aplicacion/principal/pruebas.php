<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");
include_once(dirname(__FILE__) . "/../clases/curso2026/MuebleReciclado.php");
include_once(dirname(__FILE__) . "/../clases/curso2026/MuebleTradicional.php");
include_once(dirname(__FILE__) . "/../clases/curso2026/MuebleBase.php");
include_once(dirname(__FILE__) . "/../clases/curso2026/Caracteristicas.php");


// Controlador
// Creamos los objetos que vamos a probar.
$reciclado = new MuebleReciclado(
    "Estanteria reciclada",
    "FaMu:",
    "ESPAÑA",
    2024,
    null,
    "31/12/2040",
    120,
    75
);

$tradicional = new MuebleTradicional(
    "Mesa tradicional",
    "FaMu:",
    "ESPAÑA",
    2023,
    null,
    "31/12/2040",
    250,
    45.5,
    "S02"
);


// Probamos los setters.
$resultadoPorcentaje = $reciclado->set_porcentajeReciclado(85);
$resultadoPeso = $tradicional->set_peso(60);
$resultadoSerie = $tradicional->set_serie("S03");

// Probamos valores no válidos.
$resultadoPorcentajeIncorrecto = $reciclado->set_porcentajeReciclado(150);
$resultadoPesoIncorrecto = $tradicional->set_peso(500);


// Probamos las características.
$caracteristicas = new Caracteristicas();
$caracteristicas->color = "Roble";
$caracteristicas->material = "Madera";
$caracteristicas->altura = 120;
$caracteristicas->ULTIMA = true;

// Modificar una característica existente después de ULTIMA.
$caracteristicas->color = "Nogal";

// Intentar añadir una característica nueva después de ULTIMA.
$errorCaracteristicas = "";
try {
    $caracteristicas->anchura = 80;
} catch (Exception $e) {
    $errorCaracteristicas = $e->getMessage();
}


// Añadimos características al mueble.
$tradicional->anadirCaracteristicas(
    "saliente", true,
    "precioEspecial", 300,
    "acabado", "Barnizado",
    "sinPareja"
);


// Dibuja la vista
inicioCabecera("Prueba de las clases");
cabecera();
finCabecera();

inicioCuerpo("Prueba de las clases");
cuerpo(
    $reciclado,
    $tradicional,
    $resultadoPorcentaje,
    $resultadoPeso,
    $resultadoSerie,
    $resultadoPorcentajeIncorrecto,
    $resultadoPesoIncorrecto,
    $caracteristicas,
    $errorCaracteristicas
);
finCuerpo();


// Vista
function cabecera()
{
}

function cuerpo(
    MuebleReciclado $reciclado,
    MuebleTradicional $tradicional,
    $resultadoPorcentaje,
    $resultadoPeso,
    $resultadoSerie,
    $resultadoPorcentajeIncorrecto,
    $resultadoPesoIncorrecto,
    Caracteristicas $caracteristicas,
    $errorCaracteristicas
) {
?>

    <h2>Mueble reciclado</h2>

    <p><?php echo $reciclado; ?></p>
    <p>Porcentaje reciclado: <?php echo $reciclado->get_porcentajeReciclado(); ?>%</p>
    <p>Setter con 85: <?php echo $resultadoPorcentaje ? "Correcto" : "Incorrecto"; ?></p>
    <p>Setter con 150: <?php echo $resultadoPorcentajeIncorrecto ? "Correcto" : "Rechazado, como se esperaba"; ?></p>


    <h2>Mueble tradicional</h2>

    <p><?php echo $tradicional; ?></p>
    <p>Peso: <?php echo $tradicional->get_peso(); ?></p>
    <p>Serie: <?php echo $tradicional->get_serie(); ?></p>
    <p>Setter de peso con 60: <?php echo $resultadoPeso ? "Correcto" : "Incorrecto"; ?></p>
    <p>Setter de serie con S03: <?php echo $resultadoSerie ? "Correcto" : "Incorrecto"; ?></p>
    <p>Setter de peso con 500: <?php echo $resultadoPesoIncorrecto ? "Correcto" : "Rechazado, como se esperaba"; ?></p>


    <h2>Características del mueble</h2>

    <p><?php echo $tradicional; ?></p>


    <h2>Clase Caracteristicas independiente</h2>

    <p><?php echo $caracteristicas; ?></p>

    <h3>Recorrido con foreach (orden inverso)</h3>

    <ul>
        <?php
        foreach ($caracteristicas as $nombre => $valor) {
        ?>
            <li>
                <?php echo $nombre; ?>:
                <?php echo is_scalar($valor) ? $valor : gettype($valor); ?>
            </li>
        <?php
        }
        ?>
    </ul>

    <h3>Prueba de ULTIMA</h3>

    <p>Modificar una característica existente: <?php echo $caracteristicas->color; ?></p>
    <p>Intentar añadir una característica nueva: <?php echo $errorCaracteristicas; ?></p>

<?php
}
?>