<?php

include_once "venta.php";

//punto a
$arrayVentas = Venta::TraerVentasFromBBDD();

echo "La cantidad total de pizzas vendidas es: " . Venta::ObtenerTotalVentas($arrayVentas);

echo "\n\n\n";
//punto b
$arrayEntreFechas = Venta::TraerVentasEntreDosFechas("2021-03-03", "2021-05-03");

var_dump($arrayEntreFechas);

echo "\n\n\n";
//punto c
$arrayPorMail = Venta::TraerVentasSegunEmail("testventa@venta.com");

var_dump($arrayPorMail);

echo "\n\n\n";
//punto d
$arrayPorSabor = Venta::TraerVentasSegunSabor("muzzarella");

var_dump($arrayPorSabor);

echo "\n\n\n";
?>