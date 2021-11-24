<?php
session_start();
$name = $_POST['Rnazwisko'];
$haslo1 = $_POST['Rhaslo1'];
$haslo2 = $_POST['Rhaslo2'];
setcookie('Rnazwisko',$nazwisko);  //ustawienie ciasteczka;

require_once 'DB_connect.php';

if ($haslo1 !== $haslo2) {
    $_SESSION['registrationResponse'] = "Hasła nie są identyczne"; //odpowiedź zwrotna w razie 
    echo "<script>window.location='index.php'</script>";         // gdy hasł a nie sa identynczne
} else {

    $q = "SELECT * FROM CloudUsers WHERE name='$name' ";
    $res = $DB_connection->query($q) or die("Zapytanie $q nie działa");
    
    if ($res->fetch_object()) { // sprawdzenie czy podany użytkownik już istnieje
        $_SESSION['registrationResponse'] = "Taki użytkownik już istnieje"; //
    } else {
        $q = "INSERT INTO CloudUsers(name,pass) VALUES('$name','$haslo1')";
        $res = $DB_connection->query($q) or die("Zapytanie $q nie działa");
        /// pomyślne zarejestrowanie i odpowiedź zwrotna
        $_SESSION['registrationResponse'] = $res ? "Dodano użytkownika" : '';
    }
}

echo "<script>window.location='index.php'</script>"; // przkierowanie na stronę główną
