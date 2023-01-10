<?php

/**
 * Descripcion: API para obtener las cotizacioes historicas de la SET de Paraguay * 
 * @author: Ing. Doglas A. Dembogurski Feix
 * @email:  dembogurski@gmail.com
 * @twitter: doglasd
 * @fecha: 10-01-2023
 * Licencia: Creative Commons  
 * Requerimientos:
 * Sistema Operativo Linux
 * Modo de Uso: 
 * 1-Poner este archivo en /var/www/html/ con el nombre de HistoricoCotizacionesSETParaguay.class.php
 * 2-Llamar desde un navegador o desde otro programa a la siguiente URL:  http://ip_servidor/HistoricoCotizacionesSETParaguay.class.php?anio=<anio>&mes=<mes>&dia=<dia>
 * Ej.: para obtener cotizaciones de todo el mes http://ip_servidor/HistoricoCotizacionesSETParaguay.class.php?anio=2022&mes=05
 * Ej.: para obtener cotizaciones de un dia en especifico http://ip_servidor/HistoricoCotizacionesSETParaguay.class.php?anio=2022&mes=05&dia=07
 * Ej.: para obtener cotizaciones de un dia en especifico (No importa el Orden de los parametros) http://ip_servidor/HistoricoCotizacionesSETParaguay.class.php?dia=04&mes=06&anio=2022
 * Ej.: modo de uso desde php:  $json =  file_get_contents('http://ip_servidor/HistoricoCotizacionesSETParaguay.class.php?mes=05&anio=2022'); 
 * 
 * Ej. acceso a los datos:
 * data["dia"].dolar.compra
 * data["dia"].real.venta
 * 
 * data["04"].dolar.compra   >>  "6970.89"
 * data["01"].dolar.venta   >>  "7028.60" 
 * 
 */


class HistoricoCotizacionesSETParaguay {
    function __construct() {
        
        $anio = $_REQUEST['anio'];
        $mes = $_REQUEST['mes'];
        $dia = $_REQUEST['dia'];
        
        
        $dia_req = "*";//Todos los dias
        
        if(isset($_REQUEST['dia'])){  // Si se pide solo de un dia del mes
           $dia_req = $dia; 
        }
        
        //echo "$dia-$mes-$anio<br> dia_req $dia_req <br>";
        
        $meses = array("01"=>"Enero","02"=>"Febrero","03"=>"Marzo","04"=>"Abril","05"=>"Mayo","06"=>"Junio","07"=>"Julio","08"=>"Agosto","09"=>"Setiembre","10"=>"Octubre","11"=>"Noviembre","12"=>"Diciembre");
    
        // Los dela SET son unos Genios convirtieron los meses a Letras
        $meses_letras = array("01"=>"A","02"=>"B","03"=>"C","04"=>"D","05"=>"E","06"=>"F","07"=>"G","08"=>"H","09"=>"I","10"=>"J","11"=>"K","12"=>"L");
        
        $letra = $meses_letras[$mes];
        $mes_letras = $meses[$mes];
        $mes_minuscula = strtolower($mes_letras);
        
        $web = 'wget -q -O HistoricoCotizacionesSETParaguay "https://www.set.gov.py/portal/PARAGUAY-SET/detail?folder-id=repository:collaboration:/sites/PARAGUAY-SET/categories/SET/Informes%20Periodicos/cotizaciones-historicos/'.$anio.'/e-mes-de-'.$mes_minuscula.'&content-id=/repository/collaboration/sites/PARAGUAY-SET/documents/informes-periodicos/cotizaciones/'.$anio.'/'.$letra.'%20-%20Mes%20de%20'.$mes_letras.'" ';
        shell_exec($web);

        //echo $web;
           
        //sleep(1);
        
        $content = file_get_contents("HistoricoCotizacionesSETParaguay");
        
        $tipo_cambios_pos = strpos($content,'<p style="text-align:center!important;"><strong>Tipos de cambios del mes de');
        
        $content = substr($content, $tipo_cambios_pos, 1000000);
        
        $table_init_pos = strpos($content,'<table align="center" border="1" cellpadding="0" cellspacing="0" width="100%">');
        
        $content = substr($content, $table_init_pos, 1000000);
        
        
        $table_end_pos = strpos($content,'</table>');
        
        
        $htmlContent =  substr($content, 0, $table_end_pos + 8);  
        
        
        $htmlContent = str_replace( 'style="text-align: center;"' ,'align="center"',$htmlContent);
        
        $htmlContent = str_replace( '&Oacute;' ,'O',$htmlContent);
        $htmlContent = str_replace( 'style="font-size: 8pt;"' ,'',$htmlContent);
        $htmlContent = str_replace( 'colspan="2"' ,'',$htmlContent);
        $htmlContent = str_replace( '<span style="font-size: 13px; line-height: 20.7999992370605px;">DOLAR</span>' ,'Dolar',$htmlContent);        
        $htmlContent = str_replace( 'Dolar' ,'Dolar Compra</td><td align="center">Dolar Venta',$htmlContent);
        
        $htmlContent = str_replace( '<span style="font-size: 13px; line-height: 20.7999992370605px;">REAL</span>' ,'Real',$htmlContent);        
        $htmlContent = str_replace( 'Real' ,'Real Compra</td><td align="center">Real Venta',$htmlContent);
        
        
        $htmlContent = str_replace( '<span style="font-size: 13px; line-height: 20.7999992370605px;">PESO ARG.</span>' ,'Peso',$htmlContent);        
        $htmlContent = str_replace( 'Peso' ,'Peso Compra</td><td align="center">Peso Venta',$htmlContent);
        
        $htmlContent = str_replace( '<span style="font-size: 13px; line-height: 20.7999992370605px;">YEN</span>' ,'Yen',$htmlContent);        
        $htmlContent = str_replace( 'Yen' ,'Yen Compra</td><td align="center">Yen Venta',$htmlContent);
        
        $htmlContent = str_replace( '<span style="font-size: 13px; line-height: 20.7999992370605px;">EURO</span>' ,'Euro',$htmlContent);        
        $htmlContent = str_replace( 'Euro' ,'Euro Compra</td><td align="center">Euro Venta',$htmlContent);
        
        $htmlContent = str_replace( '<span style="font-size: 13px; line-height: 20.7999992370605px;">LIBRA</span>' ,'Libra',$htmlContent);        
        $htmlContent = str_replace( 'Libra' ,'Libra Compra</td><td align="center">Libra Venta',$htmlContent);
        
        $htmlContent = str_replace( '<span style="font-size: 13px; line-height: 20.7999992370605px;">Compra</span>', 'Compra' ,$htmlContent);
        $htmlContent = str_replace( '<span style="font-size: 13px; line-height: 20.7999992370605px;">Venta</span>', 'Venta' ,$htmlContent);
         
        $widths = array('width="19"','width="46"', 'width="48"','width="49"','width="55"','bgcolor="#efefef"');
        
        $htmlContent = str_replace($widths ,'',$htmlContent);
         
        
        $tabla = '<html>'
                . ''.$htmlContent.''
                . '</html>';
                
        //echo $tabla;
        
        //file_put_contents('tabla.txt', $tabla);
        $DOM = new DOMDocument();
	$DOM->loadHTML($tabla);
	
	$Header = $DOM->getElementsByTagName('tr');
	$Detail = $DOM->getElementsByTagName('td');

         
        //#Get header name of the table
	foreach($Header as $NodeHeader){            
	    $aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
	}
	 
	$i = 0;
	$j = 0;
	foreach($Detail as $sNodeDetail){
            //echo "Nodo ". $sNodeDetail->textContent." <br>";
		$aDataTableDetailHTML[$j][] = trim($sNodeDetail->textContent);
		$i = $i + 1;
		$j = $i % count($aDataTableHeaderHTML) == 0 ? $j + 1 : $j;
	}
	 
        $aux = explode("\n", $htmlContent); 
        
        $current_day = ""; 
        
        $master = array(); 
        
        $c = 0;
        foreach ($aDataTableDetailHTML as $key => $arr) {          
          
           foreach ($arr as $k  => $valor ) {
               
               $valor = str_replace('.', '', $valor);
               $valor = str_replace(',', '.', $valor);
               $isnum = is_numeric($valor); 
               
               if($c == 0 && $valor ){
                   $current_day = strval(str_pad($valor, 2, "0",STR_PAD_LEFT));                      
                   //$current_day = ".$valor.";                      
               }
                
               /***********Dolar EEUU*************/
               if($c === 1 && $isnum && ($current_day == $dia_req || $dia_req == "*") ){
                   $master[$current_day]['dolar'] =  array('compra'=>$valor);                   
               }
               if($c === 2  && $isnum && ($current_day == $dia_req || $dia_req == "*")){                    
                    $master[$current_day]['dolar']['venta']=$valor;
               }
               /***********Real Brasil*************/
               if($c === 3 && $isnum && ($current_day == $dia_req || $dia_req == "*")){
                   $master[$current_day]['real'] =  array('compra'=>$valor);                   
               }
               if($c === 4  && $isnum && ($current_day == $dia_req || $dia_req == "*")){                    
                    $master[$current_day]['real']['venta']=$valor;
               }                              
               /***********Peso Argentino*************/
               if($c === 5 && $isnum && ($current_day == $dia_req || $dia_req == "*")){
                   $master[$current_day]['peso'] =  array('compra'=>$valor);                   
               }
               if($c === 6  && $isnum && ($current_day == $dia_req || $dia_req == "*")){                    
                    $master[$current_day]['peso']['venta']=$valor;
               }
               /***********Yen Japones*************/
               if($c === 7 && $isnum && ($current_day == $dia_req || $dia_req == "*")){
                   $master[$current_day]['yen'] =  array('compra'=>$valor);                   
               }
               if($c === 8  && $isnum && ($current_day == $dia_req || $dia_req == "*")){                    
                    $master[$current_day]['yen']['venta']=$valor;
               }               
               /***********Euro*************/
               if($c === 9 && $isnum && ($current_day == $dia_req || $dia_req == "*")){
                   $master[$current_day]['euro'] =  array('compra'=>$valor);                   
               }
               if($c === 10  && $isnum && ($current_day == $dia_req || $dia_req == "*")){                    
                    $master[$current_day]['euro']['venta']=$valor;
               }               
               /***********Libra *************/
               if($c === 11 && $isnum && ($current_day == $dia_req || $dia_req == "*")){
                   $master[$current_day]['libra'] =  array('compra'=>$valor);                   
               }
               if($c === 12  && $isnum && ($current_day == $dia_req || $dia_req == "*")){                    
                   $master[$current_day]['libra']['venta']=$valor;
               }
                
               $c++; 
               if($c == 13){       
                   $c = 0; 
               }
           } 
           
        } 
       
        echo json_encode($master);
         
        //echo json_encode($aDataTableDetailHTML); 
    }
}

new HistoricoCotizacionesSETParaguay();

?>

