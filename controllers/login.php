<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: ./inicio.php");
    exit();
}

require_once "./db/connect.php";
require_once "./models/getUserInfo.php";

function errorHandler($errno, $errstr, $errfile, $errline) {
    echo "<b> Error:</b> [$errno] $errstr<br>";
    // echo " Error on line $errline in $errfile<br>";
}
set_error_handler("errorHandler");

if($_POST){
  $email = $_POST['email'];
  $password = $_POST['password'];
  $user = getUserInfo($email);
  $errorMsg = 'Login Failed: ';
  // si formulario esta vacio
  if(empty($email) || empty($password)){
    $errorMsg .= 'Email or Password cannot be empty';
    trigger_error($errorMsg, E_USER_WARNING);
  }else {
    // si no existe el usuario
    if(!$user){
      $errorMsg .= 'This account does not exist';
      trigger_error($errorMsg, E_USER_ERROR);
    }else{
      // si clave equivocada
      if($email != $user['Email'] || $password != $user['LastName']){
        $errorMsg .= 'Wrong Email or Password';
  trigger_error($errorMsg,E_USER_ERROR);
      }else{
        session_start();
        $_SESSION['user'] = $user;
        var_dump($_SESSION);
        // header('Location: ./controllers/inicio.php');
        // exit();
      }
    }
  }
}

require_once "./view/formLogin.php";
