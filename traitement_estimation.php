<?php
// traitement_estimation.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

session_start();

$host = getenv('DB_HOST') ?: "51.68.44.166";
$db   = getenv('DB_NAME') ?: "vote_db";
$user = getenv('DB_USER') ?: "root";
$pass = getenv('DB_PASS') ?: "Lavisione@2025";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erreur DB: " . $conn->connect_error);
}

// Ensure table exists
$conn->query("CREATE TABLE IF NOT EXISTS estimations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100),
  telephone VARCHAR(30),
  email VARCHAR(100),
  ville VARCHAR(100),
  type_bien VARCHAR(50),
  prix INT,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // reCAPTCHA validation (skipping for now as we don't have keys, but keeping logic placeholder)
    /*
    $secret = "VOTRE_CLE_SECRETE";
    $response = $_POST['g-recaptcha-response'];
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response");
    $captcha_success = json_decode($verify);
    if (!$captcha_success->success) {
        die("Captcha invalide");
    }
    */

    // Clean data
    $nom = htmlspecialchars($_POST['nom']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $email = htmlspecialchars($_POST['email']);
    $ville = htmlspecialchars($_POST['ville']);
    $type_bien = htmlspecialchars($_POST['type_bien']);
    $prix = intval($_POST['prix']);

    // Insertion
    $stmt = $conn->prepare("INSERT INTO estimations (nom, telephone, email, ville, type_bien, prix) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $nom, $telephone, $email, $ville, $type_bien, $prix);
    $stmt->execute();
    $stmt->close();

    // Email notification with PHPMailer
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'ssl0.ovh.net';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'contact@les-annonces.com.tn';
        $mail->Password   = 'mncontactla2024';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('contact@les-annonces.com.tn', 'Immobilier');
        $mail->addAddress('moatazghabri@gmail.com'); // Same as from for notification

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Nouvelle estimation - ' . $nom;
        $mail->Body    = "
            <h3>Nouvelle demande d'estimation immobilière</h3>
            <p><strong>Nom:</strong> $nom</p>
            <p><strong>Téléphone:</strong> $telephone</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Ville:</strong> $ville</p>
            <p><strong>Type de bien:</strong> $type_bien</p>
            <p><strong>Prix souhaité:</strong> $prix DT</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        // Log error but maybe don't stop the user
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    // Redirect or show message
    echo "<h1>Merci ! Votre demande a été enregistrée.</h1>";
    echo "<a href='index1.php'>Retour</a>";
} else {
    header("Location: index1.php");
}
?>
