<?php
session_start();

if (isset($_GET['kategoria'])) {
    $_SESSION['kategoria_quizu'] = $_GET['kategoria'];
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quizz-ly</title>
    <link href="kolorki.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="mainIntro">
    <h1>Quizz-ly</h1>
</div>

<div id="container">
    <h2>Witaj w Quizzly!</h2>
    <p>Twoje ulubione miejsce do rozwiązywania i tworzenia quizów!</p>
</div>

<div id="mainPage">
    <section id="opis-quizzly" class="card">
        <h2 class="card-title">Co oferujemy?</h2>
        <ul>
            <li>
                <h4>Różnorodne Quizy</h4>
                <ul class="lista-kategorii">
                    <?php
                    echo '<li><a href="listaQuizow.php?kategoria=popkultura">Popkultura</a></li>';
                    echo '<li><a href="listaQuizow.php?kategoria=geografia">Geografia</a></li>';
                    echo '<li><a href="listaQuizow.php?kategoria=sport">Sport</a></li>';
                    echo '<li><a href="listaQuizow.php?kategoria=motoryzacja">Motoryzacja</a></li>';
                    ?>
                </ul>
            </li>
            <li><h4><a href="profil.php">Śledzenie statystyk</a></h4></li>
            <?php
            echo '<li><h4><a href="rozwiazywanieQuizow.php?kategoria=quizdnia">Quiz Dnia</a></h4></li>';
            ?>
            <li><h4><a href="stworzQuiz.php">Tworzenie własnych quizów</a></h4></li>
        </ul>
    </section>

    <section id="oPowstaniuStrony" class="card">
        <h2 class="card-title">Jak powstała strona Quizz-ly?</h2>
        <p>Strona internetowa Quizz-ly powstała w ramach projektu z WPRG.</p>
        <p>Autorem projektu jest Łukasz Święcicki (indeks: 32699), który wybrał serwis quizowy ze względu na autentyczną pasję do testowania wiedzy i poszerzania swoich horyzontów. Strona powstała po wielu nieudanych próbach i przestrzeni kilku tygodni.</p>
        <p>Mimo tego, że sama strona może wydawać się trywialna i podstawowa, to autor jest z niej bardzo dumny.</p>
    </section>
</div>

<br><br>

<section id="zakonczenie">

    <div id="przejdzDoProfilu">
        <form action="profil.php" method="get">
            <button type="submit">Mój Profil</button>
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