<?php
session_start();
if(empty($_SESSION['user'])){
  header('Location: ../index.php');
}
