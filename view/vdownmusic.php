<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - MUSICAL</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/extra.css">
    <style>
    </style>
  </head>
  <body class="bg-dark">
    <!-- confirmacion para comprar  -->
    <dialog <?php echo $dialog; ?>>
      <p>Total: <span><?php echo $total;?></span></p>
      <p>Desea realizar la compra?</p>
    <form action='https://sis-t.redsys.es:25443/sis/realizarPago' method='POST'>
        <?php if($dialog){ ?>

 <input type="hidden" name="Ds_SignatureVersion" value="<?php echo $version; ?>"/>
 <input type="hidden" name="Ds_MerchantParameters" value="<?php echo $params; ?>"/>
 <input type="hidden" name="Ds_Signature" value="<?php echo $signature; ?>"/>
        <?php } ?>
      <input type='submit' value='PAGAR'>
      </form>
    </dialog>
    <header>
      <a class="btn btn-danger" href="./controllers/login/logout.php">Logout</a>
    </header>
    <main>
      <h1>Descargar Musica</h1>
      <div class="card border">
      <form id="" name="" action="" method="post" class="card-body">
        <div class="form-group">
          Cancion
          <select name="track" class="">
            <option disabled selected>Selecciona una pista</option>
              <?php
              // LISTADO DE PISTAS 
              $optionsList = '';
              foreach($vehiculos as $v){
                  $optionsList .= '<option value="' . $v['TrackId'] .'">'.$v['Name'] . ' - ' . $v['UnitPrice'] . '€' .'</option>';
              }
              ?>
            <?php echo $optionsList ?>
          </select>
        </div>
		<input type="submit" name="addToCart" value="Agregar al carrito" class="btn btn-info disabled">
		<input type="submit" name="download" value="Finalizar Compra" class="btn btn-info disabled">
      <?php if(count($carritoView) > 0){ ?>
      <hr>
      <h3>Carrito</h3>
      <table>
        <thead>
          <tr>
            <th>Cancion</th>
            <th>Precio</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
      <?php foreach($carritoView as $c){ ?>
        <?php if($c){ ?>

          <tr>
            <td><?php echo $c['Name']; ?></td>
            <td><?php echo $c['UnitPrice']; ?></td>
            <td><input type="checkbox" name="tracksToRemove[]" value="<?php echo $c['TrackId'];?>"></td>
          </tr>
      <?php }} ?>
            <tr>
              <td></td>
              <td></td>
              <td><input type="submit" name="removeFromCart" value="Eliminar" class="btn btn-danger"></td>
            </tr>
        </tbody>
      </table>
      <?php } ?>
      </form>
      </div>
    </main>
  </body>
</html>
