<?php
/*PALERMO JUAN GABRIEL
PizzaConsultar.php: (por POST)Se ingresa Sabor,Tipo, si coincide con algún registro del archivo Pizza.json,
retornar “Si Hay”. De lo contrario informar si no existe el tipo o el sabor.*/

include_once "pizza.php";

if(isset($_POST["sabor"]) && isset($_POST["tipo"]))
{
    $sabor = $_POST['sabor'];
    $tipo = $_POST['tipo'];

    //creo la pizza con los parametros que me mandan
    $pizza = Pizza::__crearPizzaParametros($sabor, "", $tipo, "");
    //traigo las pizzas que tengo el json
    $pizzasCargadas = Pizza::LeerJson(".\pizza.json");

    //busco si hay coincidencia e imprimo el mensaje del metodo
    echo $pizza->BuscarExistente($pizzasCargadas);
}
else
{
    echo "error en los parametros";
}


?>