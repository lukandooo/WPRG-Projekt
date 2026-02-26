<?php
session_start();

$errorLog = "";
$opisKat = "";
$nazwaKat = "";

if (isset($_GET['kategoria'])) {
    $_SESSION['kategoria_quizu'] = $_GET['kategoria'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nazwa_quizu'])) {
    $_SESSION['nazwa_quizu'] = $_POST['nazwa_quizu'];
}

if($_SESSION['kategoria_quizu'] == 'popkultura'){
    $plikDir = "QUIZY/popkultura/TREŚĆ.txt";
    $nazwaKat = "Popkultura";
    $opisKat = "Sprawdź, jak dobrze znasz filmy, seriale, muzykę i gwiazdy! Quizy z popkultury to gratka dla fanów wszystkiego, co na topie!";
}
else if($_SESSION['kategoria_quizu'] == 'sport'){
    $plikDir = "QUIZY/sport/TREŚĆ.txt";
    $nazwaKat = "Sport";
    $opisKat = "Masz sportowego ducha? Przesteuj swoją wiedzę o piłce nożnej, koszykówce i wielu innych dyscyplinach!";
}
else if($_SESSION['kategoria_quizu'] == 'geografia'){
    $plikDir = "QUIZY/geografia/TREŚĆ.txt";
    $nazwaKat = "Geografia";
    $opisKat = "Od stolic po szczyty gór - geograficzne quizy zabiorą cię w podróż dookoła świata bez wychodzenia z domu!";
}
else if($_SESSION['kategoria_quizu'] == 'motoryzacja'){
    $plikDir = "QUIZY/motoryzacja/TREŚĆ.txt";
    $nazwaKat = "Motoryzacja";
    $opisKat = "Silniki, marki, modele i historia motoryzacji - sprawdź, czy jesteś prawdziwym fanem czterech kółek";
}

class trescQuizu {
    public $nazwaQuizu;
    public $opisQuizu;

    public function __construct($nazwaQuizu, $opisQuizu) {
        $this->nazwaQuizu = $nazwaQuizu;
        $this->opisQuizu = $opisQuizu;
    }

    public function getNazwaQuizu() {
        return $this->nazwaQuizu;
    }

    public function getOpisQuizu() {
        return $this->opisQuizu;
    }
}

$quizy = [];
if(file_exists($plikDir)){
    $linie = file($plikDir, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach($linie as $linia){
        $dane = explode(";", $linia);
        if(count($dane) == 2){
            $quiz = new trescQuizu($dane[0], $dane[1]);
            $quizy[] = $quiz;
        }
    }
}
else {
    $errorLog = "Nie znaleziono pliku";
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="kolorki.css" rel="stylesheet" type="text/css">
    <title>Quizz-ly | lista quizów</title>
</head>
<body>
    <div id="mainIntro">
        <h1>Quizz-ly</h1>
    </div>

    <?php
    if($errorLog != ""){
        echo '<h5 style="color:red">' .  $errorLog . '</h5>';
    }
    ?>

    <div id="container">
        <?php
        echo '<h2>' .  $nazwaKat . '</h2>';
        echo '<p>' . $opisKat . '</p>';
        ?>
    </div>


    <div id="quizList">
        <?php
        if(count($quizy) == 0){
            echo '<h3>Brak Quizów w tej kategorii!</h3>';
        }
        else{
            echo '<table>';
                echo '<tr>';
                    echo '<th>Nazwa</th>';
                    echo '<th>Opis</th>';
                    echo '<th>Wybierz</th>';
                echo '</tr>';

                foreach($quizy as $quiz){
                    echo '<tr>';
                        echo '<td id="NazwaQuizu">' . htmlspecialchars($quiz->getNazwaQuizu()) . '</td>';
                        echo '<td>' . htmlspecialchars($quiz->getOpisQuizu()) . '</td>';

                        echo '<td>';
                            echo '<form action="rozwiazywanieQuizow.php" method="post">';
                                echo '<input type="hidden" name="nazwa_quizu" value="' . htmlspecialchars($quiz->getNazwaQuizu()) . '">';
                                echo '<button type="submit" class="btn-wybierz">Wybierz Quiz</button>';
                            echo '</form>';
                        echo '</td>';

                    echo '</tr>';
                }

            echo '</table>';
        }
        ?>
    </div>

    <br><br>

    <section id="zakonczenie">
        <div id="przeniesDoGlownejStrony">
            <form action="main.php" method="get">
                <button type="submit">Strona główna!</button>
            </form>
        </div>

        <div id="przejdzDoProfilu">
            <form action="profil.php" method="get">
                <button type="submit">Mój Profil</button>
            </form>
        </div>
    </section>

    <br><br>
</body>
</html>
