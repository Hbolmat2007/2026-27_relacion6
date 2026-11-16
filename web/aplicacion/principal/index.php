<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");
//controlador

$arrayMuebles = include(dirname(__FILE__) . "/../../scripts/librerias/array_muebles.php");
$usuario=getenv("MYSQL_USER");

function dibujarTabla(array $array) {
    ?>
        <table class="tabla">
            <tr>
                <th>Índice</th>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Año</th>
                <th>Precio</th>
                <th>Material</th>
            </tr>
    <?php
    for ($cont = 1; $cont <= count($array); $cont++) {
        ?>
        <tr>
            <td> <?php echo $cont; ?></td>
            <td> <?php echo get_class($array[$cont]); ?></td>
            <td> <?php echo $array[$cont]->get_nombre(); ?></td>
            <td> <?php echo $array[$cont]->get_anio(); ?></td>
            <td> <?php echo $array[$cont]->get_precio() . "€"; ?></td>
            <td> <?php echo $array[$cont]->getMaterialDescripcion(); ?></td>
        </tr>
        <?php
    }
    ?>
        </table>
    <?php
}

function dibujarOpciones(array $array) {
        for ($cont = 1; $cont <= count($array); $cont++) {
            ?>
                <option value="<?php echo $cont ?>"><?php echo $array[$cont]->get_nombre(); ?></option>
            <?php
        }
}

function mostrarMueble(int $valor, array $arrayMuebles) {
    $mueble = $arrayMuebles[$valor];

    ?>
    <h2><?php echo $mueble->get_nombre(); ?></h2>

    <p>Fabricante: <?php echo $mueble->get_fabricante(); ?></p>
    <p>País: <?php echo $mueble->get_pais(); ?></p>
    <p>Año: <?php echo $mueble->get_anio(); ?></p>
    <p>Fecha inicial de venta: <?php echo $mueble->get_fechaIniVenta(); ?></p>
    <p>Fecha final de venta: <?php echo $mueble->get_fechaFinVenta(); ?></p>
    <p>Precio: <?php echo $mueble->get_precio(); ?>€</p>
    <p>Material: <?php echo $mueble->getMaterialDescripcion(); ?></p>

    <h3>Propiedades adicionales</h3>

    <?php
    foreach ($mueble->damePropiedadesAdicionales() as $nombre => $dato) {
        ?>
        <p><?php echo $nombre . ": " . $dato; ?></p>
        <?php
    }
    ?>

    <h3>Características</h3>

    <?php
    foreach ($mueble->get_caracteristicas() as $nombre => $dato) {
        ?>
        <p><?php echo $nombre . ": " . $dato; ?></p>
        <?php
    }
}
//dibuja la plantilla de la vista
inicioCabecera("Tienda");
cabecera();
finCabecera();
inicioCuerpo("Tienda");
cuerpo($arrayMuebles);  

if (isset($_POST["opcion"])) {
    if ($_POST["opcion"] == "Mostrar Mueble") {
        if (isset($_POST["opcionesMueble"])) {
            $valor = (int) $_POST["opcionesMueble"];

            if (array_key_exists($valor, $arrayMuebles)) {
                mostrarMueble($valor, $arrayMuebles);
            }
        }
    } else if ($_POST["opcion"] == "Modificar Mueble") {

    }
}

finCuerpo();
// **********************************************************

//vista
function cabecera() 
{}

//vista
function cuerpo(array $arrayMuebles) {
    dibujarTabla($arrayMuebles);
    ?>
    <form action="./index.php" method="POST">
        <select name="opcionesMueble" id="selMueble">
            <?php dibujarOpciones($arrayMuebles); ?>
        </select>
        <input type="submit" name="opcion" value="Mostrar Mueble" class="boton">
        <input type="submit" name="opcion" value="Modificar Mueble" class="boton" formaction="./modificar.php" formtarget="_blank">
    </form>
    <?php

}
?>