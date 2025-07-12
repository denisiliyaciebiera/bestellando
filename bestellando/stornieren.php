<?php
include 'includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Ungültige Bestellung");
}

$id = intval($_GET['id']);

// Positionen löschen
$conn->query("DELETE FROM bestellpositionen WHERE bestellung_id = $id");

// Bestellung löschen
$conn->query("DELETE FROM bestellungen WHERE id = $id");

header("Location: bestellung.php");
exit;
