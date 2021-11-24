<?php
session_start();
session_unset();
session_destroy(); //zniszczenie sesji
$_COOKIE['loggedUser'] = '';
setcookie("loggedUser", "", time() - 36000000); //przeterminowanie ciasteczka

echo "<script>window.location = 'index.php'</script>";
