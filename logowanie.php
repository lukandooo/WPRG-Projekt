<?php
session_start();
$blad = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = trim($_POST["username"]);
    $haslo = $_POST['haslo'];

    if(file_exists("użytkownicy.txt")){
        $lines = file("użytkownicy.txt", FILE_IGNORE_NEW_LINES);
        foreach($lines as $line){
            $data = explode(";", $line);
            if(isset($data[3]) && isset($data[4])){
                if($data[3] == $username && password_verify($haslo, $data[4])){
                    $_SESSION['username'] = $username;
                    header("Location: main.php");
                    exit();
                }
            }
        }
    }

    $blad = "Błędny username lub hasło!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quizz-ly | Logowanie</title>
    <link href="kolorki.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="mainIntro">
    <h1>Quizz-ly</h1>
</div>

<br><br>

<form method="post" id="GetIn" enctype="multipart/form-data">
    <h2>Logowanie</h2>

    <?php
    if(!empty($blad)) {
        echo "<h4 style='color:red;'>$blad</h4>";
    }
    ?>

    <input type="text" name="username" placeholder="username"><br>
    <br>
    <input type="password" name="haslo" placeholder="password"><br>
    <br>
    <button type="submit">Zatwierdź</button>
</form>

<br><br>

<div id="przeniesDoRejestracji">
    <form action="rejestracja.php" method="get">
        <button type="submit">Nie mam jeszcze konta</button>
    </form>
</div>
</body>
</html>