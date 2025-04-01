<?php
include "./controllers/login/checkLogin.php";
require "./db/connect.php";
require "./models/queriesTracks.php";
echo "\$_SESSION[user]:";
var_dump($_SESSION['user']);
echo "<br>";

// ------------------ SELECT > OPTIONS Tracks ----------------
  $vehiculos = getAllTracks();

// ACCION AGREGAR CARRITO ------------------------------
if(isset($_POST['addToCart']) && isset($_POST['track'])){
  	$trackIsAlreadySelected = false;
  if (!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = array();
  } else {
    // COMPRUEBA SI CANCION SELECCIONADA ESTA EN EL CARRITO
    if(in_array($_POST['track'],$_SESSION['carrito'])){
      $trackIsAlreadySelected = true;
    }
  }
  // AGREGA ID DE LA CANCION AL CARRITO si no esta agregado ya
  if(!$trackIsAlreadySelected){
			$_SESSION['carrito'][] = $_POST['track'];
	}
}
// ------------------------------------------------ ACCION ELIMINAR DEL CARRITO ------------------------------------------------
if(isset($_POST['removeFromCart'])){
  foreach($_POST['tracksToRemove'] as $id){
    $index = array_search($id,$_SESSION['carrito']);
    if($index > -1){
      unset($_SESSION['carrito'][$index]);
      $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
  }
}


// ACCION COMPRAR ------------------------------------------------------------
$total = 0;
$dialog = '';
if(isset($_POST['download'])){
	if (count($_SESSION['carrito']) > 0) {
    $dialog = 'open';
    foreach($_SESSION['carrito'] as $trackId){
      $total += getTrackPrice($trackId);
    }

	include './controllers/API_PHP/redsysHMAC256_API_PHP_7.0.0/apiRedsys.php';
  include './models/queriesInvoice.php';
	$miObj = new RedsysAPI;
		
	$url="http://sis-t.redsys.es:25443/sis/realizarPago";
  $urlOKKO="http://192.168.206.130/apps/appmusic/downmusic.php";
  // numero de pedido
	$id=sprintf("%012d", (string)getInvoiceId());
  // total a pagar
	$amount=floatval($total)*100;
  $card = 'C';
	// Se Rellenan los campos
	$miObj->setParameter("DS_MERCHANT_AMOUNT",$amount);
	$miObj->setParameter("DS_MERCHANT_ORDER",$id);
	$miObj->setParameter("DS_MERCHANT_MERCHANTCODE",'263100000');
	$miObj->setParameter("DS_MERCHANT_CURRENCY",'978');
	$miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",'0');
	$miObj->setParameter("DS_MERCHANT_TERMINAL",'13');
	$miObj->setParameter("DS_MERCHANT_MERCHANTURL",$url);
	$miObj->setParameter("DS_MERCHANT_URLOK",$urlOKKO);
	$miObj->setParameter("DS_MERCHANT_URLKO",$urlOKKO);
	$miObj->setParameter("DS_MERCHANT_PAYMENTMETHOD",$card);

	//Datos de configuración
	$version="HMAC_SHA256_V1";
  $kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
	// Se generan los parámetros de la petición
	$params = $miObj->createMerchantParameters();
	$signature = $miObj->createMerchantSignature($kc);
  }
  // VACIA EL CARRITO DESPUES DE HACER LA COMPRA
  // $_SESSION['carrito'] = array();
}

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
            // echo "FIRMA OK";
            // echo "<h2>PAYMENT WENT OK</h2>";
            storeInvoice();
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
				// echo "FIRMA OK";
        storeInvoice();
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
  $user = getUserData($_SESSION['userId']);
  $invoiceId = getInvoiceId();
  $total = 0;
  foreach($_SESSION['carrito'] as $trackId){
    $total += getTrackPrice($trackId);
    insertInvoiceLine(getInvoiceLineId(),$invoiceId,$trackId,$getTrackPrice($trackId));
  }
  insertInvoice($invoiceId,$_SESSION['userId'],date('Y-m-d H:i:s') , $user['BillingAddress'],$user['BillingCity'],$user['BillingState'],$user['BillingCountry'],$user['BillingPostalCode'],$total);
}


// carrito para la vista
if(isset($_SESSION['carrito'])){
  // array con los nombres y precios de las canciones del carrito
  $carritoView = array();
  foreach($_SESSION['carrito'] as $trackId){
    $carritoView[] = getTrackInfo($trackId);
  }
}
echo "\$_SESSION[carrito]:";
var_dump($_SESSION['carrito']);
echo "<br>";
require "./view/vdownmusic.php";
