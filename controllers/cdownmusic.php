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
if(isset($_POST['addToCart'])){
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
if(isset($_POST['download'])){
	if (count($_SESSION['carrito']) > 0) {
    
  }
  // VACIA EL CARRITO DESPUES DE HACER LA COMPRA
  $_SESSION['carrito'] = array();
}

// carrito para la vista
if(isset($_SESSION['carrito'])){
  // array con los nombres y precios de las canciones del carrito
  $carritoView = array();
  foreach($_SESSION['carrito'] as $c){
    $carritoView[] = getTrackName($c);
  }
}
$optionsList = '';
foreach($vehiculos as $v){
    $optionsList .= '<option value="' . $v['TrackId'] .'">'.$v['Name'] . ' - ' . $v['UnitPrice'] . '€' .'</option>';
}
require "./view/vdownmusic.php";
