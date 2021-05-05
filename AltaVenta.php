<?php

include_once "venta.php";
include_once "pizza.php";

if(isset($_POST["email"]) && isset($_POST["sabor"]) && isset($_POST["tipo"]) && isset($_POST["cantidad"]) && isset($_FILES["imagen"]))
{
    $email = $_POST["email"];
    $sabor = $_POST["sabor"];
    $tipo = $_POST["tipo"];
    $cantidad = $_POST["cantidad"];

    //creo la pizza con los datos recibidos.
    $pizza = Pizza::__crearPizzaParametros($sabor, "", $tipo, $cantidad);

    //traigo las pizzas que tengo en el JSON
    $arrayPizzas = Pizza::LeerJSON(".\pizza.json");

    $pizzasActualizadas = $pizza->BuscarParaVender($arrayPizzas);

    if($pizzasActualizadas != NULL) //si existe la pizza en el json (con stock)
    {
        //actualizar el stock del json
        Pizza::ArrayToJson(".\pizza.json", $pizzasActualizadas);
        //crear la venta
        $venta = Venta::__crearVentaParametros($email, $sabor, $tipo, $cantidad);
        //imagen config
        $venta->GuardarImagenVenta($_FILES["imagen"]);
        //postear la venta en base de datos
        echo "Se registro la venta bajo el ID: " . Venta::InsertarVentaToBBDD($venta);

    
    }
    else //si no existe la pizza en el json
    {
        //informar que no se puede realizar la venta
        echo "No se puede realizar la venta";
    }
    
}else
{
    echo "Error en los parametros recibidos";
}


?>