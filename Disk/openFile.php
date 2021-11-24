<?php
session_start();
$root = $_SESSION['root'];
$user = $_COOKIE['loggedUser'];

$back = $_GET['back'];
$rootFolder = $_GET['root'];
$folderName = $_GET['folderName'];
$fileName = $_GET['fileName'];

if ($folderName) {
     $_SESSION['root'] = $root . $folderName . '/';
}
if ($rootFolder) {
     $_SESSION['root'] = $_SERVER['DOCUMENT_ROOT'] . "z7/Disk/$user/";
}

if ($back) { //jeśli klinięte cofnięcie i katalog jest katalogiem 
     // usera to przekieruj do panelu bez przenoszenia lokalizacji
     if ($root == "/z7/Disk/$user/") {
          echo "<script>window.location = '../panel.php'</script>";
     } else { // rozbij ścieżkę na foldery, zdejmij ostatni folder i połącz w ścieżkę
          $arr = preg_split('/\//', $root);
          $poped =  array_pop($arr);
          echo $poped;
          if ($poped == '') {
               array_pop($arr);
          }
          $_SESSION['root'] = join('/', $arr) . '/';
     }
}
if ($fileName) {
     $cut = substr($root, 31);
     echo "<script>window.location = '$cut/$fileName'</script>";
} //otworzenie pliku
echo "<script>window.location = '../panel.php'</script>"; //przekierowanie
