<?php

class Pizza{

///ATRIBUTOS
    protected $id;
    protected $sabor;
    protected $precio;
    protected $tipo;
    protected $cantidad;

///PROPIEDADES
    
    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of cantidad
     */ 
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set the value of cantidad
     *
     * @return  self
     */ 
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get the value of tipo
     */ 
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */ 
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get the value of precio
     */ 
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set the value of precio
     *
     * @return  self
     */ 
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get the value of sabor
     */ 
    public function getSabor()
    {
        return $this->sabor;
    }

    /**
     * Set the value of sabor
     *
     * @return  self
     */ 
    public function setSabor($sabor)
    {
        $this->sabor = $sabor;

        return $this;
    }

///CONSTRUCTOR
    public function __construct(){}

    public static function __crearPizzaParametros($sabor, $precio, $tipo, $cantidad)
    {
        $pizza = new Pizza();

        $pizza->sabor = $sabor;
        $pizza->precio = $precio;
        $pizza->tipo = $tipo;
        $pizza->cantidad = $cantidad;
        $pizza->id = Pizza::GenerateId();

        return $pizza;
    }
    
///METODOS
    
    ///generales
        public static function GenerateId()
        {
            return random_int(1,10000);
        }

        
        public function ActualizarFromArray($array)
        {
            foreach($array as $element)
            {
                //criterio de coincidencia
                if($this->getSabor() == $element->getSabor() && $this->getTipo() == $element->getTipo())
                {
                    //actualizacion de datos
                    $element->setPrecio($this->getPrecio());
                    $element->setCantidad($this->getCantidad() + $element->getCantidad());
                    return $array;
                }
            }

            return NULL;
        }

        public function BuscarExistente($array)
        {
            $retorno = "No existen pizzas ni de ese tipo, ni de ese sabor";
            $flagSabor = 0;
            $flagTipo = 0;

            foreach($array as $element)
            {
                if($this->getSabor() == $element->getSabor() && $this->getTipo() == $element->getTipo())
                {
                    return "Si hay.";
                }

                if($this->getSabor() == $element->getSabor() && $this->getTipo() != $element->getTipo())
                {
                    $flagSabor = 1;
                }
                if($this->getTipo() == $element->getTipo() && $this->getSabor() != $element->getSabor())
                {
                    $flagTipo = 1;
                }
            }

            if ($flagSabor == 1 && $flagTipo == 0)
            {
                $retorno = "El sabor existe, pero no con ese tipo";
            }
            if ($flagTipo == 1 && $flagSabor == 0)
            {
                $retorno = "El tipo existe, pero no con ese sabor";
            }
            if ($flagTipo == 1 && $flagSabor == 1)
            {
                $retorno = "Existen el sabor y el tipo, pero no en la misma pizza";
            }

            return $retorno;
        }

        public function BuscarParaVender($array)
        {
            //retorno el array actualizado si la encuentra
            foreach($array as $element)
            {
                if($element->getTipo() == $this->getTipo() && $element->getSabor() == $this->getSabor() && $element->getCantidad() >= $this->getCantidad())
                {
                    //se puede realizar la venta
                    //actualizo la cantidad
                    $element->setCantidad($element->getCantidad() - $this->getCantidad());
                    return $array;
                }
            }

            //retorno null si no cumple los requisitos
            return NULL; 
        }

        public function GuardarImagenPizza($files)
        {
        //obtengo la extension del archivo
        $path = $files['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        //seteo el nombre de la imagen
        $nombreImagen = $this->tipo . $this->sabor . "." . $ext;
        //dictamino el destino
        $destino = "./ImagenesDePizzas/".$nombreImagen;
        //la envio
        move_uploaded_file($files["tmp_name"], $destino);
        }

    ///json
        public static function LeerJSON($path)
        {
            if ($handler = fopen($path, "r"))
            {
                $retorno = array();
                while(!feof($handler))
                {
                    $linea = fgets($handler);
                    if($linea != null)
                    {
                        $aux = json_decode($linea, true);

                        if($aux != null)
                        {
                            $pizza = Pizza::__crearPizzaParametros($aux["sabor"], $aux["precio"], $aux["tipo"], $aux["cantidad"]);
                            array_push($retorno, $pizza);
                        }                    
                    }
                }
                return $retorno;               
            }
            return false;
        }

        public static function ArrayToJson($path, $array)
        {
            if ($handler = fopen($path, "w"))
            {   
                //hago que la numeracion del array sea consecutiva, para evitar problemas en el encode
                $auxArray = array_values($array);

                foreach($auxArray as $elemento)
                {
                    //Genera el json del objeto, dandole las propiedades a traves de la serializacion.
                    fwrite($handler, json_encode($elemento->SerializarJson()) . "\n");
                }

                fclose($handler);

                return true;
            }   
            return false;
        }

        private function SerializarJson()
        {
            return get_object_vars($this);
        }


}



?>