<?php
session_start();

$root = $_SESSION['root']; //aktualny katalog
$fileName = $_POST['toRemove']; //plik do usunięcia

if (rmdir($root . $fileName)) { //usunięcie katalogu
     $_SESSION['message'] = 'Usunięto';
} else {
     $_SESSION['message'] = "Nie usunięto katalogu";
}
echo "<script>window.location = '../panel.php'</script>"; //przekierowanie
