<?php
echo '<html>';
echo '  <head>';
echo '    <meta charset="UTF-8">';
echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '    <meta http-equiv="refresh" content="5,url=./cinicio.php">';
echo '    <title>PORTAL MUSICAL</title>';
echo '    <link rel="stylesheet" href="../css/bootstrap.min.css">';
echo '  </head>';
echo '  <body class="">';
echo '    <header>';
echo '      <a class="btn btn-danger" href="../controllers/login/logout.php">Logout</a>';
echo '    </header>';
echo '    <main>';
echo '    <h1>'. $msg . '</h1>';
echo '      <h3>You will be redirected in 5s to <a href="../controllers/cinicio.php">Inicio</a></h3>';
echo '    </main>';
echo '  </body>';
echo '</html>';
