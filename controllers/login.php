<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: controllers/inicio.php");
    exit();
}
function errores ($error_level,$error_message)
	{
	  echo "<b> ERROR!! Codigo error: </b> ".$error_level."  - <b> Mensaje: ".$error_message." </b><br>";
	  echo "Finalizando script <br>";
	}
set_error_handler(errores);
echo "hey";
require_once "./view/formLogin.php";
