<?php
include "./controllers/login/checkLogin.php";
require "./db/connect.php";
require "./models/queriesTracks.php";
// ------------------ SELECT > OPTIONS VEHICULOS ----------------
  $optionsList = '';
  $vehiculos = getAllTracks();
  foreach($vehiculos as $v){
      $optionsList .= '<option value="' . $v['TrackId'] .'">'.$v['Name'] . ' - ' . $v['UnitPrice'] . '€' .'</option>';
  }


require "./view/vdownmusic.php";
