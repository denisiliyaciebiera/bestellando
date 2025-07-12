<?php
include 'includes/db.php';

// Hier Username und Passwort festlegen
$username = 'testuser';
$passwort_plain = 'geheim123';

// Passwort hashen
$passwort_hash = password_hash($passwort_plain, PASSWORD_DEFAULT);

// Prepared Statement zum sicheren Einfügen
$stmt = $conn->prepare("INSERT INTO users (username, passwort) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $passwort_hash);

if ($stmt->execute()) {
    echo "User '$username' erfolgreich angelegt.";
} else {
    echo "Fehler: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>