<?php
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
// ------------------ FORMULARIO PARA REDSYS -------------------------------------
echo '    <!-- confirmacion para comprar  -->';
echo '    <dialog ' . $dialog.'>';
echo '      <p>Total: <span> '. $total.'</span></p>';
echo '      <p>Desea realizar la compra?</p>';
echo ' <form action="https://sis-t.redsys.es:25443/sis/realizarPago" method="POST">';
if($dialog){
echo '<input type="hidden" name="Ds_SignatureVersion" value="'. $version .'"/>';
echo '<input type="hidden" name="Ds_MerchantParameters" value="'. $params .'"/>';
echo '<input type="hidden" name="Ds_Signature" value="'. $signature.'"/> ';
} 
echo '<input type="submit" value="PAGAR">';
echo '<btn></btn>';
echo '</form>';
echo '      <form method="dialog"><input type="submit" value="CANCELAR"></form>';
echo '    </dialog>';
// -------------------------------------------------------------------------------
echo '    <header class="container-fluid row">';
echo '      <a class="btn btn-danger col-2 col-lg-1" href="./login/logout.php">Logout</a>';
echo '      <span class="col-1"></span>';
echo '      <a class="btn btn-info col-2 col-lg-1" href="./cinicio.php">Inicio</a>';
echo '    </header>';
echo '    <main>';
echo '      <h1>Descargar Musica</h1>';
echo '      <div class="card border">';
// ---------------------------------------------------------------------------------
echo '      <form id="" name="" action="" method="post" class="card-body">';
echo '        <div class="form-group">';
echo '          Cancion';
// ----------------- LISTADO DE PRODUCTOS ----------------------------------------
echo '          <select name="track" class="">';
echo '            <option disabled selected>Selecciona una pista</option>';
  $optionsList = '';
  foreach($vehiculos as $v){
    $optionsList .= '<option value="' . $v['TrackId'] .'">'.$v['Name'] . ' - ' . $v['UnitPrice'] . '€' .'</option>';
  }
echo $optionsList;
echo '          </select>';
echo '        </div>';
// BOTON AGREGAR AL CARRITO
echo '		<input type="submit" name="addToCart" value="Agregar al carrito" class="btn btn-info disabled">';
// BOTON COMPRAR 
echo '		<input type="submit" name="download" value="Finalizar Compra" class="btn btn-info disabled">';
// --------------------- CARRITO ----------------------------------------------
      if(isset($carritoView)){
      if(count($carritoView) > 0){
echo '      <hr>';
echo '      <h3>Carrito</h3>';
echo '      <table>';
echo '        <thead>';
echo '          <tr>';
echo '            <th>Cancion</th>';
echo '            <th>Cantidad</th>';
echo '            <th>Precio</th>';
echo '            <th></th>';
echo '          </tr>';
echo '        </thead>';
echo '        <tbody>';
      foreach($carritoView as $c){
        if($c){

echo '          <tr>';
echo '            <td>'. $c['Name'].'</td>';
echo '            <td> &nbsp;&nbsp;&nbsp;'. $_SESSION['carrito'][$c['TrackId']].'</td>';
echo '            <td>'. $c['UnitPrice'] . '</td>';
// CASILLA PARA SELECCIONAR ITEM PARA ELIMINAR
echo '            <td><input type="checkbox" name="tracksToRemove[]" value="'. $c['TrackId'].'"></td>';
echo '          </tr>';
       }}
echo '            <tr>';
echo '              <td></td>';
echo '              <td></td>';
// BOTON PARA ELIMINAR ITEM DEL CARRITO
echo '              <td><input type="submit" name="removeFromCart" value="Eliminar" class="btn btn-danger"></td>';
echo '            </tr>';
echo '        </tbody>';
echo '      </table>';
        }
      }
echo '      </form>';
echo '      </div>';
echo '    </main>';
echo '  </body>';
echo '</html>';
