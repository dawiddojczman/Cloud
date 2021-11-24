<?php
ob_start();
session_start();
$name = $_POST['nazwisko'];
setcookie('name', $name); //ciasteczko do celów logowania
$haslo = $_POST['haslo'];

require_once 'DB_connect.php'; //połączenie z bazą dancyh

$blocked = isBlocked($name); // sprawdzenie czy użytkownik jest zalogowany - true jeśli jest false jeśli nie jest

$response = ''; // odpowiedź zwrotna do użytkownika na temat stanu logowania

$q = "SELECT * FROM CloudUsers where name ='$name' limit 1";
$res = $DB_connection->query($q) or die("Nie działa $q");; //res z klienci
$resObj = $res->fetch_object();
if ($resObj->name) {
     $q = "SELECT * FROM CloudUsers  where pass = '$haslo' and name = '$resObj->name' limit 1";
     $res = $DB_connection->query($q) or die("Nie działa $q");
     $passResObj = $res->fetch_object();
     if (!$blocked) {
          if ($passResObj->pass == $haslo) {
               logUser(true, $passResObj->name); // wpisanie użytkownika do rejestru logowania
               if (checkErrors(true, $passResObj->name) == true) {
                    $DB_connection->query("UPDATE CloudUsers SET blocked=0, loginError = 0 where name = '$name'");
                    setcookie('loggedUser', $name, time() + 36000);
                    // echo "<script>document.cookie='loggedUser=$name;expires 1'</script>";
                    echo 'zalogowano';
                    echo "<script>window.location = 'panel.php';</script>";
               } else {
                    $response = "zostałeś zablokowany, odczekaj 3 minuty";
               }
          } else {
               logUser(false, $resObj->name); // wpisanie użytkownika do rejestru logowania
               checkErrors(false, $resObj->name); // sprawdzenie ilości błędów - logged - czy użytkownik się zalogował true/false
               $response = "Złe hasło";
          }
     } else {
          $response = "zostałeś zablokowany, odczekaj 3 minuty";
     }
} else {
     $response = "Nie ma Cię w bazie $name!";
}
$_SESSION['response'] = $response; // wysłanie odpowiedzi zwrotnej
echo "<script>window.location = 'index.php';</script>"; //przekierowanie do strony głównej

//boolean - czy się zalogował
function logUser($bool, $name)
{
     global $DB_connection;

     $q = "INSERT into CloudLogi(idu,logged) values(
        (SELECT idu from CloudUsers where name= '$name'), '$bool' )";
     $DB_connection->query($q) or die("nie działa $q");
}

// sprawdzenie ilości błędów - logged - czy użytkownik się zalogował true/false
function checkErrors($logged, $name)
{
     global $DB_connection, $response, $blocked;
     $q = "SELECT datetime,loginError,blocked from CloudUsers inner join CloudLogi using(idu) where name = '$name' order by datetime";
     $res = $DB_connection->query($q) or die("Nie działa $q");
     $res = $res->fetch_object();
     $errors = ($logged ?  $res->loginError : $res->loginError += 1);

     if ($errors > 3 && !$blocked) {
          $q = "UPDATE CloudUsers SET blocked = 1 where name= '$name'";
          $DB_connection->query($q) or die("Nie działa $q");

          return false;
     } else {
          $q = "UPDATE CloudUsers Set loginError = $errors where name = '$name' ";
          $DB_connection->query($q) or die("Nie działa $q");
     }
     return true;
}

function isBlocked($name)
{
     global $DB_connection;
     $q = "SELECT blocked from CloudUsers where name = '$name'";
     $res = $DB_connection->query($q);

     if ($res) {
          if ($res->fetch_object()->blocked) {
               $q = "SELECT datetime from CloudLogi where idu = (SELECT idu from CloudUsers where name='$name') and logged=0 order by datetime desc limit 1";
               $res = $DB_connection->query($q) or die("Nie działa $q");
               $datetime = $res->fetch_object()->datetime;

               $curDatetime = $DB_connection->query("SELECT now() as currDatetime ")->fetch_object()->currDatetime;

               // echo (strtotime($curDatetime) - strtotime($datetime));
               if ((strtotime($curDatetime) - strtotime($datetime)) > 60) {
                    return false;
               } else {
                    return true;
               }
          } else {
               return false;
          }
     } else {
          return false;
     }
}
