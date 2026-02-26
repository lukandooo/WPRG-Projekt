<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'projektwprg');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>