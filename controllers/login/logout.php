<?php
session_start();
session_unset();
session_destroy();
setcookie(session_name(), '', time() - 9999, '/');
header("Location: ../../index.php");
