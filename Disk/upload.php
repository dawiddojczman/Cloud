<?php
session_start();

$root = $_SESSION['root']; //pobranie obecnego katalogu
print_r($_FILES);
if (is_uploaded_file($_FILES['file']['tmp_name'])) {
     $_SESSION['message'] = "Odebrano " . $_FILES['file']['name'];
     move_uploaded_file( //przeniesienie pliku do aktualnego katalogu
          $_FILES['file']['tmp_name'],
          $root . $_FILES['file']['name']
     );
} else {
     $_SESSION['message'] = 'Błąd przy przesyłaniu pliku';
}

echo "<script>window.location = '../panel.php'</script>"; //przekierowanie
