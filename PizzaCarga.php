<?php
/* PALERMO JUAN GABRIEL
B- (1 pt.) PizzaCarga.php: (por GET)se ingresa Sabor, precio, Tipo (“molde” o “piedra”), cantidad( de unidades). Se
guardan los datos en en el archivo de texto Pizza.json, tomando un id autoincremental como
identificador(emulado) .Sí el sabor y tipo ya existen , se actualiza el precio y se suma al stock existente.
5- (2 pts.)PizzaCarga.php:.(continuación) Cambio de get a post.
completar el alta con imagen de la pizza, guardando la imagen con el tipo y el sabor como nombre en la carpeta
/ImagenesDePizzas.
*/

include_once "pizza.php";

if(isset($_POST['sabor']) && isset($_POST['precio']) && isset($_POST['tipo']) && isset($_POST['cantidad']) && isset($_FILES['imagen']))
{
    $sabor = $_POST['sabor'];
    $precio = $_POST['precio'];
    $tipo = $_POST['tipo'];
    $cantidad = $_POST['cantidad'];

    if ($tipo == "molde" || $tipo == "piedra")
    {

        //creo la pizza con los datos que me pasaron
        $pizza = Pizza:: __crearPizzaParametros($sabor, $precio, $tipo, $cantidad);
    
        //busco todas las pizzas que tengo en el archivo json
        $pizzasCargadas = Pizza::LeerJSON(".\pizza.json");
    
        //actualizo las pizzas (si no hay coincidencia, trae null)
        $pizzasActualizadas = $pizza->ActualizarFromArray($pizzasCargadas);
    
        if($pizzasActualizadas == NULL)
        {
            //agregar la pizza y actualizo el JSON
            array_push($pizzasCargadas, $pizza);
            Pizza::ArrayToJson(".\pizza.json", $pizzasCargadas);
    
            echo "Se agrego la nueva pizza";
        }
        else
        {
            //actualizo el JSON
            Pizza::ArrayToJson(".\pizza.json", $pizzasActualizadas);
    
            echo "Se actualizaron las pizzas";
        }

        $pizza->GuardarImagenPizza($_FILES["imagen"]);
        
      
    }
    else
    {
        echo "Ese tipo de pizza no es valido";
    }
}
else
{
    echo "error en los parametros";
}

?>