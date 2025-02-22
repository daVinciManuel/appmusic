<?php
session_start();
require_once "./controllers/login/configLogin.php";
require_once "./db/connect.php";
require_once "./models/getUserInfo.php";

if (isset($_SESSION['user'])) {
    header("Location: ./".WELCOME_PAGE);
    exit();
}
if(isset($_SESSION['ban'])){
  // si ha pasado el tiempo baneado
  if(time() > $_SESSION['ban']){
    session_unset();
  }else {
    header("Location: about:blank");
  }
}

  if(!isset($_SESSION['loginAttempts'])){
    $_SESSION['loginAttempts'] = 1;
  }else if($_SESSION['loginAttempts'] == LOGIN_ATTEMPTS_MAX_LIMIT){
    $_SESSION['ban'] = time() + (60*LOGIN_BAN_DURATION);
    header("Location: about:blank");
  }
function errorHandler($errno, $errstr, $errfile, $errline) {
    echo "<b> Error:</b> [$errno] $errstr<br>";
    // echo " Error on line $errline in $errfile<br>";
}
set_error_handler("errorHandler");
if(isset($_POST[LOGIN_FORM_USER_ACC]) && isset($_POST[LOGIN_FORM_USER_PASS])){
  $formUser = trim($_POST[LOGIN_FORM_USER_ACC]);
  $formPassword = trim($_POST[LOGIN_FORM_USER_PASS]);
  $errorMsg = 'Login Failed: ';
  // si formulario esta vacio
  if(empty($formUser) || empty($formPassword)){
    // muestra error
    $errorMsg .= 'Email or Password cannot be empty';
    trigger_error($errorMsg, E_USER_WARNING);

    // suma un intento fallido 
    $_SESSION['loginAttempts'] += 1;
  }else {
    $user = getUserInfo($formUser);
    // si no existe el usuario
    if(!$user){
      // muestra error
      $errorMsg .= 'This account does not exist';
      trigger_error($errorMsg, E_USER_ERROR);

      // suma un intento fallido 
      $_SESSION['loginAttempts'] += 1;

    }else{
      $dbUser = $user[DATABASE_COLUMN_USER_ACC];
      $dbPassword = $user[DATABASE_COLUMN_USER_PASS];
      // si clave equivocada
      if($formUser != $dbUser || $formPassword != $dbPassword){
        // muestra el error 
        $errorMsg .= 'Wrong Email or Password';
        trigger_error($errorMsg,E_USER_ERROR);

        // suma un intento fallido 
        $_SESSION['loginAttempts'] += 1;

      }else{
        // -------------------------- LOGIN OK ----------------------------------------
      session_unset();
        $_SESSION['user'] = $user;
        header('Location: ./'.WELCOME_PAGE);
        exit();
      }
    }
  }
}

require_once "./view/formLogin.php";
