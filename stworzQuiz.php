<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $kategoria = $_POST["category"];

    if($kategoria == "popkultura"){
        $katalogDir = "QUIZY/popkultura/";
    }
    else if($kategoria == "sport"){
        $katalogDir = "QUIZY/sport/";
    }
    else if($kategoria == "geografia"){
        $katalogDir = "QUIZY/geografia/";
    }
    else{
        $katalogDir = "QUIZY/motoryzacja/";
    }

    $nazwa = trim($_POST["nazwa"]);

    $lines = file($katalogDir."TREŚĆ.txt", FILE_IGNORE_NEW_LINES);
    $nazwaWystepuje = false;

    foreach($lines as $line){
        $data = explode(";", $line);
        if($data[0] == $nazwa){
            $nazwaWystepuje = true;
        }
    }

    $numerPytania = 1;
    $lines = file($katalogDir."PYTANIA.txt", FILE_IGNORE_NEW_LINES);
    if(!$nazwaWystepuje){
        $opis = trim($_POST["opis"]);
        $inputTresc = "$nazwa;$opis\n";
        file_put_contents($katalogDir."TREŚĆ.txt", $inputTresc,  FILE_APPEND);
    }
    else{
        foreach($lines as $line){
            $data = explode(";", $line);
            if($data[0] == $nazwa){
                if((int)$data[1] >= $numerPytania){
                    $numerPytania = ((int)$data[1]) + 1;
                }
            }
        }
    }

    $rodzajPytania = $_POST["rodzajPytania"];
    echo "Rodzaj pytania: " . $rodzajPytania . "\n";

    $trescPytania = trim($_POST["trescPytania"]);
    echo "Treść pytania: " . $trescPytania . "\n";

    $odpowiedziPytania = trim($_POST["odpowiedziPytania"]);
    echo "Odpowiedzi pytania:" . $odpowiedziPytania . "\n";

    $poprawnaOdpowiedz = trim($_POST["poprawnaOdpowiedz"]);
    echo "Poprawna odpowiedź pytania: " . $poprawnaOdpowiedz . "\n";

    $imagePath = "x";

    if(isset($_FILES['zdjeciePytania']) && $rodzajPytania == "image-guess"){
        $file = $_FILES['zdjeciePytania'];

        if($file['type'] !== 'image/png'){
            $message = "<p style='color:red;'>Dozwolone są tylko pliki PNG.</p>";
        }
        else if($file['size'] > 2 * 1024 * 1024){ //MAX 2MB
            $message = "<p style='color:red;'>Plik jest za duży (max 2MB).</p>";
        }
        else{
            $newFileName = $nazwa . $numerPytania . ".png";
            $destynacjaZdjecia = $katalogDir . "zdjęcia/";

            $fullFilePath = $destynacjaZdjecia . $newFileName;
            $imagePath = $fullFilePath;

            if(move_uploaded_file($file['tmp_name'], $fullFilePath)){
                echo "Zdjęcie zostało zmienione! \n";
            }
            else{
                echo "Zdjęcie nie zostało zmienione! \n";
            }
        }
    }

    $inputPytania = "$nazwa;$numerPytania;$rodzajPytania;$trescPytania;$odpowiedziPytania;$imagePath\n";
    file_put_contents($katalogDir."PYTANIA.txt", $inputPytania,  FILE_APPEND);

    $inputOdpowiedz = "$nazwa;$numerPytania;$poprawnaOdpowiedz\n";
    file_put_contents($katalogDir."ODPOWIEDZI.txt", $inputOdpowiedz,  FILE_APPEND);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Quizz-ly | Stwórz Quiz</title>
    <link href="kolorki.css" rel="stylesheet" type="text/css">
</head>
<body>

<div id="mainIntro">
    <h1>Quizz-ly</h1>
</div>

<form method="post" id="tworzenieQuizu" enctype="multipart/form-data">
    <h3>Tworzenie Quizu</h3>

    <h5>Wybierz kategorię *REQUIRED*</h5>
    <select name="category" required>
        <option value="popkultura">Popkultura</option>
        <option value="geografia">Geografia</option>
        <option value="sport">Sport</option>
        <option value="motoryzacja">Motoryzacja</option>
    </select>

    <h5>Wymyśl/Podaj UNIKATOWĄ nazwę *REQUIRED*</h5>
    <input type="text" name="nazwa" required>


    <h5>Podaj opis</h5>
    <input type="text" name="opis">


    <p>----------------------------------------------------</p>
    <h3>Pytanie</h3>

    <h5>Podaj treść pytania *REQUIRED*</h5>
    <input type="text" name="trescPytania" required>

    <p>----------------------------------------------------</p>

    <h5>Podaj Rodzaj Pytania *REQUIRED*</h5>
    <select name="rodzajPytania" required>
        <option value="single-choice">Single Choice</option>
        <option value="multiple-choice">Multiple Choice</option>
        <option value="text-input">Text Input</option>
        <option value="image-guess">Image Guess</option>
    </select>
    <br>
    <p>----------------------------------------------------</p>
    <h5>(IMAGE GUESS) prześlij zdjęcie!</h5>
    <input type="file" name="zdjeciePytania" accept="image/png">
    <p>----------------------------------------------------</p>

    <h5>(SINGLE CHOICE, MULTIPLE CHOICE) Prześlij 4 odpowiedzi po przecinku (odp1,odp2,odp3,odp4)</h5>
    <input type="text" name="odpowiedziPytania" size="70">

    <p>----------------------------------------------------</p>

    <h5>Prześlij odpowiedź lub odpowiedzi *REQUIRED*</h5>
    <input type="text" name="poprawnaOdpowiedz" size="50" required>

    <br><br>
    <button type="submit">Zatwierdź</button>
    <br><br><br>
</form>

<div id="przeniesDoGlownejStrony">
    <form action="main.php" method="get">
        <button type="submit">Strona główna!</button>
    </form>
</div>

<br>

</body>
</html>
