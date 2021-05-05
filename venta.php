<?php

include_once "AccesoDatos.php";

class Venta{


///atributos
    public $email;
    public $fecha;
    public $numero;
    public $id;
    public $sabor;
    public $tipo;
    public $cantidad;
    public $imagen;

///propiedades
    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of fecha
     */ 
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     *
     * @return  self
     */ 
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get the value of imagen
     */ 
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set the value of imagen
     *
     * @return  self
     */ 
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get the value of numero
     */ 
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set the value of numero
     *
     * @return  self
     */ 
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

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


///constructor
    public function __construct(){}

    public static function __crearVentaParametros($email, $sabor, $tipo, $cantidad)
    {
        $venta = new Venta();

        $venta->email = $email;
        $venta->sabor = $sabor;
        $venta->tipo = $tipo;
        $venta->cantidad = $cantidad;
        $venta->fecha = date("Y-m-d");
        $venta->numero = random_int(10001, 20000);
        $venta->id = random_int(1,10000);

        return $venta;
    }
    
//generales
    public static function ObtenerTotalVentas ($array)
    {
        $retorno = 0;

        foreach($array as $element)
        {
            $retorno += $element->getCantidad();
        }

        return $retorno;
    }

    public static function FormatDate($fecha)
    {
        $auxFecha = str_replace("/","-",$fecha);
        $retorno = date("Y-m-d", strtotime($auxFecha));
        return $retorno;
    }

    public function GuardarImagenVenta($files)
    {
        //obtengo el cuerpo del mail
        $cuerpoMail = explode("@",$this->email);
        //obtengo la extension del archivo
        $path = $files['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        //seteo el nombre de la imagen
        $nombreImagen = $this->tipo . $this->sabor . $cuerpoMail[0] . "." . $ext;
        //dictamino el destino
        $destino = "./ImagenesDeLaVenta/".$nombreImagen;
        //la envio
        move_uploaded_file($files["tmp_name"], $destino);
        $this->setImagen($destino);
        
    }

    public static function MoverImagen($venta)
    {
        $nombre = pathinfo($venta->getImagen(), PATHINFO_BASENAME);

        $destino = "./BACKUPVENTAS/".$nombre;

        rename($venta->getImagen(), $destino);
    }

//bbdd

    public static function InsertarVentaToBBDD($param)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into venta (email,fecha,numero,sabor,tipo,cantidad,imagen)values(:email,:fecha,:numero,:sabor,:tipo,:cantidad,:imagen)");
        $consulta->bindValue(':email', $param->getEmail(), PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $param->getFecha(), PDO::PARAM_STR);
        $consulta->bindValue(':numero', $param->getNumero(), PDO::PARAM_INT);
        $consulta->bindValue(':sabor', $param->getSabor(), PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $param->getTipo(), PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $param->getCantidad(), PDO::PARAM_INT);
        $consulta->bindValue(':imagen', $param->getImagen(), PDO::PARAM_STR);
        $consulta->execute();
                
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public static function TraerVentasFromBBDD()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT id, 
                                                        email as email,
                                                        fecha as fecha,
                                                        numero as numero,
                                                        sabor as sabor,
                                                        tipo as tipo,
                                                        cantidad as cantidad,
                                                        imagen as imagen
                                                        from venta");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "venta");
    }

    public static function TraerUnaVentaFromBBDD($numero)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT id, 
                                                        email as email,
                                                        fecha as fecha,
                                                        numero as numero,
                                                        sabor as sabor,
                                                        tipo as tipo,
                                                        cantidad as cantidad,
                                                        imagen as imagen
                                                        from venta
                                                        where numero = $numero");
        $consulta->execute();
        return $consulta->fetchObject('venta');
    }

    public static function TraerVentasEntreDosFechas($fechaMinima, $fechaMaxima)
    {
        $fechaMinimaFormateada = Venta::FormatDate($fechaMinima);
        $fechaMaximaFormateada = Venta::FormatDate($fechaMaxima);

        if($fechaMinimaFormateada && $fechaMaximaFormateada)
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta =$objetoAccesoDato->RetornarConsulta("SELECT id as id, 
                                                            email as email,
                                                            fecha as fecha,
                                                            numero as numero,
                                                            sabor as sabor,
                                                            tipo as tipo,
                                                            cantidad as cantidad,
                                                            imagen as imagen
                                                            FROM venta
                                                            WHERE fecha BETWEEN :fechaMinima AND :fechaMaxima
                                                            ORDER BY sabor ASC");
            $consulta->bindValue(':fechaMaxima', $fechaMaximaFormateada, PDO::PARAM_STR);
            $consulta->bindValue(':fechaMinima', $fechaMinimaFormateada, PDO::PARAM_STR);
            $consulta->execute();			
            return $consulta->fetchAll(PDO::FETCH_CLASS, "venta");
        }
        else
        {
            return NULL;
        }    
    }

    public static function TraerVentasSegunEmail($email)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta =$objetoAccesoDato->RetornarConsulta("SELECT id as id, 
                                                            email as email,
                                                            fecha as fecha,
                                                            numero as numero,
                                                            sabor as sabor,
                                                            tipo as tipo,
                                                            cantidad as cantidad,
                                                            imagen as imagen
                                                            FROM venta
                                                            WHERE email = :email");
            $consulta->bindValue(':email', $email, PDO::PARAM_STR);
            $consulta->execute();			
            return $consulta->fetchAll(PDO::FETCH_CLASS, "venta");
    }

    public static function TraerVentasSegunSabor($sabor)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT id as id, 
                                                        email as email,
                                                        fecha as fecha,
                                                        numero as numero,
                                                        sabor as sabor,
                                                        tipo as tipo,
                                                        cantidad as cantidad,
                                                        imagen as imagen
                                                        FROM venta
                                                        WHERE sabor = :sabor");
        $consulta->bindValue(':sabor', $sabor, PDO::PARAM_STR);
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS, "venta");
    }

    public static function ModificarVenta($venta, $numero)
    {
           $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
           $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE venta
                                                          SET email=:email,
                                                          sabor=:sabor,
                                                          tipo=:tipo,
                                                          cantidad=:cantidad
                                                          WHERE numero=:numero");        
			   $consulta->bindValue(':email',$venta->getEmail(), PDO::PARAM_STR);
			   $consulta->bindValue(':sabor', $venta->getSabor(), PDO::PARAM_STR);
			   $consulta->bindValue(':tipo', $venta->getTipo(), PDO::PARAM_STR);
			   $consulta->bindValue(':cantidad', $venta->getCantidad(), PDO::PARAM_INT);
			   $consulta->bindValue(':numero', $numero, PDO::PARAM_INT);
               $consulta->execute();
           
               return $consulta->rowCount();
    }

    public static function BorrarVentaPorNumero($numero)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            delete 
            from venta 				
            WHERE numero=:numero");	
            $consulta->bindValue(':numero',$numero, PDO::PARAM_INT);		
            $consulta->execute();
            return $consulta->rowCount();
    }




}

?>