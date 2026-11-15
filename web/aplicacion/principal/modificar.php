<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

$arrayMuebles = include(dirname(__FILE__) . "/../../scripts/librerias/array_muebles.php");
// Comprobamos que se haya seleccionado un mueble
if (!isset($_POST["opcionesMueble"])) {
    die("No se ha seleccionado ningún mueble.");
}

$valor = (int) $_POST["opcionesMueble"];

if (!array_key_exists($valor, $arrayMuebles)) {
    die("El mueble seleccionado no existe.");
}

$mueble = $arrayMuebles[$valor];

// Recogemos los datos actuales del mueble
$datos = [
    "nombre" => $mueble->get_nombre(),
    "fabricante" => $mueble->get_fabricante(),
    "pais" => $mueble->get_pais(),
    "anio" => $mueble->get_anio(),
    "fechaIniVenta" => $mueble->get_fechaIniVenta(),
    "fechaFinVenta" => $mueble->get_fechaFinVenta(),
    "precio" => $mueble->get_precio()
];

// Recogemos también las propiedades adicionales
if ($mueble instanceof MuebleReciclado) {
    $datos["porcentajeReciclado"] = $mueble->get_porcentajeReciclado();
} else if ($mueble instanceof MuebleTradicional) {
    $datos["peso"] = $mueble->get_peso();
    $datos["serie"] = $mueble->get_serie();
}

$error = false;
$modificado = false;

// Controlador: modificación del mueble
if (isset($_POST["guardar"])) {

    // Recogemos los datos enviados
    foreach ($datos as $nombre => $dato) {
        if (isset($_POST[$nombre]) && is_string($_POST[$nombre])) {
            $datos[$nombre] = $_POST[$nombre];
        }
    }

    // Creamos una instancia auxiliar del mismo tipo
    if ($mueble instanceof MuebleReciclado) {
        $auxiliar = new MuebleReciclado("AUXILIAR");
    } else {
        $auxiliar = new MuebleTradicional("AUXILIAR");
    }

    // Validación mediante los métodos set
    $anio = filter_var($datos["anio"], FILTER_VALIDATE_INT);

    $valido = $auxiliar->set_nombre($datos["nombre"]);

    if ($anio !== false) {
        $valido = $valido && $auxiliar->set_pais($datos["pais"]);
        $valido = $valido && $auxiliar->set_anio($anio);
        $valido = $valido && $auxiliar->set_fechaIniVenta($datos["fechaIniVenta"]);
    } else {
        $valido = false;
    }

    // Validación directa mediante condiciones

    // Fabricante: añadimos el prefijo si no lo tiene
    $fabricante = $datos["fabricante"];

    if (mb_substr($fabricante, 0, 5) != "FaMu:") {
        $fabricante = "FaMu:" . $fabricante;
    }

    $fabricante = mb_substr($fabricante, 0, 30);

    // Fecha final
    $fechaFin = DateTime::createFromFormat("!d/m/Y", $datos["fechaFinVenta"]);
    $fechaInicio = DateTime::createFromFormat("!d/m/Y", $datos["fechaIniVenta"]);

    if (
        $fechaFin === false ||
        $fechaInicio === false ||
        $fechaFin->format("d/m/Y") != $datos["fechaFinVenta"] ||
        $fechaFin < $fechaInicio
    ) {
        $valido = false;
    }

    // Precio
    if (!is_numeric($datos["precio"]) || $datos["precio"] < 30) {
        $valido = false;
    }

    // Propiedades específicas de cada tipo
    if ($mueble instanceof MuebleReciclado) {

        if (
            filter_var($datos["porcentajeReciclado"], FILTER_VALIDATE_INT) === false ||
            $datos["porcentajeReciclado"] < 0 ||
            $datos["porcentajeReciclado"] > 100
        ) {
            $valido = false;
        }

    } else if ($mueble instanceof MuebleTradicional) {

        if (
            !is_numeric($datos["peso"]) ||
            $datos["peso"] < 15 ||
            $datos["peso"] > 300
        ) {
            $valido = false;
        }

        if ($datos["serie"] == "") {
            $valido = false;
        }
    }

    // Si todo es correcto, modificamos el mueble
    if ($valido) {

        $mueble->set_nombre($datos["nombre"]);
        $mueble->set_fabricante($fabricante);
        $mueble->set_pais($datos["pais"]);
        $mueble->set_anio($anio);
        $mueble->set_fechaIniVenta($datos["fechaIniVenta"]);
        $mueble->set_fechaFinVenta($datos["fechaFinVenta"]);
        $mueble->set_precio((float) $datos["precio"]);

        if ($mueble instanceof MuebleReciclado) {
            $mueble->set_porcentajeReciclado((int) $datos["porcentajeReciclado"]);
        } else if ($mueble instanceof MuebleTradicional) {
            $mueble->set_peso((float) $datos["peso"]);
            $mueble->set_serie($datos["serie"]);
        }

        $modificado = true;

    } else {
        $error = true;
    }
}

// Vista
inicioCabecera("Modificar mueble");
cabecera();
finCabecera();

inicioCuerpo("Modificar mueble");
cuerpo();
finCuerpo();

// Vista
function cabecera() {
}

function cuerpo() {
    global $mueble, $datos, $valor, $error, $modificado;

    if ($modificado) {
        ?>
        <h2>Mueble modificado correctamente</h2>

        <h3>Resumen de los datos</h3>

        <p>Nombre: <?php echo htmlspecialchars($mueble->get_nombre()); ?></p>
        <p>Fabricante: <?php echo htmlspecialchars($mueble->get_fabricante()); ?></p>
        <p>País: <?php echo htmlspecialchars($mueble->get_pais()); ?></p>
        <p>Año: <?php echo $mueble->get_anio(); ?></p>
        <p>Fecha inicial de venta: <?php echo $mueble->get_fechaIniVenta(); ?></p>
        <p>Fecha final de venta: <?php echo $mueble->get_fechaFinVenta(); ?></p>
        <p>Precio: <?php echo $mueble->get_precio(); ?>€</p>

        <h3>Propiedades adicionales</h3>

        <?php
        foreach ($mueble->damePropiedadesAdicionales() as $nombre => $dato) {
            ?>
            <p><?php echo htmlspecialchars($nombre) . ": " . htmlspecialchars((string) $dato); ?></p>
            <?php
        }
        ?>

        <a href="./index.php">Volver a Index.php</a>

        <?php
        return;
    }

    if ($error) {
        ?>
        <p style="color:red;">Hay errores en los datos introducidos. Comprueba los valores.</p>
        <?php
    }
    ?>

    <h2>Modificar <?php echo htmlspecialchars($mueble->get_nombre()); ?></h2>

    <form action="./modificar.php" method="POST">

        <input type="hidden" name="opcionesMueble" value="<?php echo $valor; ?>">

        <p>
            Nombre:
            <input type="text" name="nombre"
                   value="<?php echo htmlspecialchars((string) $datos["nombre"]); ?>">
        </p>

        <p>
            Fabricante:
            <input type="text" name="fabricante"
                   value="<?php echo htmlspecialchars((string) $datos["fabricante"]); ?>">
        </p>

        <p>
            País:
            <input type="text" name="pais"
                   value="<?php echo htmlspecialchars((string) $datos["pais"]); ?>">
        </p>

        <p>
            Año:
            <input type="number" name="anio"
                   value="<?php echo htmlspecialchars((string) $datos["anio"]); ?>">
        </p>

        <p>
            Fecha inicial de venta (dd/mm/aaaa):
            <input type="text" name="fechaIniVenta"
                   value="<?php echo htmlspecialchars((string) $datos["fechaIniVenta"]); ?>">
        </p>

        <p>
            Fecha final de venta (dd/mm/aaaa):
            <input type="text" name="fechaFinVenta"
                   value="<?php echo htmlspecialchars((string) $datos["fechaFinVenta"]); ?>">
        </p>

        <p>
            Precio:
            <input type="number" step="any" name="precio"
                   value="<?php echo htmlspecialchars((string) $datos["precio"]); ?>">
        </p>

        <?php if ($mueble instanceof MuebleReciclado) { ?>

            <p>
                Porcentaje reciclado:
                <input type="number" name="porcentajeReciclado"
                       value="<?php echo htmlspecialchars((string) $datos["porcentajeReciclado"]); ?>">
            </p>

        <?php } else if ($mueble instanceof MuebleTradicional) { ?>

            <p>
                Peso:
                <input type="number" step="any" name="peso"
                       value="<?php echo htmlspecialchars((string) $datos["peso"]); ?>">
            </p>

            <p>
                Serie:
                <input type="text" name="serie"
                       value="<?php echo htmlspecialchars((string) $datos["serie"]); ?>">
            </p>

        <?php } ?>

        <input type="submit" name="guardar" value="Guardar cambios" class="boton">

    </form>

    <p><a href="./index.php">Volver a Index.php</a></p>

    <?php
}
?>
