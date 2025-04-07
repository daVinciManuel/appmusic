<?php var_dump($_SESSION); ?>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - MUSICAL</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/extra.css">
    <style>
    </style>
  </head>
  <body class="bg-dark">
    <header class="container-fluid row">
      <a class="btn btn-danger col-2 col-lg-1" href="../controllers/login/logout.php">Logout</a>
      <span class="col-1"></span>
      <a class="btn btn-info col-2 col-lg-1" href="../controllers/cinicio.php">Inicio</a>
    </header>
    <main>
      <h1>Historial de facturas</h1>
      <div class="card border">
        <h3> Usuario: <?php echo $_SESSION['user'];?></h3>
        <div class="row justify-content-center">
        <table class="w-75">
          <thead>
            <tr>
              <th>Numero</th>
              <th>Fecha</th>
              <th>Codigo</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
<?php if(isset($facturas)){ ?>

  <?php $numFacturas = 0; ?>
  <?php foreach($facturas as $f){ ?>
              <?php $numFacturas +=1; ?>
            <tr>
                <td><?php echo $numFacturas; ?></td>
                <td><?php echo $f['InvoiceDate']; ?></td>
                <td><?php echo $f['InvoiceId']; ?></td>
                <td><?php echo $f['Total'] . ' €'; ?></td>
            </tr>
  <?php } ?>
<?php } ?>
          </tbody>
        </table>
        </div>
      </div>
    </main>
  </body>
</html>
