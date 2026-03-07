<?php
/**
 * API de connexion admin
 */

session_start();

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (empty($data['username']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Identifiants manquants']);
    exit;
}

try {
    $pdo = getDBConnection();
    if (!$pdo) {
        throw new Exception("Erreur de connexion à la base de données");
    }

    $stmt = $pdo->prepare("SELECT id, username, password_hash, email FROM admin_users WHERE username = ?");
    $stmt->execute([trim($data['username'])]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($data['password'], $admin['password_hash'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Nom d\'utilisateur ou mot de passe incorrect']);
        exit;
    }

    // Mise à jour de la date de dernière connexion
    $pdo->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?")
        ->execute([$admin['id']]);

    // Création de la session
    $_SESSION['admin_id']       = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_email']    = $admin['email'];
    $_SESSION['admin_logged_in'] = true;

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    error_log("Erreur login admin : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Une erreur est survenue']);
}
?>
