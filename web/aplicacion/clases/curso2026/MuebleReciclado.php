<?php 
include_once(dirname(__FILE__) . '/../../scripts/librerias/val_normal.php');

final class MuebleReciclado extends MuebleBase {
    
    private int $_porcentajeReciclado;

    public function get_porcentajeReciclado() : int { return $this->_porcentajeReciclado; }
    public function set_porcentajeReciclado(int $numero) : bool { 
        if ($numero < 0 || $numero > 100) {
            return false;
        } else {
            $this->_porcentajeReciclado = $numero;
            return true;
        }
    }

    // Constructor que llama a  la clase padre y añade el porcentaje reciclado
    public function __construct(string $nombre, string $fabricante = "FaMu:", string $pais = "ESPAÑA", int $anio = 2020, ?string $fechaIniVenta = null, string $fechaFinVenta = "31/12/2040", float $precio = 30, int $porcentajeReciclado = 10) {
        parent::__construct($nombre, $fabricante, $pais, $anio, $fechaIniVenta, $fechaFinVenta, $precio);
        if (!$this->set_porcentajeReciclado($porcentajeReciclado)) {
            $this->set_porcentajeReciclado(10);
        }
    }
    public function damePropiedadesAdicionales() : array {
        // Creamos el array (asociativo) y lo devuelvo
        $array = [
            "porcentajeReciclado"=> $this->get_porcentajeReciclado()
        ];
        return $array;
    }

    // Función toString, que llama al padre y añade la sección de porcentaje reciclado:
    #[Override]
    public function __toString()
    {
        return parent::__toString() . ", Porcentaje reciclado: " . $this->get_porcentajeReciclado();
    }

}
?>