<?php
require_once "./login/checkLogin.php";
$devview = true;
if($devview){
echo '<hr>';
echo '<h5><b>Developer view:</b></h5>';
echo 'current time:';
var_dump(date('Y-m-d H:i:s'));
echo '</br>';
echo '$_SESSION: ';
var_dump($_SESSION);
echo '</br>';
// echo '$ GET:';
// var_dump($_GET);
// echo '</br>';
// echo '$ POST:';
// var_dump($_POST);
// echo '</br>';
}
// -------------------- PROCESAR RESPUESTA DE REDSYS ------------------------------------------------------------

  if (!empty( $_POST ) && isset($_POST['Ds_SignatureVersion']) && isset($_POST["Ds_MerchantParameters"]) && isset($_POST["Ds_Signature"]) ) {//URL DE RESP. ONLINE
    include './API_PHP/redsysHMAC256_API_PHP_7.0.0/apiRedsys.php';
					
      $version = $_POST["Ds_SignatureVersion"];
      $datos = $_POST["Ds_MerchantParameters"];
      $signatureRecibida = $_POST["Ds_Signature"];
					

      $miObj = new RedsysAPI;
      $decodec = $miObj->decodeMerchantParameters($datos);	
      $kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //Clave recuperada de CANALES
      $firma = $miObj->createMerchantSignatureNotif($kc,$datos);	

      if ($firma === $signatureRecibida){
        echo "FIRMA OK";
        // echo "<h2>PAYMENT WENT OK</h2>";
        $insertDone = storeInvoice();
        echo $insertDone ? 'Guardado correctamente en la BD' : 'Error enviando datos a la base de datos';
      } else {
        echo "FIRMA KO";
      }
	}
	else{
    if (!empty( $_GET ) && isset($_GET['Ds_SignatureVersion']) && isset($_GET["Ds_MerchantParameters"]) && isset($_GET["Ds_Signature"]) ) {//URL DE RESP. ONLINE
      include './API_PHP/redsysHMAC256_API_PHP_7.0.0/apiRedsys.php';
			$version = $_GET["Ds_SignatureVersion"];
			$datos = $_GET["Ds_MerchantParameters"];
			$signatureRecibida = $_GET["Ds_Signature"];
				
		
      $miObj = new RedsysAPI;
			$decodec = $miObj->decodeMerchantParameters($datos);
			$kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //Clave recuperada de CANALES
			$firma = $miObj->createMerchantSignatureNotif($kc,$datos);
      echo '$ respuesta decodec: ';
			echo gettype($decodec);	
        echo '</br>';
			if ($firma === $signatureRecibida){
				echo "FIRMA OK";
        echo '</br>';
        // codigo de respuesta:
        $responseCode = $miObj->getParameter("Ds_Response");
        if($devview){
          echo '$responseCode: ';
          var_dump($responseCode);
          echo '</br>';
        }
        // si codigo ok => guardar en DB
        if($responseCode === "0000"){
          $insertDone = storeInvoice();
        }
        if($devview){
          echo $insertDone ? 'Pago guardado correctamente en la BD' : 'Error insertando datos a la base de datos';
        }
			} else {
				echo "FIRMA KO";
			}
		}
		else{
			// die("No se recibió respuesta");
		}
	}

// ------------- GUARDA FACTURA EN BD ------------------------------------------
function storeInvoice(){
  include_once '../db/connect.php';
  include_once '../models/insertInvoice.php';
  $insertDone = false;
  // si existe el carrito y tiene items hace el insert a la base de datos
  echo "$ SESSION['carrito']";
  if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0){
    //
    // calculo el codigo de la siguiente factura
    include_once '../models/queriesInvoice.php';
    $invoiceId = getInvoiceId();
    //
    // obtengo los datos del comprador necesarios para la factura (City,Country,State...)
    include_once '../models/queriesCustomer.php';
    $user = getUserData($_SESSION['userId']);
    //
    // suma el precio de todos los items del carrito
    include_once '../models/queriesTracks.php';
  $total = 0;
    $pistas = array_keys($_SESSION['carrito']);
    $cantidades = array_values($_SESSION['carrito']);
      // obtengo el monto TOTAL A PAGAR
    foreach($pistas as $n => $trackId){
      $cant = $cantidades[$n];
      $monto = $cant * getTrackPrice($trackId);
      $total += $monto;   
    }
    // redondeo el precio para evitar un fallo del almacenamiento en memoria de los FLOAT en PHP que son inexactos
    $total = round($total,2);

    // hago el insert, si todo va bien devuelve true, si no, devuelve false
    if(insertInvoice($invoiceId,$_SESSION['userId'],date('Y-m-d H:i:s'),$user['Address'],$user['City'],$user['State'],$user['Country'],$user['PostalCode'],$total)){
      $insertDone = true;
    }
    foreach(array_keys($_SESSION['carrito']) as $trackId){
      if(!insertInvoiceLine(getInvoiceLineId(),$invoiceId,$trackId,getTrackPrice($trackId),$_SESSION['carrito'][$trackId])){
        $insertDone = false;
      }
    }
  }
  // devuelvo true si han funcionado todos los inserts a DB
  return $insertDone;
}

// borro el carrito si se ha guardado la compra en la BD 
if(isset($insertDone)){
  unset($_SESSION['carrito']);
}
if($devview){ echo '<hr>'; }
require_once "../view/vinicio.php";
