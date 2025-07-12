<?php
include 'includes/db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST-Daten prüfen und trimmen
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $passwort = isset($_POST['passwort']) ? trim($_POST['passwort']) : '';

    if ($username !== '' && $passwort !== '') {
        // Sichere Abfrage mit Prepared Statement
        $stmt = $conn->prepare("SELECT id, username, passwort FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Passwortprüfung mit password_verify
            if (password_verify($passwort, $user['passwort'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Falsches Passwort';
            }
        } else {
            $error = 'Benutzer nicht gefunden';
        }

        $stmt->close();
    } else {
        $error = 'Bitte Benutzername und Passwort eingeben';
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        Benutzername: <input type="text" name="username" required><br><br>
        Passwort: <input type="password" name="passwort" required><br><br>
        <button type="submit">Anmelden</button>
    </form>
</body>
</html>
