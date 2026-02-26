<?php
session_start();

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imie = trim($_POST["imie"]);
    $nazwisko = trim($_POST["nazwisko"]);
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $haslo1 = $_POST["haslo1"];
    $haslo2 = $_POST['haslo2'];

    if($haslo1 != $haslo2) {
        $message = "Hasła nie są takie same!";
    }
    else if(empty($imie) || empty($nazwisko) || empty($email) || empty($username)  || empty($haslo1)) {
        $message = "Wszystkie pola muszą być wypełnione";
    }

    if(file_exists("użytkownicy.txt")){
        $lines = file("użytkownicy.txt", FILE_IGNORE_NEW_LINES);
        foreach($lines as $line) {
            $data = explode(";", $line);

            if(isset($data[2]) && isset($data[3])){
                if($data[2] == $email){
                    $message = "Podany adres email posiada już konto!";
                    break;
                }
                if($data[3] == $username){
                    $message = "Istnieje już konto z taką nazwą!";
                    break;
                }
            }
        }
    }

    if($message === "") {
        $hashedPassword = password_hash($haslo1, PASSWORD_DEFAULT);
        $line = "$imie;$nazwisko;$email;$username;$hashedPassword\n";
        file_put_contents("użytkownicy.txt", $line, FILE_APPEND);
        $_SESSION['username'] = $username;
        header("Location: main.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quizz-ly | Rejestracja</title>
    <link href="kolorki.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="mainIntro">
    <h1>Quizz-ly</h1>
</div>

<br><br>

<form method="post" id="GetIn" enctype="multipart/form-data">
    <h2>Rejestracja</h2>

    <?php
    if(!empty($message)) {
        echo "<h4 style='color:red;'>$message</h4>";
    }
    ?>

    <input type="text" name="imie" placeholder="Imię">
    <br><br>
    <input type="text" name="nazwisko" placeholder="Nazwisko">
    <br><br>
    <input type="email" name="email" placeholder="Email">
    <br><br>
    <input type="text" name="username" placeholder="Username">
    <br><br>
    <input type="password" name="haslo1" placeholder="Hasło">
    <br><br>
    <input type="password" name="haslo2" placeholder="Powtórz hasło">
    <br><br>
    <button type="submit">Zatwierdź</button>
</form>

<br><br>

<div id="przeniesDoLogowania">
    <form action="logowanie.php" method="get">
        <button type="submit">Już masz konto?</button>
    </form>
</div>
</body>
</html>