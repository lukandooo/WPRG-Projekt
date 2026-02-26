<?php

session_start();

$liczbaPytan = $_SESSION['liczba-pytan'];
$poprawneOdpowiedzi = $_SESSION['poprawne-pytania'];
$username = $_SESSION['username'];
$kategoria = $_SESSION['kategoria_quizu'];
$nazwa = $_SESSION['nazwa_quizu'];

$katalogDir = "wyniki/" . $username . ".txt";

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

$mapa = [
    'popkultura' => 0,
    'geografia' => 1,
    'sport' => 2,
    'motoryzacja' => 3
];

$index = $mapa[$kategoria] ?? -1;

if ($index !== -1) {
    // Aktualizacja
    $poprawneOdp[$index] += $poprawneOdpowiedzi;
    $liczbaPytanOdp[$index] += $liczbaPytan;

    // Suma
    $poprawneOdp[4] += $poprawneOdpowiedzi;
    $liczbaPytanOdp[4] += $liczbaPytan;

    // Zapis
    $linie[0] = implode(";", $poprawneOdp);
    $linie[1] = implode(";", $liczbaPytanOdp);
    file_put_contents($katalogDir, implode("\n", $linie));
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quizz-ly | Wyniki</title>
    <link href="kolorki.css" rel="stylesheet" type="text/css">
</head>
<body>

<div id="mainIntro">
    <h1>Quizz-ly</h1>
</div>

<div id="wyniki">
    <?php
    echo '<h1>Nazwa quizu: <a>' . $nazwa . '</a></h1>';
    echo '<h3>Liczba poprawnych odpowiedzi: <a>' . $poprawneOdpowiedzi . '</a></h3>';
    echo '<h3>Liczba pytań: <a>' . $liczbaPytan . '</a></h3>';
    ?>
</div>

<br><br>

<section id="zakonczenie">
    <div id="przejdzDoProfilu">
        <form action="profil.php" method="get">
            <button type="submit">Mój Profil</button>
        </form>
    </div>

    <div id="przeniesDoGlownejStrony">
        <form action="main.php" method="get">
            <button type="submit">Strona główna!</button>
        </form>
    </div>
</section>

</body>
</html>
