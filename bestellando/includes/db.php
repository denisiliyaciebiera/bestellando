<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'restaurant';

$conn = new mysqli($host, $user, $pass, $db);

// Verbindung prüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}
?>
