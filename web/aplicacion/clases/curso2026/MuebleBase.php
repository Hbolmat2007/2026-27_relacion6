<?php
abstract class MuebleBase {
    // Constante y contador de muebles creados
    private const int MAXIMO_MUEBLES = 100;
    private static int $_mueblesCreados = 0;
    private static $_regexFabricante = "/^FaMu:/";

    // Propiedades privadas de la clase
    private string          $_nombre;
    private string          $_fabricante;
    private string          $_pais;
    private int             $_anio;
    private string          $_fechaIniVenta;
    private string          $_fechaFinVenta;
    private float           $_precio;
    private Caracteristicas $_caracteristicas;
    private Materiales      $_material;
    

    // Comprueba si una fecha tiene el formato correcto
    private function esFechaValida(string $fecha) {
        $array = mb_split("/", $fecha);
        if (count($array) !== 3) { return false; }
        $fechaConvertida = DateTime::createFromFormat('d/m/Y', $fecha);
        if ($fechaConvertida === false || $fechaConvertida === null) return false;

        return $fechaConvertida->format('d/m/Y') === $fecha;
    }

    // Getter y setter del nombre
    public function get_nombre() : string { return $this->_nombre; }
    public function set_nombre(string $nombre) : bool { 
        if ($nombre == "" || mb_strlen($nombre) > 40) {
            return false;
        } else {
            $this->_nombre = mb_strtoupper($nombre, 'UTF-8');
            return true;
        }
    }

    // Getter y setter del fabricante
    public function get_fabricante() : string { return $this->_fabricante; }
    public function set_fabricante(string $fabricante) : bool { 
    
        if (!preg_match(self::$_regexFabricante, $fabricante)) {
            $this->_fabricante = mb_substr(("FaMu:" . $fabricante), 0, 30);
        } else {
            $this->_fabricante = mb_substr(($fabricante), 0 , 30);
        }
        return true;
    }
    
    // Getter y setter del país
    public function get_pais() : string { return $this->_pais; }
    public function set_pais(string $pais) : bool { 
        if ($pais == "" || mb_strlen($pais) > 20) {
            return false;
        } else {
            $this->_pais = $pais;
            return true;
        }
    }

    // Getter y setter del año de fabricación
    public function get_anio() : int { return $this->_anio; }
    public function set_anio(int $anio) : bool {
        if ($anio < 2020 || $anio > date("Y")) {
            return false;
        } else {
            $this->_anio = $anio;
            return true;
        }
    }
    
    // Getter y setter de la fecha inicial de venta
    public function get_fechaIniVenta() : string { return $this->_fechaIniVenta; }
    public function set_fechaIniVenta(string $fecha) : bool {
        if (!$this->esFechaValida($fecha)) {
            return false;
        } else if (mb_split("/", $fecha)[2] < $this->_anio) {
            return false;
        } else {
            $this->_fechaIniVenta = $fecha;
            return true;
        }
    }

    // Getter y setter de la fecha final de venta
    public function get_fechaFinVenta() : string { return $this->_fechaFinVenta; }
    public function set_fechaFinVenta(string $fecha) : bool {
        if (!$this->esFechaValida($fecha)) {
            return false;
        }
        $fechaInicio = DateTime::createFromFormat("d/m/Y", $this->get_fechaIniVenta());
        $fechaFin = DateTime::createFromFormat("d/m/Y", $fecha);
        
        if ($fechaFin < $fechaInicio) {
            return false;
        } else {
            $this->_fechaFinVenta = $fecha;
            return true;
        }
    }

    // Getter y setter del precio
    public function get_precio() : float { return $this->_precio; }
    public function set_precio(float $precio) : bool { 
        if ($precio < 30) {
            return false;
        } else {
            $this->_precio = $precio;
            return true;
        }
    }

    public function get_caracteristicas() : Caracteristicas { return $this->_caracteristicas; }
    public function anadirCaracteristicas(string $carac, mixed $valor, mixed ...$resto) {
        // Asignamos el parámetro $valor a la característica $carac 
        $this->_caracteristicas->$carac = $valor;
        // Recorremos el bucle de dos en dos. 
        // Si la posición siguiente se pasa de la longitud, se para de recorrer.
        for ($cont = 0; $cont + 1< count($resto); $cont = $cont + 2) {
            $this->_caracteristicas->{$resto[$cont]} = $resto[$cont + 1];
        }
    }

    public function get_material_int() : int { return $this->_material->value; }
    public function getmaterialDescripcion() : string { return $this->_material->name; }

    public function set_material(int | string $material) : bool {
        if (Materiales::tryFrom($material) !== null) { 
            $this->_material = Materiales::tryFrom($material);
            return true;
        }
        return false;
    }
    // Constructor
    public function __construct(string $nombre, string $fabricante = "FaMu:", string $pais = "ESPAÑA", int $anio = 2020, ?string $fechaIniVenta = null, string $fechaFinVenta = "31/12/2040", float $precio = 30, int $material = 10) {

        // Comprobamos que no se haya alcanzado el máximo de muebles
        if (self::$_mueblesCreados >= self::MAXIMO_MUEBLES) {
            throw new Exception("Máximo de muebles alcanzado. No se pueden crear más.");
        }

        // El nombre es obligatorio y, si no es válido, no se permite crear el mueble
        $errorNombre = $this->set_nombre($nombre);
        if (!$errorNombre) {
            throw new Exception("El nombre debe tener entre 1 y 40 carácteres");
        }

        // Asignar propiedades
        if (!$this->set_fabricante($fabricante)) {
            $this->set_fabricante("FaMu:");
        }
        
        if (!$this->set_pais($pais)) {
            $this->set_pais("ESPAÑA");
        }

        if (!$this->set_anio($anio)) {
            $this->set_anio(2020);
        }

        // Si no se indica una fecha inicial, utilizamos el 1/01/anio
        if ($fechaIniVenta === null) {
            $fechaInicioVenta = "01/01/" . $this->get_anio();
        } else {
            $fechaInicioVenta = $fechaIniVenta;
        }
        if (!$this->set_fechaIniVenta($fechaInicioVenta)) {
            $this->set_fechaIniVenta("01/01/" . $this->get_anio());
        }

        if (!$this->set_fechaFinVenta($fechaFinVenta)) {
            $this->set_fechaFinVenta("31/12/2040");
        }

        if (!$this->set_precio($precio)) {
            $this->set_precio(30);
        }
        
        if (!$this->set_material($material)) {
            $this->set_material(10);
        }
        
        $this->_caracteristicas = new Caracteristicas();
        // Aumentamos el contador de muebles 
        self::$_mueblesCreados++;
    }

    // Comprueba si se pueden crear más muebles e indica cuántos quedan
    public static function puedeCrear(int &$numero) : bool {
        $numero = self::MAXIMO_MUEBLES - self::$_mueblesCreados;
        return self::$_mueblesCreados < self::MAXIMO_MUEBLES;
    }

    // Método abstracto
    public abstract function damePropiedadesAdicionales() : array;

    // Función tostring
    public function __toString() {
        $str = "Mueble de clase " . get_class($this) . " con nombre "
         . $this->_nombre . ", fabricante " . $this->_fabricante . 
        ", fabricado en " . $this->_pais . " a partir del año " . $this->_anio . 
        ", vendido desde el " . $this->_fechaIniVenta . " hasta el " . 
        $this->_fechaFinVenta . ", precio ". $this->_precio . ". Características: "
        . $this->get_caracteristicas()->__toString() . ". Del material" . $this->getmaterialDescripcion();

        return $str;
    }

    // Impide crear propiedades dinámicas
    public function __set($nombre, $valor) {
        throw new Exception("No se permiten propiedades dinámicas");
    }
}
?>