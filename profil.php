<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: logowanie.php");
    exit;
}

$loggedInUsername = $_SESSION['username'];
$uploadDir = "zdjeciaProfilowe/";
$filename = "użytkownicy.txt";
$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_picture'])){

    $file = $_FILES['profile_picture'];

    if($file['type'] !== 'image/png'){
        $message = "<p style='color:red;'>Dozwolone są tylko pliki PNG.</p>";
    }
    else if($file['size'] > 2 * 1024 * 1024){ //MAX 2MB
        $message = "<p style='color:red;'>Plik jest za duży (max 2MB).</p>";
    }
    else{
        $newFilename = strtolower($loggedInUsername) . '.png';
        $destination = $uploadDir . $newFilename;

        if(move_uploaded_file($file['tmp_name'], $destination)){
            $message = "<p style='color:green;'>Zdjęcie zostało pomyślnie zmienione!</p>";
        }
        else{
            $message = "<p style='color:red;'>Wystąpił błąd podczas zapisywania zdjęcia.</p>";
        }
    }
}


$imie = "nieznane";
$nazwisko = "nieznane";
$email = "nieznany";
$avatar = $uploadDir . "default_user_photo.png";

$userAvatarPath = $uploadDir . strtolower($loggedInUsername) . '.png';
if(file_exists($userAvatarPath)){
    $avatar = $userAvatarPath;
}

if(file_exists($filename) && is_readable($filename)){
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach($lines as $line){
        $data = explode(";", $line);

        if($loggedInUsername == $data[3]){
            $imie = $data[0];
            $nazwisko = $data[1];
            $email = $data[2];

            break;
        }
    }
} else{
    error_log("Nie znaleziono pliku tekstowego: " . $filename);
}

class TworzenieTabeli {
    public $poprawnePopkultura;
    public $poprawneGeografia;
    public $poprawneSport;
    public $poprawneMotoryzacja;
    public $liczbaPopkultura;
    public $liczbaGeografia;
    public $liczbaSport;
    public $liczbaMotoryzacja;

    public function  __construct($poprawnePopkultura, $poprawneGeografia, $poprawneSport, $poprawneMotoryzacja, $liczbaPopkultura, $liczbaGeografia, $liczbaSport, $liczbaMotoryzacja) {
        $this->poprawnePopkultura = $poprawnePopkultura;
        $this->poprawneGeografia = $poprawneGeografia;
        $this->poprawneSport = $poprawneSport;
        $this->poprawneMotoryzacja = $poprawneMotoryzacja;
        $this->liczbaPopkultura = $liczbaPopkultura;
        $this->liczbaGeografia = $liczbaGeografia;
        $this->liczbaSport = $liczbaSport;
        $this->liczbaMotoryzacja = $liczbaMotoryzacja;
    }

    public function poprawneSuma(){
        return ($this->poprawnePopkultura + $this->poprawneMotoryzacja + $this->poprawneGeografia + $this->poprawneSport);
    }

    public function liczbaSuma(){
        return ($this->liczbaPopkultura + $this->liczbaGeografia + $this->liczbaSport + $this->liczbaMotoryzacja);
    }
    public function procentPopkultura() {
        return $this->liczbaPopkultura > 0 ? round(($this->poprawnePopkultura / $this->liczbaPopkultura) * 100, 2) : 0;
    }

    public function procentGeografia() {
        return $this->liczbaGeografia > 0 ? round(($this->poprawneGeografia / $this->liczbaGeografia) * 100, 2) : 0;
    }

    public function procentSport() {
        return $this->liczbaSport > 0 ? round(($this->poprawneSport / $this->liczbaSport) * 100, 2) : 0;
    }

    public function procentMotoryzacja() {
        return $this->liczbaMotoryzacja > 0 ? round(($this->poprawneMotoryzacja / $this->liczbaMotoryzacja) * 100, 2) : 0;
    }

    public function procentSuma() {
        $sumaPytan = $this->liczbaSuma();
        return $sumaPytan > 0 ? round(($this->poprawneSuma() / $sumaPytan) * 100, 2) : 0;
    }
}

$katalogDir = "wyniki/" . $loggedInUsername . ".txt";

if(!file_exists($katalogDir)) {
    $linie = [
        "0;0;0;0;0",
        "0;0;0;0;0"
    ];

    file_put_contents($katalogDir, implode("\n", $linie));
}

$linie = file($katalogDir, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$poprawneOdp = explode(";", $linie[0]);
$liczbaPytanOdp = explode(";", $linie[1]);

$tabela = new TworzenieTabeli(
    (int)$poprawneOdp[0],
    (int)$poprawneOdp[1],
    (int)$poprawneOdp[2],
    (int)$poprawneOdp[3],
    (int)$liczbaPytanOdp[0],
    (int)$liczbaPytanOdp[1],
    (int)$liczbaPytanOdp[2],
    (int)$liczbaPytanOdp[3]
);

if (isset($_GET['action']) && $_GET['action'] == 'export') {

    $linie = file($katalogDir, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $poprawneOdp = explode(";", $linie[0]);
    $liczbaPytanOdp = explode(";", $linie[1]);

    $tabelaEksport = new TworzenieTabeli(
        (int)$poprawneOdp[0], (int)$poprawneOdp[1], (int)$poprawneOdp[2], (int)$poprawneOdp[3],
        (int)$liczbaPytanOdp[0], (int)$liczbaPytanOdp[1], (int)$liczbaPytanOdp[2], (int)$liczbaPytanOdp[3]
    );

    $nazwaPliku = "osiagniecia_" . $loggedInUsername . "_" . date('Y-m-d') . ".txt";
    $trescPliku = "Osiągnięcia użytkownika: " . $loggedInUsername . "\n";
    $trescPliku .= "Data wygenerowania raportu: " . date('Y-m-d H:i:s') . "\n";
    $trescPliku .= "--------------------------------------------------\n\n";
    $trescPliku .= "Kategoria | Poprawne odpowiedzi | Liczba pytań | Skuteczność (%)\n";
    $trescPliku .= "--------------------------------------------------\n";
    $trescPliku .= "Popkultura | " . $tabelaEksport->poprawnePopkultura . " | " . $tabelaEksport->liczbaPopkultura . " | " . $tabelaEksport->procentPopkultura() . "%\n";
    $trescPliku .= "Geografia  | " . $tabelaEksport->poprawneGeografia . " | " . $tabelaEksport->liczbaGeografia . " | " . $tabelaEksport->procentGeografia() . "%\n";
    $trescPliku .= "Sport      | " . $tabelaEksport->poprawneSport . " | " . $tabelaEksport->liczbaSport . " | " . $tabelaEksport->procentSport() . "%\n";
    $trescPliku .= "Motoryzacja| " . $tabelaEksport->poprawneMotoryzacja . " | " . $tabelaEksport->liczbaMotoryzacja . " | " . $tabelaEksport->procentMotoryzacja() . "%\n";
    $trescPliku .= "--------------------------------------------------\n";
    $trescPliku .= "SUMA       | " . $tabelaEksport->poprawneSuma() . " | " . $tabelaEksport->liczbaSuma() . " | " . $tabelaEksport->procentSuma() . "%\n";

    header('Content-Type: text/plain; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $nazwaPliku . '"');

    echo $trescPliku;
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quizz-ly | Profil</title>
    <link href="kolorki.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="mainIntro">
    <h1>Quizz-ly</h1>
</div>

<div id="profilBox">
    <img class="profilowe" src="<?php echo $avatar; ?>" alt="Zdjęcie profilowe"><br>

    <?php if(!empty($message)) { echo $message; }?>

    <p><strong>Imię</strong></p> <?php echo $imie; ?><br>
    <p><strong>Email</strong></p> <?php echo $email; ?><br>
    <p><strong>Username</strong></p> <?php echo $loggedInUsername; ?><br>
</div>

<section id="mojProfil">


    <table id="tabelaProfil">
        <tr>
            <th colspan="6"><h1>Moje Statystyki</h1></th>
        </tr>
        <tr>
            <th>XXX</th>
            <th>Popkultura</th>
            <th>Geografia</th>
            <th>Sport</th>
            <th>Motoryzacja</th>
            <th>Suma</th>
        </tr>
        <tr>
            <th>Poprawne Odpowiedzi</th>
            <?php
            echo '<td>' . $tabela->poprawnePopkultura . '</td>';
            echo '<td>' . $tabela->poprawneGeografia . '</td>';
            echo '<td>' . $tabela->poprawneSport. '</td>';
            echo '<td>' . $tabela->poprawneMotoryzacja . '</td>';
            echo '<td>' . $tabela->poprawneSuma() . '</td>';
            ?>
        </tr>
        <tr>
            <th>Liczba Pytań</th>
            <?php
            echo '<td>' . $tabela->liczbaPopkultura . '</td>';
            echo '<td>' . $tabela->liczbaGeografia . '</td>';
            echo '<td>' . $tabela->liczbaSport. '</td>';
            echo '<td>' . $tabela->liczbaMotoryzacja . '</td>';
            echo '<td>' . $tabela->liczbaSuma() . '</td>';
            ?>
        </tr>
        <tr>
            <th>Procenty</th>
            <?php
            echo '<td>' . $tabela->procentPopkultura() . '%</td>';
            echo '<td>' . $tabela->procentGeografia() . '%</td>';
            echo '<td>' . $tabela->procentSport() . '%</td>';
            echo '<td>' . $tabela->procentMotoryzacja() . '%</td>';
            echo '<td>' . $tabela->procentSuma() . '%</td>';
            ?>
        </tr>
    </table>

    <div id="eksport">
        <form method="get">
            <input type="hidden" name="action" value="export">
            <h3>Eksportuj osiągnięcia do pliku</h3>
            <button type="submit">Eksport</button>
        </form>
    </div>

    <div class="zmianaZdjecia">
        <h4>Zmień zdjęcie profilowe!</h4>
        <p>(tylko png)</p>
        <form action="profil.php" method="post" enctype="multipart/form-data">
            <input type="file" name="profile_picture" accept="image/png" required>
            <br><br>
            <button type="submit">Zmień zdjęcie</button>
        </form>
    </div>

</section>

<br><br>

<section id="zakonczenie">
    <div id="przeniesDoGlownejStrony">
        <form action="main.php" method="get">
            <button type="submit">Strona główna!</button>
        </form>
    </div>

    <div id="przeniesDoWylogowania">
        <form action="logout.php" method="get">
            <button type="submit">Wyloguj się!</button>
        </form>
    </div>
</section>

<br><br>

</body>
</html>