<?php
/**
 * API — Demande de réinitialisation du mot de passe admin
 */

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$email = trim($input['email'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Adresse email invalide']);
    exit;
}

// Message générique pour ne pas révéler si l'email existe
$genericMessage = 'Si cet email est associé à un compte admin, un lien de réinitialisation a été envoyé.';

try {
    $pdo = getDBConnection();
    if (!$pdo) throw new Exception("Connexion DB impossible");

    // Créer les colonnes si elles n'existent pas encore
    $pdo->exec("ALTER TABLE admin_users
        ADD COLUMN IF NOT EXISTS reset_token VARCHAR(100) DEFAULT NULL,
        ADD COLUMN IF NOT EXISTS reset_token_expires DATETIME DEFAULT NULL
    ");

    $stmt = $pdo->prepare("SELECT id, username, email FROM admin_users WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin) {
        // Générer un token sécurisé (expire dans 1 heure)
        $token   = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $pdo->prepare("UPDATE admin_users SET reset_token = ?, reset_token_expires = ? WHERE id = ?")
            ->execute([$token, $expires, $admin['id']]);

        $resetLink = SITE_URL . '/admin/reset-password.html?token=' . $token;

        $subject = 'Réinitialisation de votre mot de passe admin';
        $body    = "Bonjour " . $admin['username'] . ",\n\n"
                 . "Vous avez demandé la réinitialisation de votre mot de passe.\n\n"
                 . "Cliquez sur ce lien (valable 1 heure) :\n"
                 . $resetLink . "\n\n"
                 . "Si vous n'avez pas fait cette demande, ignorez cet email.\n\n"
                 . "— Administration troubles-succion-oralite.ch";

        $headers = "From: " . FROM_EMAIL . "\r\n"
                 . "Reply-To: " . FROM_EMAIL . "\r\n"
                 . "Content-Type: text/plain; charset=UTF-8\r\n";

        mail($admin['email'], $subject, $body, $headers);
    }

    echo json_encode(['success' => true, 'message' => $genericMessage]);

} catch (Exception $e) {
    error_log("Erreur forgot-password admin : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Une erreur est survenue. Veuillez réessayer.']);
}
?>
