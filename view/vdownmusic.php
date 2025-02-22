<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - MUSICAL</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/extra.css">
  </head>
  <body class="bg-dark">
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
            <option disabled selected></option>
            <?php echo $optionsList ?>
          </select>
        </div>
		<input type="submit" name="submit" value="Agregar al carrito" class="btn btn-dark disabled">
      </form>
      </div>
    </main>
  </body>
</html>
