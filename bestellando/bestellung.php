<?php
include 'includes/db.php';

$sql = "SELECT * FROM bestellungen ORDER BY erstellt_am DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Bestellungen Übersicht</title>
    <style>
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
        h1, h3 {
            text-align: center;
        }
        .actions {
            text-align: center;
            margin: 10px 0;
        }
        .back-button {
            display: block;
            width: fit-content;
            margin: 30px auto;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Offene Bestellungen</h1>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($bestellung = $result->fetch_assoc()): ?>
            <?php $bestellung_id = intval($bestellung['id']); ?>
            <h3>Bestellung #<?= $bestellung_id ?> – Tisch <?= htmlspecialchars($bestellung['tisch_nr']) ?> – <?= $bestellung['erstellt_am'] ?></h3>

            <?php
            $posSql = "
                SELECT bp.id AS pos_id, bp.menge, s.gericht, s.preis 
                FROM bestellpositionen bp 
                JOIN speisekarte s ON bp.speise_id = s.id 
                WHERE bp.bestellung_id = $bestellung_id
            ";
            $posResult = $conn->query($posSql);
            ?>

            <?php if ($posResult->num_rows > 0): ?>
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
                        <th><?= number_format($gesamt, 2, ',', '.') ?></th>
                    </tr>
                </table>
            <?php else: ?>
                <p style="text-align:center;">Keine Positionen gefunden.</p>
            <?php endif; ?>

            <div class="actions">
                <a href="rechnung.php?id=<?= $bestellung_id ?>">Rechnung anzeigen</a> |
                <a href="bearbeiten.php?id=<?= $bestellung_id ?>">Bearbeiten</a> |
                <a href="stornieren.php?id=<?= $bestellung_id ?>" onclick="return confirm('Bestellung wirklich stornieren?');" style="color:red;">Stornieren</a>
            </div>
            <hr>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center;">Keine Bestellungen vorhanden.</p>
    <?php endif; ?>

    <a href="index.php" class="back-button">Zurück zur Startseite</a>
</body>
</html>
