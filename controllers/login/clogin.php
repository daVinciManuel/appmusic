<?php
// si existe la cookie de usuario se lleva a la pagina de inicio
if (isset($_COOKIE['user'])) {
    header("Location: ./inicio.php");
    // exit();
}
// si esta baneado se redirige a una pagina vacia
if(isset($_COOKIE['ban'])){
    header("Location: about:blank");
}
// si ha superado el limite de intentos se banea por 5min al usuario y se redirige a una pagina vacia
if(isset($_COOKIE['loginAttempts']) && $_COOKIE['loginAttempts'] == 3){
  // ban por 5 minutos
  setcookie('ban','',time() + 60 * 5,'/');
  header("Location: about:blank");
}
function errorHandler($errno, $errstr, $errfile, $errline) {
    echo "<b> Error:</b> [$errno] $errstr<br>";
    echo " Error on line $errline in $errfile<br>";
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

    // crea la cookie de intentos fallidos si no existe
    if(!isset($_COOKIE['loginAttempts'])){
      setcookie('loginAttempts',serialize(0),time() + 3600 * 2,'/');
    }
    // suma un intento fallido 
    $_COOKIE['loginAttempts'] = serialize(1 + unserialize($_COOKIE['loginAttempts']));
  }else {
    include_once "./db/connect.php";
    include_once "./models/mlogin.php";
    $user = getUserFullname($formUser);
    // si no existe el usuario
    if(!$user){
      // muestra error
      $errorMsg .= 'This account does not exist';
      trigger_error($errorMsg, E_USER_WARNING);

      // crea la cookie de intentos fallidos si no existe
      if(!isset($_COOKIE['loginAttempts'])){
        setcookie('loginAttempts',serialize(0),time() + 3600 * 2,'/');
      }
      // suma un intento fallido 
      $_COOKIE['loginAttempts'] = serialize(1 + unserialize($_COOKIE['loginAttempts']));
    }else{
      // ----------------------------------- AUTENTICACION ---------------------------
      $dbPassword = getUserPass($formUser);
      // si clave equivocada
      if($formPassword != $dbPassword){
        // muestra el error 
        $errorMsg .= 'Wrong Email or Password';
        trigger_error($errorMsg,E_USER_ERROR);

        // crea la cookie de intentos fallidos si no existe
        if(!isset($_COOKIE['loginAttempts'])){
          setcookie('loginAttempts',serialize(0),time() + 3600 * 2,'/');
        }
        // suma un intento fallido 
        $_COOKIE['loginAttempts'] = serialize(1 + unserialize($_COOKIE['loginAttempts']));

      }else{
        // -------------------------- LOGIN OK ----------------------------------------
      session_unset();
        $_SESSION['user'] = $user;
        header('Location: ./inicio.php');
        // exit();
      }
    }
  }
}

require_once "./view/vlogin.php";
