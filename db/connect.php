<?php
function connect(){

    $dbhost = 'localhost';
    $username = 'root';
    $password = 'rootroot';
    $dbname = 'musica';

    try{
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$username,$password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        $conn=null;
        die($e->getMessage());
    }
  return $conn;
}
