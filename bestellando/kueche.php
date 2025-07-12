<?php
include 'includes/db.php';

// Status-Update verarbeiten (z.B. Bestellung auf „fertig“ setzen)
if (isset($_GET['fertig']) && is_numeric($_GET['fertig'])) {
    $id = intval($_GET['fertig']);
    $conn->query("UPDATE bestellungen SET status='fertig' WHERE id = $id");
    header("Location: kueche.php");
    exit;
}

// Alle offenen und in Bearbeitung Bestellungen abrufen
$sql = "SELECT * FROM bestellungen WHERE status IN ('offen', 'in_bearbeitung') ORDER BY erstellt_am ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Küchenansicht</title>
    <style>
        table { border-collapse: collapse; width: 90%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background-color: #f4f4f4; }
        h1 { text-align: center; }
        .status-offen { color: red; font-weight: bold; }
        .status-in_bearbeitung { color: orange; font-weight: bold; }
    </style>
</head>
<body>
<h1>Küchenansicht: Offene Bestellungen</h1>

<?php
if ($result->num_rows > 0) {
    while ($bestellung = $result->fetch_assoc()) {
        $statusClass = 'status-' . $bestellung['status'];
        echo "<h3>Bestellung #{$bestellung['id']} - Tisch {$bestellung['tisch_nr']} - <span class='$statusClass'>{$bestellung['status']}</span></h3>";

        // Bestellpositionen abfragen (ohne Preise, nur Menge + Gericht)
        $sqlPos = "
            SELECT bp.menge, s.gericht 
            FROM bestellpositionen bp 
            JOIN speisekarte s ON bp.speise_id = s.id 
            WHERE bp.bestellung_id = {$bestellung['id']}
        ";
        $posResult = $conn->query($sqlPos);

        if ($posResult->num_rows > 0) {
            echo "<table><tr><th>Gericht</th><th>Menge</th></tr>";

            while ($pos = $posResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($pos['gericht']) . "</td>";
                echo "<td>" . intval($pos['menge']) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>Keine Positionen gefunden.</p>";
        }

        // Button um Status auf „fertig“ zu setzen
        echo "<p><a href='kueche.php?fertig={$bestellung['id']}' onclick='return confirm(\"Bestellung als fertig markieren?\");'>Bestellung als fertig markieren</a></p>";
        echo "<hr>";
    }
} else {
    echo "<p>Keine offenen Bestellungen.</p>";
}
?>

</body>
</html>
