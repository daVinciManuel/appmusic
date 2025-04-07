<?php
include "./controllers/login/checkLogin.php";

include_once "./db/connect.php";
include_once "./models/queriesInvoice.php";
// request fecha,codigo de pedido, total WHEN CustomerId = $id;
// of musica.Invoice
$facturas = getInvoices($_SESSION['userId']);
require_once './view/vhistfacturas.php';
