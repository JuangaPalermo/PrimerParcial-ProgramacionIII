<?php
/*
PALERMO JUAN GABRIEL
7- (2 pts.) borrarVenta.php(por DELETE), debe recibir un número de pedido,se borra la venta y la foto se mueve a
la carpeta /BACKUPVENTAS*/

include_once "./venta.php";


if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"),$put_vars);

    if(isset($put_vars["numero"]))
    {
        $numero = $put_vars["numero"];
    
        $venta = Venta::TraerUnaVentaFromBBDD($numero);
    
        if($venta)
        {
            Venta::MoverImagen($venta);
    
            if(Venta::BorrarVentaPorNumero($numero) != 0)
            {
                echo "Venta eliminada";
            } 
            else
            {

                echo "Error al querer eliminar";
            }
        } 
        else 
        {
            echo "No hay ventas con ese numero";
        }
    }
    else
    {
        echo "Error en los parametros";
    }


} else 
{
    echo "No es un DELETE";
}


?>