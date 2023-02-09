# api_cotizaciones_py
Api para Obtener el Historico de cotizaciones de la SET Paraguay

 <?php

  //$json =  file_get_contents('http://<IP_TU_SERVIDOR>/SET/HistoricoCotizacionesSETParaguay.class.php?anio=2022&mes=08&dia=14');



  $json =  file_get_contents('http://<IP_TU_SERVIDOR>/SET/HistoricoCotizacionesSETParaguay.class.php?anio=2022&mes=08');



  $data = json_decode($json,true);


    
  echo $data["01"]['dolar']['compra'];

?>



