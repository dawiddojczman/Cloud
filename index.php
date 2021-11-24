<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Dojczman</title>
</head>

<body>
    <h1>Dojczman Cloud</h1>
    <div class='form'>
        <h3>Logowanie</h3>
        <form action='login.php' method='POST'>
            <label for="nazwisko">
                Login: <input type="text" autofocus name="nazwisko" id="nazwisko" required value='<?php echo $_COOKIE['name']; ?>'>
            </label>
            <!-- ciasteczko logowania-->
            <label for="haslo">
                Hasło: <input type="password" name="haslo" id="haslo" required>
            </label>

            <button type="submit">Zaloguj!</button>
        </form>
        <p><?php echo $_SESSION['response']; ?></p> <!-- odpowiedź zwrotna w razie niepomyślnego logowania-->
    </div>
    <div class='form'>
        <h3>Rejestracja</h3>
        <form action='registration.php' method='POST'>

            <label for="Rnazwisko">Nazwisko:
                <input type="text" name="Rnazwisko" id="Rnazwisko" required value='<?php echo $_COOKIE['Rnazwisko']; ?>'>
            </label>
            <label for="haslo1">Hasło:
                <input type="password" name="Rhaslo1" id="haslo1" required>
            </label>
            <label for="haslo2">
                <input type="password" name="Rhaslo2" id="haslo2" required>
            </label>
            <button type="submit">Zarejestruj!</button>
        </form>
        <p><?php echo $_SESSION['registrationResponse']; ?></p><!-- odpowiedź zwrotna w razie niepomyślnej rejestracji-->
    </div>
</body>

</html>