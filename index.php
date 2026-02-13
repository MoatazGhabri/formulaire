<?php
// ---------------- CONFIGURATION ----------------
$host = getenv('DB_HOST') ?: "51.68.44.166";
$dbname = getenv('DB_NAME') ?: "vote_db";
$user = getenv('DB_USER') ?: "root";
$pass = getenv('DB_PASS') ?: "Lavisione@2025";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    die("Erreur connexion DB: " . $e->getMessage());
}

// ---------------- CREATE TABLE (first run) ----------------
$pdo->exec("CREATE TABLE IF NOT EXISTS votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(50) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// ---------------- HANDLE AJAX REQUEST ----------------
if (isset($_GET['action']) && $_GET['action'] === 'vote') {
    $ip = $_SERVER['REMOTE_ADDR'];

    // Check if IP already voted
    $stmt = $pdo->prepare("SELECT id FROM votes WHERE ip = ?");
    $stmt->execute([$ip]);

    if ($stmt->fetch()) {
        echo json_encode(["status" => "already"]);
        exit;
    }

    // Insert vote
    $stmt = $pdo->prepare("INSERT INTO votes (ip) VALUES (?)");
    $stmt->execute([$ip]);

    echo json_encode(["status" => "ok"]);
    exit;
}

// ---------------- COUNT VOTES ----------------
$count = $pdo->query("SELECT COUNT(*) FROM votes")->fetchColumn();
?><!DOCTYPE html><html lang="fr">
<head>
<meta charset="UTF-8">
<title>Système de Vote</title>
<style>
body {
    font-family: Arial, sans-serif;
    text-align: center;
    margin-top: 80px;
}button { padding: 15px 30px; font-size: 18px; border: none; border-radius: 10px; background: #007bff; color: white; cursor: pointer; }

button:hover { background: #0056b3; }

#result { margin-top: 20px; font-size: 22px; }

.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #333;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    opacity: 0;
    transition: opacity 0.3s;
    z-index: 1000;
}
.toast.show {
    opacity: 1;
}
</style>

</head>
<body>
<?php include 'navbar.php'; ?>
<h1>👍 Votez pour nous</h1><button onclick="sendVote()">Voter</button>

<div id="result">Votes actuels : <b><?php echo $count; ?></b></div><script>
function sendVote() {
    fetch('?action=vote')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'already') {
                showToast('Vous avez déjà voté.');
            } else {
                showToast('Vote enregistré !');
                // Update count without reload
                const countEl = document.querySelector('#result b');
                const currentCount = parseInt(countEl.textContent);
                countEl.textContent = currentCount + 1;
            }
        });
}

function showToast(message) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.classList.add('show');
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}
</script>
<div id="toast" class="toast"></div></body>
</html>