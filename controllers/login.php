<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: controllers/inicio.php");
    exit();
}

require_once "./db/connect.php";
require_once "../models/getUser.php";

if($_POST){
  $email = $_POST['email'];
  $password = $_POST['password'];
  $user = getUserInfo($email);
  if($user){
    // verificar password
  }
}



require_once "./view/formLogin.php";
