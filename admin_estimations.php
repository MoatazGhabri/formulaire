<?php
// admin_estimations.php
session_start();

$host = getenv('DB_HOST') ?: "51.68.44.166";
$db   = getenv('DB_NAME') ?: "vote_db";
$user = getenv('DB_USER') ?: "root";
$pass = getenv('DB_PASS') ?: "Lavisione@2025";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erreur DB: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM estimations ORDER BY date_creation DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Estimations</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; }
        .container { padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h2>Demandes d’estimation</h2>
        <table>
            <tr>
                <th>ID</th><th>Nom</th><th>Téléphone</th><th>Ville</th><th>Type</th><th>Prix</th><th>Date</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nom'] ?></td>
                <td><?= $row['telephone'] ?></td>
                <td><?= $row['ville'] ?></td>
                <td><?= $row['type_bien'] ?></td>
                <td><?= $row['prix'] ?> DT</td>
                <td><?= $row['date_creation'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
