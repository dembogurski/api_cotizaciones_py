<?php

  //$json =  file_get_contents('http://testing.marijoa/marijoa/utils/SET/HistoricoCotizacionesSETParaguay.class.php?anio=2022&mes=08&dia=14');

  $json =  file_get_contents('http://testing.marijoa/marijoa/utils/SET/HistoricoCotizacionesSETParaguay.class.php?anio=2022&mes=08');

  $data = json_decode($json,true);
    
  echo $data["01"]['dolar']['compra'];

?>

