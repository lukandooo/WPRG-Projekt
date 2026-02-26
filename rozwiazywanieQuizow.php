<?php

session_start();

$errorLog = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nazwa_quizu'])) {
    $_SESSION['nazwa_quizu'] = $_POST['nazwa_quizu'];
}

class PytanieQuiz {
    public $nazwaPytania;
    public $numerPytania;
    public $rodzajPytania;
    public $trescPytania;
    public $odpowiedziPytania;
    public $imagePathPytania;

    public function __construct($nazwaPytania, $numerPytania, $rodzajPytania, $trescPytania, $odpowiedziPytania, $imagePathPytania) {
        $this->nazwaPytania = $nazwaPytania;
        $this->numerPytania = $numerPytania;
        $this->rodzajPytania = $rodzajPytania;
        $this->trescPytania = $trescPytania;
        $this->odpowiedziPytania = $odpowiedziPytania;
        $this->imagePathPytania = $imagePathPytania;
    }

    public function getNazwaPytania() {
        return $this->nazwaPytania;
    }
    public function getNumerPytania() {
        return $this->numerPytania;
    }
    public function getRodzajPytania() {
        return $this->rodzajPytania;
    }
    public function getTrescPytania() {
        return $this->trescPytania;
    }
    public function getOdpowiedziPytania() {
        return $this->odpowiedziPytania;
    }
    public function getImagePathPytania() {
        return $this->imagePathPytania;
    }

}

class OdpowiedzQuiz {
    public $nazwaQuizu;
    public $numerPytania;
    public $odpowiedz;

    public function __construct($nazwaQuizu, $numerPytania, $odpowiedz) {
        $this->nazwaQuizu = $nazwaQuizu;
        $this->numerPytania = $numerPytania;
        $this->odpowiedz = $odpowiedz;
    }

    public function getNazwaQuizu() {
        return $this->nazwaQuizu;
    }
    public function getNumerPytania() {
        return $this->numerPytania;
    }
    public function getOdpowiedz() {
        return $this->odpowiedz;
    }
}

if (isset($_GET['kategoria'])) {
    $_SESSION['kategoria_quizu'] = $_GET['kategoria'];
}

if($_SESSION['kategoria_quizu'] == 'popkultura'){
    $pytaniaDir = "QUIZY/popkultura/PYTANIA.txt";
    $odpowiedziDir = "QUIZY/popkultura/ODPOWIEDZI.txt";
}
else if($_SESSION['kategoria_quizu'] == 'sport'){
    $pytaniaDir = "QUIZY/sport/PYTANIA.txt";
    $odpowiedziDir = "QUIZY/sport/ODPOWIEDZI.txt";
}
else if($_SESSION['kategoria_quizu'] == 'geografia'){
    $pytaniaDir = "QUIZY/geografia/PYTANIA.txt";
    $odpowiedziDir = "QUIZY/geografia/ODPOWIEDZI.txt";
}
else if($_SESSION['kategoria_quizu'] == 'motoryzacja'){
    $pytaniaDir = "QUIZY/motoryzacja/PYTANIA.txt";
    $odpowiedziDir = "QUIZY/motoryzacja/ODPOWIEDZI.txt";
}
else if($_SESSION['kategoria_quizu'] == 'quizdnia'){
    $pytaniaDir = "quizDnia/PYTANIA.txt";
    $odpowiedziDir = "quizDnia/ODPOWIEDZI.txt";


    $numerDniaTygodnia = date('N');
    $_SESSION['nazwa_quizu'] = $numerDniaTygodnia;
}

$pytania = [];
if(file_exists($pytaniaDir)){
    $linie = file($pytaniaDir, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach($linie as $linia){
        $dane = explode(";", $linia);

        if($dane[0] == $_SESSION['nazwa_quizu']){
            $pytanie = new PytanieQuiz($dane[0], $dane[1], $dane[2],  $dane[3], $dane[4], $dane[5]);
            $pytania[] = $pytanie;
        }
    }
}
else{
    $errorLog = "Nie udało się znaleźć pliku z Pytaniami!";
}

$odpowiedzi = [];
if(file_exists($odpowiedziDir)){
    $linie = file($odpowiedziDir, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach($linie as $linia){
        $dane = explode(";", $linia);

        if($dane[0] == $_SESSION['nazwa_quizu']){
            $odpowiedz = new OdpowiedzQuiz($dane[0], $dane[1], $dane[2]);
            $odpowiedzi[] = $odpowiedz;
        }
    }
}
else{
    $errorLog = "Nie udało się znaleźć pliku z Odpowiedziami!";
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])){
    $liczbaPoprawnychOdpowiedzi = 0;
    $liczbaPytan = count($odpowiedzi);

    foreach($odpowiedzi as $odpowiedz){
        $numer = $odpowiedz->getNumerPytania();

        if(isset($_POST[$numer])){
            $odpowiedzUzytkownika = $_POST[$numer];

            if(is_array($odpowiedzUzytkownika)){
                $poprawna = explode(",", $odpowiedz->getOdpowiedz());
                $poprawna = array_map('trim', $poprawna);
                $odpowiedzUzytkownika = array_map('trim', $odpowiedzUzytkownika);
                sort($poprawna);
                sort($odpowiedzUzytkownika);
                if ($poprawna == $odpowiedzUzytkownika) {
                    $liczbaPoprawnychOdpowiedzi++;
                }
            }
            else{
                if(trim(strtolower($odpowiedzUzytkownika)) == strtolower($odpowiedz->getOdpowiedz())){
                    $liczbaPoprawnychOdpowiedzi++;
                }
            }
        }

    }

    $_SESSION['poprawne-pytania'] =  $liczbaPoprawnychOdpowiedzi;
    $_SESSION['liczba-pytan'] =  $liczbaPytan;
    header("Location: wyniki.php");
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="kolorki.css" rel="stylesheet" type="text/css">
    <title>Quizz-ly | Rozwiązywanie Quizów</title>
</head>

<body>
    <div id="mainIntro">
        <h1>Quizz-ly</h1>
    </div>

    <?php
    if($errorLog != ""){
        echo '<h1 style="color:red">' . $errorLog . '</h1>';
    }
    ?>

    <div id="quiz">
        <?php
            echo '<h1>Nazwa quizu: <a>' .  $_SESSION['nazwa_quizu'] . '</a></h1>';
        ?>

        <?php
            if(count($pytania) > 0){
                echo '<form method="post" name="mainQuiz">';

                    foreach($pytania as $pytanie){
                        echo '<div id="PytanieWQuizie">';
                            echo '<h3>' . $pytanie->getNumerPytania() . ". " . $pytanie->getTrescPytania() . '</h3>';

                            $odpowiedziPytania = explode(",", $pytanie->getOdpowiedziPytania());

                            if($pytanie->getRodzajPytania() == "text-input"){
                                echo '<h4>Odpowiedź:</h4>';
                                echo '<input type="text" name="' . $pytanie->getNumerPytania() . '"required>';
                            }
                            else {
                                if($pytanie->getRodzajPytania() == "image-guess"){
                                    echo '<img src="' . $pytanie->getImagePathPytania() . '" alt="">';
                                }

                                echo '<h4>Odpowiedzi:</h4>';

                                if($pytanie->getRodzajPytania() == "multiple-choice"){
                                    echo '<label><input type="checkbox" name="' . $pytanie->getNumerPytania() . '[]" value="' . $odpowiedziPytania[0] . '">' . $odpowiedziPytania[0] . '</input></label>';
                                    echo '<label><input type="checkbox" name="' . $pytanie->getNumerPytania() . '[]" value="' . $odpowiedziPytania[1] . '">' . $odpowiedziPytania[1] . '</input></label>';
                                    echo '<label><input type="checkbox" name="' . $pytanie->getNumerPytania() . '[]" value="' . $odpowiedziPytania[2] . '">' . $odpowiedziPytania[2] . '</input></label>';
                                    echo '<label><input type="checkbox" name="' . $pytanie->getNumerPytania() . '[]" value="' . $odpowiedziPytania[3] . '">' . $odpowiedziPytania[3] . '</input></label>';
                                }
                                else{
                                    echo '<label><input type="radio" name="' . $pytanie->getNumerPytania() . '" value="' . $odpowiedziPytania[0] . '">' . $odpowiedziPytania[0] . '</input></label>';
                                    echo '<label><input type="radio" name="' . $pytanie->getNumerPytania() . '" value="' . $odpowiedziPytania[1] . '">' . $odpowiedziPytania[1] . '</input></label>';
                                    echo '<label><input type="radio" name="' . $pytanie->getNumerPytania() . '" value="' . $odpowiedziPytania[2] . '">' . $odpowiedziPytania[2] . '</input></label>';
                                    echo '<label><input type="radio" name="' . $pytanie->getNumerPytania() . '" value="' . $odpowiedziPytania[3] . '">' . $odpowiedziPytania[3] . '</input></label>';
                                }
                            }
                        echo '</div>';
                    }

                    echo '<br>' . '<br>';
                    echo '<button type="submit" name="submit">Zatwierdź</button>';
                echo '</form>';
            }
            else{
                $errorLog = "Brak pytań!";
            }
        ?>
    </div>
</body>
</html>
