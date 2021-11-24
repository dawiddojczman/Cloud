<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="style.css">
     <title>UserPanel</title>
</head>

<body>
     <?php
     echo $_COOKIE['loggedUser'] ? '' : "<script>window.location = 'index.php'</script>"; //jeśli nie ma ciacha użytkownika to przekieruj do logowania
     $user = $_COOKIE['loggedUser'];

     if (!file_exists("Disk/$user")) { //sprawdzenie czy folder użytkownika istnieje
          if (!mkdir("Disk/$user")) { //jeśli nie istnieje to go utwórz
               echo 'nie udało się utworzyć katalogu';
          } else {
               $root = "Disk/$user/"; // jeśli istnieje to ustaw root jako Disk/nazwa_użytkownika
          }
     } else {
          if (!isset($_SESSION['root'])) { //jeśli nie już ustawiony katalog root
               $_SESSION['root'] = $_SERVER['DOCUMENT_ROOT'] . 'z7/Disk/' . $user . '/'; //ustaw katalog root do zmeinnej sesyjnej
          }
          $root = $_SESSION['root']; // jeśli jest ustawiony, to pobierz z sesji
     }
     ?>

     <p>Witaj <?php
               require_once 'DB_connect.php';
               $q = "SELECT loginError from CloudUsers where name = '$user' and loginError>0";
               $res = $DB_connection->query($q) or die("nie działa $q");
               if ($res->fetch_object()->loginError) {
                    echo "<span style='color:tomato;'>$user</span>";
               } else {
                    echo $user;
               }

               $q = "UPDATE CloudUsers SET loginError = 0 where name = '$user'";
               $res = $DB_connection->query($q) or die("nie działa $q");


               ?></p>
     <p>użyj right click na plikach</p>
     <form action="logout.php" method="post">
          <button type="submit">logout</button>
     </form>

     <div class="upload">
          <form action="Disk/upload.php" method="post" enctype="multipart/form-data">
               <label for="fileToUpload">Select file to upload:<input type="file" name="file" id="fileToUpload"></label>
               <button type="submit" value="Upload" name="submit">Upload</button>
          </form>
     </div>
     <div class="addfolder">
          <form class="addFolder" action="Disk/addFolder.php" method="POST">
               <input type="text" name="folderName" id="folderName" placeholder="nazwa folderu" required>
               <input type="submit" value="addFolder">
          </form>
     </div>
     <div class="storage">
          <h2>Your files: </h2>
          <?php
          echo "<p class='message'>" . $_SESSION['message'] . "</p>";

          $content = scandir($root); //zczytaj pliki
          echo "<form action='Disk/openFile.php' method='get'>";
          foreach ($content as $file) { //wyświetl każdy plik
               if ($file == '..') {
                    echo "<input class='nav' type='submit' value='back' name='back'  >";
               }
               if ($file == '.') {
                    echo "<input class='nav' type='submit' value='root' name='root'  >";
               }
               if (is_dir($root . $file) && $file != '.' && $file != '..') {
                    echo "<input class='folder' type='submit' value='$file' name='folderName' >";
               }
               if (is_file($root . $file)  && $file != '.' && $file != '..') {
                    echo "<input class='file' type='submit' value='$file' name='fileName'  >";
               }
          }
          echo "</form>";
          ?>
     </div>
     <script>
          const elements = document.querySelectorAll('.folder,.file').forEach(el => {
               el.addEventListener('contextmenu', (event) => {
                    const rem = document.querySelector('.menu');
                    if (rem) {
                         rem.remove()
                    }
                    event.preventDefault();
                    const menu = document.createElement('div'); //stworzenie div do celów kontekstmenu
                    menu.setAttribute("class", 'menu');
                    menu.innerHTML = `<form class='menu' action='Disk/removeFile.php' method='POST'>
                         <input type='text' style='display:none;' value='${event.target.value}' name='toRemove' id='toRemove'>
                         <input id='submit' type="submit" value="remove">
                    </form`; //ustawienie jesgo zawartości
                    menu.style.top = `${event.clientY}px`;
                    menu.style.left = `${event.clientX}px`; //określenie jego pozycji
                    document.querySelector('.storage').appendChild(menu); //umiejscowienie go na ekranie
                    // usuwanie menu po kliknięciu gdzieś indziej lub po przewijaniu
                    window.addEventListener('scroll', () => menu.remove());
                    window.addEventListener('click', (event) => {
                         if (event.target !== menu.querySelector('#submit')) {
                              menu.remove();
                         }
                    });
               });
          });
     </script>
</body>

</html>