<?php
include "./login/checkLogin.php";
require "../db/connect.php";
require "../models/queriesTracks.php";
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
    // if(in_array($_POST['track'],$_SESSION['carrito'])){
    //   $trackIsAlreadySelected = true;
    // }
  }
    // COMPRUEBA SI CANCION SELECCIONADA ESTA EN EL CARRITO
  $trackIsAlreadySelected = (array_key_exists($_POST['track'],$_SESSION['carrito'])) ? true : false ;
  // AGREGA ID DE LA CANCION AL CARRITO si no esta agregado ya
  if($trackIsAlreadySelected){
      $_SESSION['carrito'][$_POST['track']] = 1 +  intval($_SESSION['carrito'][$_POST['track']]);
  }else{
  // inserta un nuevo producto con su cantidad al carrito
      $pistasPedidas = array_keys($_SESSION['carrito']);
      array_push($pistasPedidas,$_POST['track']);
      $cantidad = array_values($_SESSION['carrito']);
      array_push($cantidad,1);
      $updateCarrito = array_combine($pistasPedidas,$cantidad);
      $_SESSION['carrito'] = $updateCarrito;
  }
}
// ------------------------------------------------ ACCION ELIMINAR DEL CARRITO ------------------------------------------------
// ////////////// FALTA ACTUALIZAR ////////////////
if(isset($_POST['removeFromCart'])){
  foreach($_POST['tracksToRemove'] as $id){
      unset($_SESSION['carrito'][$id]);
  }
}

// ACCION COMPRAR ------------------------------------------------------------
$total = 0;
$dialog = '';
if(isset($_POST['download'])){
	if (count($_SESSION['carrito']) > 0) {
    // muestro el cuadro de dialogo
    $dialog = 'open';
    $pistas = array_keys($_SESSION['carrito']);
    $cantidades = array_values($_SESSION['carrito']);
      // obtengo el monto TOTAL A PAGAR
    foreach($pistas as $n => $trackId){
      $cant = $cantidades[$n];
      $monto = $cant * getTrackPrice($trackId);
      $total += $monto;   
    }

	include_once './API_PHP/redsysHMAC256_API_PHP_7.0.0/apiRedsys.php';
  include_once '../models/queriesInvoice.php';
	$miObj = new RedsysAPI;
		
	$url="http://sis-t.redsys.es:25443/sis/realizarPago";
  // $urlOKKO="http://192.168.206.130/apps/appmusic/pruebaRedsys.php";
  $urlOK="http://localhost/apps/appmusic/controllers/cinicio.php";
  $urlKO="http://localhost/apps/appmusic/controllers/cpaymentfailed.php";
  // numero de pedido
  // $id=sprintf("%012d", (string)getInvoiceId());
  $id = rand(10000,99999);
  // total a pagar
	$amount=floatval($total)*100;
	// Se Rellenan los campos
	$miObj->setParameter("DS_MERCHANT_AMOUNT",$amount);
	$miObj->setParameter("DS_MERCHANT_ORDER",$id);
	$miObj->setParameter("DS_MERCHANT_MERCHANTCODE",'263100000');
	$miObj->setParameter("DS_MERCHANT_CURRENCY",'978');
	$miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",'0');
	$miObj->setParameter("DS_MERCHANT_TERMINAL",'13');
	$miObj->setParameter("DS_MERCHANT_MERCHANTURL",$url);
	$miObj->setParameter("DS_MERCHANT_URLOK",$urlOK);
	$miObj->setParameter("DS_MERCHANT_URLKO",$urlKO);
	$miObj->setParameter("DS_MERCHANT_PAYMENTMETHOD",'C');

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


// carrito para la vista
if(isset($_SESSION['carrito'])){
  // array con los nombres y precios de las canciones del carrito
  $carritoView = array();
  $pistas = array_keys($_SESSION['carrito']);
  foreach($pistas as $trackId){
    $carritoView[] = getTrackInfo($trackId);
  }
}
if(isset($_SESSION['carrito'])){
echo "\$_SESSION[carrito]:";
var_dump($_SESSION['carrito']);
echo "<br>";
}
require_once "../view/vdownmusic.php";
