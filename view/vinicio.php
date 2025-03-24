<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PORTAL MUSICAL</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
  </head>
  <body class="bg-dark">
    <header>
      <a class="btn btn-danger" href="./controllers/login/logout.php">Logout</a>
    </header>
    <main>
<h1>WELCOME <?php echo $_SESSION['user'];?></h1>
      <menu>
        <li><a href="./downmusic.php">Descargar Musica</a></li>
        <li><a href="./histfacturas.php">Historial de Facturas</a></li>
        <li><a href="./facturas.php">Facturas entre dos fechas</a></li>
        <li><a href="./ranking.php">Ranking de canciones</a></li>
      </menu>
    </main>
  </body>
</html>
