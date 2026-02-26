<?php

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $wartosc = trim($_POST["texxt"]);
    echo $wartosc;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>

<form method="post">
    <input type="text" name="texxt">
    <button type="submit">Zatwierdź</button>
</form>

</body>
</html>
