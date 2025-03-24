<?php
include "./controllers/login/checkLogin.php";
require "./db/connect.php";
require "./models/queriesTracks.php";
// ------------------ SELECT > OPTIONS Tracks ----------------
  $optionsList = '';
  $vehiculos = getAllTracks();
  foreach($vehiculos as $v){
      $optionsList .= '<option value="' . $v['TrackId'] .'">'.$v['Name'] . ' - ' . $v['UnitPrice'] . '€' .'</option>';
  }

if(isset($_POST['addToCart'])){
  if (!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = array();
  }
  	$trackIsAlreadySelected = false;
  if(isset($_SESSION['carrito'])){
    if(in_array($_POST['track'],$_SESSION['carrito'])){
      $trackIsAlreadySelected = true;
      $mensaje = "Cancion seleccionada previamente: ".$_POST['track'];
    } else {
      $mensaje = "Cancion nueva: ".$_POST['track'];
    }
  }
  if($trackIsAlreadySelected == false){
			$_SESSION['carrito'][] = $_POST['track'];
	}
  echo "<br><b>".$mensaje."<b><br>";
}
if(isset($_POST['download'])){
	if (count($_SESSION['carrito']) > 0) {
    
  }
  $_SESSION['carrito'] = array();
}

require "./view/vdownmusic.php";
