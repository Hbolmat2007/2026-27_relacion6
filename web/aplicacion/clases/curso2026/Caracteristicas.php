<?php
class Caracteristicas implements IteratorAggregate {
    // Array donde vamos a guardar las características
    private array $_caracteristicas = [];
    // Booleano que indica si ya se ha indicado la última característica.
    private bool $_ultima = false; 

    // Función para cuando se quiera crear una característica
    public function __set(string $nombre, mixed $valor) : void {
        // Comprobamos si la clave existe, ya que deberíamos poder
        // Modificarla aunque ya se haya indicado la última clave
        if (array_key_exists($nombre, $this->_caracteristicas)) {
            $this->_caracteristicas[$nombre] = $valor;
            return;
        }

        // Si ya se ha establecido la característica
        // "última" y se intenta crear una nueva, se lanza una excepción
        if ($this->_ultima) {
            throw new Exception("No se pueden añadir más características");
        }

        // Se crea la característica si no existe y no se ha establecido la última
        $this->_caracteristicas[$nombre] = $valor;

        // Si el nombre es "ULTIMA", dejamos el valor de la variable $_ultima a true
        if ($nombre === "ULTIMA") {
            $this->_ultima = true;
        }
    }

    // función que devuelve el valor para una clave
    public function __get(string $nombre) : mixed {
        if (!array_key_exists($nombre, $this->_caracteristicas)) {
            throw new Exception("No se encuentra la característica " . $nombre);
        }
        return $this->_caracteristicas[$nombre];
    }

    // Función que devuelve true si existe una característica y false si no
    public function __isset(string $nombre) : bool {
        return isset($this->_caracteristicas[$nombre]);
    }
    
    // Función que permite recorrer el array a la inversa
    public function getIterator(): Traversable {
        return new ArrayIterator(array_reverse($this->_caracteristicas, true));
    }    

    public function __toString() : string {
        $str = "";

        foreach ($this->_caracteristicas as $nombre => $valor) {
            $str .= $nombre . ":" . $valor . ";";
        }

        return $str;
    }
}   
?>