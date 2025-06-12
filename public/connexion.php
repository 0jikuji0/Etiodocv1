<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "espace_membre";

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
?>
