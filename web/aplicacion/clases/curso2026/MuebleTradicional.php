<?php 
include_once(dirname(__FILE__) . '../../scripts/librerias/val_normal.php');

final class MuebleTradicional extends MuebleBase {
    
    private float $_peso;
    private string $_serie;

    public function get_peso() : float { return $this->_peso; }
    public function set_peso(float $numero) : bool { 
        if ($numero < 15 || $numero > 300) {
            return false;
        } else {
            $this->_porcentajeReciclado = $numero;
            return true;
        }
    }

    public function get_serie() : string { return $this->_serie; }
    
    public function set_serie(string $serie) : bool { 
        if ($serie === "") return false;
        
        $this->_serie = $serie;
        return true;
    }

    // Constructor que llama a  la clase padre y añade el porcentaje reciclado
    public function __construct(string $nombre, string $fabricante = "FaMu:", string $pais = "ESPAÑA", int $anio = 2020, ?string $fechaIniVenta = null, string $fechaFinVenta = "31/12/2040", float $precio = 30, float $peso = 15, string $serie = "S01") {
        parent::__construct($nombre, $fabricante, $pais, $anio, $fechaIniVenta, $fechaFinVenta, $precio);
        if (!$this->set_serie($serie)) {
            $this->set_serie("S01");
        }

        if (!$this->set_peso($peso)) {
            $this->set_peso(15);
        }
    }
    public function damePropiedadesAdicionales() : array {
        // Creamos el array (asociativo) y lo devuelvo
        $array = [
            "peso"=> $this->get_peso(),
            "serie"=> $this->get_serie()
        ];
        return $array;
    }
    
    // Función toString, que llama al padre y añade la sección de peso y serie:
    #[Override]
    public function __toString() {
        return parent::__toString() . ", Peso: " . $this->get_peso() . ", Serie: " . $this->get_serie();
    }
}
?>