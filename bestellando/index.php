<?php
session_start();
include 'includes/db.php';

$error = '';

// Bestellung verarbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tisch_nr = intval($_POST['tisch_nr']);
    $gerichte = $_POST['gerichte'] ?? [];

    if ($tisch_nr > 0 && !empty($gerichte)) {
        // Neue Bestellung anlegen
        $conn->query("INSERT INTO bestellungen (tisch_nr) VALUES ($tisch_nr)");
        $bestellung_id = $conn->insert_id;

        // Bestellpositionen speichern
        foreach ($gerichte as $speise_id => $menge) {
            $menge = intval($menge);
            if ($menge > 0) {
                $conn->query("INSERT INTO bestellpositionen (bestellung_id, speise_id, menge) VALUES ($bestellung_id, $speise_id, $menge)");
            }
        }
        header("Location: bestellung.php");
        exit;
    } else {
        $error = "Bitte Tischnummer angeben und mindestens ein Gericht auswählen.";
    }
}

// Speisekarte laden
$result = $conn->query("SELECT * FROM speisekarte");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Speisekarte – Bestellung aufnehmen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 80%;
            margin: 20px auto;
        }
        h1 {
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        .error {
            color: red;
            text-align: center;
        }
        .button-area {
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .top-right {
            text-align: right;
            margin-bottom: 10px;
        }
        .top-right a {
            color: red;
            text-decoration: none;
            margin-left: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="top-right">
    <?php if (isset($_SESSION['username'])): ?>
        Angemeldet als <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
        | <a href="logout.php">Logout</a>
    <?php endif; ?>
</div>

<h1>Speisekarte</h1>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="index.php">
    <label for="tisch_nr">Tischnummer:</label>
    <input type="number" name="tisch_nr" id="tisch_nr" required min="1"><br><br>

    <table>
        <tr>
            <th>Gericht</th>
            <th>Preis (€)</th>
            <th>Menge</th>
        </tr>
        <?php while ($gericht = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($gericht['gericht']) ?></td>
            <td><?= number_format($gericht['preis'], 2, ',', '.') ?></td>
            <td><input type="number" name="gerichte[<?= $gericht['id'] ?>]" value="0" min="0"></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="button-area">
        <button type="submit" class="btn">Bestellung absenden</button>
    </div>
</form>

<div class="button-area">
    <a href="bestellung.php" class="btn">Zur Bestellübersicht</a>
</div>

</body>
</html>
