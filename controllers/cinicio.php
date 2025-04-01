<?php
require_once "./controllers/login/checkLogin.php";
var_dump($_SESSION);
echo '</br>';
echo '$ GET:';
var_dump($_GET);
echo '</br>';

// -------------------- PROCESAR RESPUESTA DE REDSYS ------------------------------------------------------------

  if (!empty( $_POST ) && isset($_POST['Ds_SignatureVersion']) && isset($_POST["Ds_MerchantParameters"]) && isset($_POST["Ds_Signature"]) ) {//URL DE RESP. ONLINE
    include './controllers/API_PHP/redsysHMAC256_API_PHP_7.0.0/apiRedsys.php';
					
      $version = $_POST["Ds_SignatureVersion"];
      $datos = $_POST["Ds_MerchantParameters"];
      $signatureRecibida = $_POST["Ds_Signature"];
					

      $miObj = new RedsysAPI;
      $decodec = $miObj->decodeMerchantParameters($datos);	
      $kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //Clave recuperada de CANALES
      $firma = $miObj->createMerchantSignatureNotif($kc,$datos);	

      // echo PHP_VERSION."<br/>";
      // echo $firma."<br/>";
      // echo $signatureRecibida."<br/>";
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
      include './controllers/API_PHP/redsysHMAC256_API_PHP_7.0.0/apiRedsys.php';
			$version = $_GET["Ds_SignatureVersion"];
			$datos = $_GET["Ds_MerchantParameters"];
			$signatureRecibida = $_GET["Ds_Signature"];
				
		
	$miObj = new RedsysAPI;
			$decodec = $miObj->decodeMerchantParameters($datos);
			$kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //Clave recuperada de CANALES
			$firma = $miObj->createMerchantSignatureNotif($kc,$datos);
		
			if ($firma === $signatureRecibida){
        // echo "<h2>PAYMENT WENT OK</h2>";
				echo "FIRMA OK";
        echo '</br>';
        $insertDone = storeInvoice();
        echo $insertDone ? 'Pago guardado correctamente en la BD' : 'Error insertando datos a la base de datos';
			} else {
				echo "FIRMA KO";
			}
		}
		else{
			// die("No se recibió respuesta");
		}
	}

// ------------- INSERTS DE FACTURAS ------------------------------------------
function storeInvoice(){
  include_once './db/connect.php';
  include_once './models/insertInvoice.php';
  $insertDone = false;
  // si existe el carrito y tiene items hace el insert a la base de datos
  if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0){
    //
    // calculo el codigo de la siguiente factura
    //
    include_once './models/queriesInvoice.php';
    $invoiceId = getInvoiceId();
    //
    // obtengo los datos del comprador necesarios para la factura (City,Country,State...)
    //
    include_once './models/queriesCustomer.php';
    $user = getUserData($_SESSION['userId']);
    //
    // suma el precio de todos los items del carrito
    //
    include_once './models/queriesTracks.php';
  $total = 0;
    foreach($_SESSION['carrito'] as $trackId){
      $total = $total + getTrackPrice($trackId);
    }
    // redondeo el precio para evitar un fallo del almacenamiento en memoria de los FLOAT en PHP que son inexactos
    $total = round($total,2);

    // hago el insert, si todo va bien devuelve true, si no, devuelve false
    if(insertInvoice($invoiceId,$_SESSION['userId'],date('Y-m-d H:i:s'),$user['Address'],$user['City'],$user['State'],$user['Country'],$user['PostalCode'],$total)){
      $insertDone = true;
    }
    foreach($_SESSION['carrito'] as $trackId){
      if(!insertInvoiceLine(getInvoiceLineId(),$invoiceId,$trackId,getTrackPrice($trackId),1)){
        $insertDone = false;
      }
    }
  }
  // devuelvo true si han funcionado todos los inserts a DB
  return $insertDone;
}

// borro el carrito si se ha guardado la compra en la BD 
if($insertDone){
  unset($_SESSION['carrito']);
}

require_once "./view/vinicio.php";
