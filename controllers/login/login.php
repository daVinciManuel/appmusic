<?php

if (isset($_SESSION['user'])) {
    // header("Location: ./inicio.php");
    // exit();
}

require_once "./db/connect.php";
require_once "./controllers/login/configLogin.php";
require_once "./models/getUserInfo.php";
function errorHandler($errno, $errstr, $errfile, $errline) {
    echo "<b> Error:</b> [$errno] $errstr<br>";
    // echo " Error on line $errline in $errfile<br>";
}
set_error_handler("errorHandler");

if($_POST){
  $formUser = $_POST[LOGIN_FORM_USER_ACC];
  $formPassword = $_POST[LOGIN_FORM_USER_PASS];
  $errorMsg = 'Login Failed: ';
  // si formulario esta vacio
  if(empty($formUser) || empty($formPassword)){
    $errorMsg .= 'Email or Password cannot be empty';
    trigger_error($errorMsg, E_USER_WARNING);
  }else {
    // si no existe el usuario
  $user = getUserInfo($formUser);
    if(!$user){
      $errorMsg .= 'This account does not exist';
      trigger_error($errorMsg, E_USER_ERROR);
    }else{
      $dbUser = $user[DATABASE_COLUMN_USER_ACC];
      $dbPassword = $user[DATABASE_COLUMN_USER_PASS];
      // si clave equivocada
      if($formUser != $dbUser || $formPassword != $dbPassword){
        $errorMsg .= 'Wrong Email or Password';
        trigger_error($errorMsg,E_USER_ERROR);
      }else{
        // -------------------------- LOGIN OK ----------------------------------------
        session_start();
        $_SESSION['user'] = $user;
        header('Location: ./'.WELCOME_PAGE);
        exit();
      }
    }
  }
}

require_once "./view/formLogin.php";
