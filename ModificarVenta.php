<?php

include_once "./venta.php";

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"),$put_vars);

    if(isset($put_vars["usuario"]) && isset($put_vars["sabor"]) && isset($put_vars["tipo"]) && isset($put_vars["cantidad"]) && isset($put_vars["numero"]))
    {
        $email = $put_vars["usuario"];
        $sabor = $put_vars["sabor"];
        $tipo = $put_vars["tipo"];
        $cantidad = $put_vars["cantidad"];
        $numero = $put_vars["numero"];
        
        $venta = Venta::__crearVentaParametros($email, $sabor, $tipo, $cantidad);
        
        $retorno = Venta::ModificarVenta($venta, $numero);

        if($retorno == 0){
    
            echo "No hay ninguna venta con ese numero de pedido";
        
        } else{
        
            echo "Se modifico la pizza con el numero indicado";    
        }
    }
    else
    {
        echo "Error en los parametros recibidos";
    }

    
} 
else
{

    echo "El metodo no es PUT";
}


?>