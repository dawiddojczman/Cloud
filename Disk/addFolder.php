<?php
session_start();
$root = $_SESSION['root'];
$folderName = $_POST['folderName']; //nazwa nowego katalogu

if (file_exists($root . $folderName)) { //czy taki katalog już istnieje
     $_SESSION['message'] = "folder już istnieje";
} else {
     if (mkdir($root . $folderName)) {
          $_SESSION['message'] = "utworzono katalog";
     } else {
          $_SESSION['message'] = "nie udało się utworzyć katalogu";
     }
}
echo "<script>window.location = '../panel.php'</script>";//przekierowanie
