<?php
var_dump($_SESSION);
echo '<html>';
echo '  <head>';
echo '    <meta charset="UTF-8">';
echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '    <title>Home - MUSICAL</title>';
echo '    <link rel="stylesheet" href="../css/bootstrap.min.css">';
echo '    <link rel="stylesheet" href="../css/extra.css">';
echo '    <style>';
echo '    </style>';
echo '  </head>';
echo '  <body class="bg-dark">';
echo '    <header class="container-fluid row">';
echo '      <a class="btn btn-danger col-2 col-lg-1" href="./login/logout.php">Logout</a>';
echo '      <span class="col-1"></span>';
echo '      <a class="btn btn-info col-2 col-lg-1" href="./cinicio.php">Inicio</a>';
echo '    </header>';
echo '    <main>';
echo '      <h1>Historial de facturas</h1>';
echo '      <div class="card border">';
echo '        <h3> Usuario: '. $_SESSION['user'].'</h3>';
echo '        <div class="row justify-content-center">';
echo '        <table class="w-75">';
echo '          <thead>';
echo '            <tr>';
echo '              <th>Numero</th>';
echo '              <th>Fecha</th>';
echo '              <th>Codigo</th>';
echo '              <th>Total</th>';
echo '            </tr>';
echo '          </thead>';
echo '          <tbody>';
if(isset($facturas)){
  $numFacturas = 0;
  foreach($facturas as $f){
    $numFacturas +=1;
echo '            <tr>';
echo '                <td>'.$numFacturas.'</td>';
echo '                <td>'. $f['InvoiceDate'] . '</td>';
echo '                <td>'. $f['InvoiceId'] .'</td>';
echo '                <td>'. $f['Total'] . ' € </td>';
echo '            </tr>';
  }
}
echo '          </tbody>';
echo '        </table>';
echo '        </div>';
echo '      </div>';
echo '    </main>';
echo '  </body>';
echo '</html>';
