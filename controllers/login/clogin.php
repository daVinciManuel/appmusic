<?php
// si existe la cookie de usuario redirige a la pagina de inicio
if (isset($_COOKIE['user'])) {
    header("Location: ./inicio.php");
    exit();
}
// si esta baneado se redirige a una pagina vacia
if(isset($_COOKIE['loginAttempts']) && $_COOKIE['loginAttempts'] >= 3 - 1){
    header("Location: about:blank");
}
function errorHandler($errno, $errstr, $errfile, $errline) {
    echo "<b> Error:</b> [$errno] $errstr<br>";
    // echo " Error on line $errline in $errfile<br>";
}
set_error_handler("errorHandler");
if(isset($_POST['email']) && isset($_POST['password'])){
  $formUser = trim($_POST['email']);
  $formPassword = trim($_POST['password']);
  $errorMsg = 'Login Failed: ';
  // si formulario esta vacio
  if(empty($formUser) || empty($formPassword)){
    // muestra error
    $errorMsg .= 'Email or Password cannot be empty';
    trigger_error($errorMsg, E_USER_WARNING);

    // ----------------------- COOKIES INTENTOS FALLIDOS -----------------------
    // crea la cookie de intentos fallidos si no existe
    if(!isset($_COOKIE['loginAttempts'])){
      setcookie('loginAttempts',1,time() + 3600 * 2,'/');
    }else{
      $attempts = $_COOKIE['loginAttempts'];
      // limite de intentos 3
      if($attempts < 3){
      // suma un intento fallido 
        $attempts+=1;
      setcookie('loginAttempts',$attempts,time() + 60*5,'/');
      // ban por 5 minutos
      }else {
        header("Location: about:blank");
      }
    }
  }else {
    include_once "./db/connect.php";
    include_once "./models/mlogin.php";
    $user = getUserFullname($formUser);
    // si no existe el usuario
    if(!$user){
      // muestra error
      $errorMsg .= 'This account does not exist';
      trigger_error($errorMsg, E_USER_WARNING);

      // ----------------------- COOKIES INTENTOS FALLIDOS -----------------------
      // crea la cookie de intentos fallidos si no existe
      if(!isset($_COOKIE['loginAttempts'])){
        setcookie('loginAttempts',1,time() + 3600 * 2,'/');
      }else{
        // limite de intentos 3
        $attempts = $_COOKIE['loginAttempts'];
        if($attempts < 3){
        // suma un intento fallido 
          $attempts+=1;
          setcookie('loginAttempts',$attempts,time() + 60*5,'/');
          // ban por 5 minutos
        }else {
        header("Location: about:blank");
        }
      }
    }else{
      // ----------------------------------- AUTENTICACION ---------------------------
      $dbPassword = getUserPass($formUser);
      // si clave equivocada
      if($formPassword != $dbPassword){
        // muestra el error 
        $errorMsg .= 'Wrong Email or Password';
        trigger_error($errorMsg,E_USER_WARNING);

        // ----------------------- COOKIES INTENTOS FALLIDOS -----------------------
        // crea la cookie de intentos fallidos si no existe
        if(!isset($_COOKIE['loginAttempts'])){
          setcookie('loginAttempts',1,time() + 3600 * 2,'/');
        }else{
          // limite de intentos 3
          $attempts = $_COOKIE['loginAttempts'];
          if($attempts < 3){
          // suma un intento fallido 
            $attempts+=1;
            setcookie('loginAttempts',$attempts,time() + 60*5,'/');
            // ban por 5 minutos
          }else {
          header("Location: about:blank");
          }
        }
      }else{
        // -------------------------- LOGIN OK ----------------------------------------
        setcookie('loginAttempts',$_COOKIE['loginAttempts'] + 1,time() - 9999,'/');
        session_start();
        $_SESSION['user'] = $user;
        header('Location: ./inicio.php');
        exit();
      }
    }
  }
}

require_once "./view/vlogin.php";
