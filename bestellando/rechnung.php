<?php
include 'includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Ungültige Bestellung");
}

$id = intval($_GET['id']);

// Bestellung abrufen
$sql = "SELECT * FROM bestellungen WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("Bestellung nicht gefunden");
}

$bestellung = $result->fetch_assoc();

// Positionen abrufen
$posSql = "
    SELECT bp.menge, s.gericht, s.preis 
    FROM bestellpositionen bp 
    JOIN speisekarte s ON bp.speise_id = s.id 
    WHERE bp.bestellung_id = $id
";
$posResult = $conn->query($posSql);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Rechnung Bestellung #<?= $id ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 80%;
            margin: 20px auto;
        }
        h1, p {
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        .actions {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-print {
            background-color: #28a745;
        }
        .btn-print:hover {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>

<h1>Rechnung für Bestellung #<?= $id ?></h1>
<p><strong>Tischnummer:</strong> <?= htmlspecialchars($bestellung['tisch_nr']) ?></p>
<p><strong>Bestelldatum:</strong> <?= htmlspecialchars($bestellung['erstellt_am']) ?></p>

<table>
    <tr>
        <th>Gericht</th>
        <th>Menge</th>
        <th>Einzelpreis (€)</th>
        <th>Gesamt (€)</th>
    </tr>
    <?php
    $gesamt = 0;
    while ($pos = $posResult->fetch_assoc()):
        $preisGesamt = $pos['menge'] * $pos['preis'];
        $gesamt += $preisGesamt;
    ?>
    <tr>
        <td><?= htmlspecialchars($pos['gericht']) ?></td>
        <td><?= intval($pos['menge']) ?></td>
        <td><?= number_format($pos['preis'], 2, ',', '.') ?></td>
        <td><?= number_format($preisGesamt, 2, ',', '.') ?></td>
    </tr>
    <?php endwhile; ?>
    <tr>
        <th colspan="3">Gesamtpreis</th>
        <th><?= number_format($gesamt, 2, ',', '.') ?> €</th>
    </tr>
</table>

<div class="actions">
    <a href="index.php" class="btn">Zurück zur Startseite</a>
    <button onclick="window.print()" class="btn btn-print">Rechnung drucken</button>
</div>

</body>
</html>
