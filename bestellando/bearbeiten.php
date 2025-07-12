<?php
include 'includes/db.php';

if (!isset($_GET['id'])) {
    die("Keine Bestellungs-ID übergeben.");
}

$bestellung_id = intval($_GET['id']);

// Speisekarte laden
$speisen = [];
$speisen_result = $conn->query("SELECT id, gericht, preis FROM speisekarte");
while ($row = $speisen_result->fetch_assoc()) {
    $speisen[$row['id']] = $row;
}

// Bestellpositionen laden
$positionen = [];
$pos_result = $conn->query("SELECT * FROM bestellpositionen WHERE bestellung_id = $bestellung_id");
while ($row = $pos_result->fetch_assoc()) {
    $positionen[$row['speise_id']] = $row['menge'];
}

// Wenn Formular gesendet wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mengen = $_POST['menge'];

    // Bestehende Positionen löschen
    $conn->query("DELETE FROM bestellpositionen WHERE bestellung_id = $bestellung_id");

    // Neue Positionen speichern
    foreach ($mengen as $speise_id => $menge) {
        $menge = intval($menge);
        if ($menge > 0) {
            $conn->query("INSERT INTO bestellpositionen (bestellung_id, speise_id, menge) VALUES ($bestellung_id, $speise_id, $menge)");
        }
    }

    header("Location: bestellung.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bestellung bearbeiten</title>
    <style>
        table { border-collapse: collapse; width: 90%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background-color: #f4f4f4; }
        body { font-family: Arial; }
        .center { text-align: center; margin-top: 20px; }
        button, .btn-link {
            padding: 8px 16px;
            margin: 5px;
            text-decoration: none;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-link:hover, button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2 class="center">Bestellung #<?= $bestellung_id ?> bearbeiten</h2>

<form method="POST">
    <table>
        <tr>
            <th>Gericht</th>
            <th>Preis (€)</th>
            <th>Menge</th>
        </tr>
        <?php foreach ($speisen as $id => $gericht): ?>
            <tr>
                <td><?= htmlspecialchars($gericht['gericht']) ?></td>
                <td><?= number_format($gericht['preis'], 2, ',', '.') ?></td>
                <td><input type="number" name="menge[<?= $id ?>]" value="<?= $positionen[$id] ?? 0 ?>" min="0"></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="center">
        <button type="submit">Speichern</button>
        <a href="bestellung.php" class="btn-link">Zurück zur Bestellübersicht</a>
        <a href="index.php" class="btn-link">Zurück zur Startseite</a>
    </div>
</form>

</body>
</html>
