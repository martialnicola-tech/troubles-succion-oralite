<?php
/**
 * API — Réinitialisation du mot de passe admin
 */

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$input    = json_decode(file_get_contents('php://input'), true);
$token    = trim($input['token']    ?? '');
$password = trim($input['password'] ?? '');

if (empty($token)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Token manquant']);
    exit;
}

if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 8 caractères']);
    exit;
}

try {
    $pdo = getDBConnection();
    if (!$pdo) throw new Exception("Connexion DB impossible");

    // Vérifier que le token existe et n'est pas expiré
    $stmt = $pdo->prepare("
        SELECT id FROM admin_users
        WHERE reset_token = ?
          AND reset_token_expires > NOW()
    ");
    $stmt->execute([$token]);
    $admin = $stmt->fetch();

    if (!$admin) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Lien invalide ou expiré. Veuillez refaire une demande.']);
        exit;
    }

    // Mettre à jour le mot de passe et invalider le token
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $pdo->prepare("
        UPDATE admin_users
        SET password_hash = ?, reset_token = NULL, reset_token_expires = NULL
        WHERE id = ?
    ")->execute([$hash, $admin['id']]);

    echo json_encode(['success' => true, 'message' => 'Mot de passe mis à jour avec succès']);

} catch (Exception $e) {
    error_log("Erreur reset-password admin : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Une erreur est survenue. Veuillez réessayer.']);
}
?>
