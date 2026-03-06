<?php
/**
 * API pour créer une nouvelle inscription professionnelle
 * 
 * Cette API reçoit les données du formulaire, crée une session d'inscription
 * et génère un lien de paiement Stripe
 */

require_once 'config.php';
require_once 'vendor/autoload.php'; // Composer autoload pour Stripe

use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

// Vérifier que c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// Récupérer les données JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Données invalides']);
    exit;
}

// Valider les données requises
$required = ['specialite', 'prenom', 'nom', 'adresse', 'cp', 'lieu', 'email'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => "Le champ $field est requis"]);
        exit;
    }
}

// Valider l'email
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Adresse email invalide']);
    exit;
}

// Valider le code postal suisse (4 chiffres)
if (!preg_match('/^[0-9]{4}$/', $data['cp'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Code postal invalide']);
    exit;
}

// Valider l'URL du site web si fournie
if (!empty($data['siteWeb'])) {
    if (!filter_var($data['siteWeb'], FILTER_VALIDATE_URL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'URL du site web invalide']);
        exit;
    }
}

try {
    $pdo = getDBConnection();
    if (!$pdo) {
        throw new Exception("Erreur de connexion à la base de données");
    }
    
    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM professionnels WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Cette adresse email est déjà enregistrée']);
        exit;
    }
    
    // Générer un token de session unique
    $sessionToken = bin2hex(random_bytes(32));
    
    // Sauvegarder les données dans la table de sessions temporaires
    $stmt = $pdo->prepare("
        INSERT INTO registration_sessions 
        (session_token, email, form_data, expires_at) 
        VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))
    ");
    
    $stmt->execute([
        $sessionToken,
        $data['email'],
        json_encode($data)
    ]);
    
    $registrationId = $pdo->lastInsertId();
    
    // Initialiser Stripe
    Stripe::setApiKey(STRIPE_SECRET_KEY);
    
    // Créer une session de paiement Stripe
    $checkoutSession = StripeSession::create([
        'payment_method_types' => ['card', 'twint'],
        'mode' => 'subscription',
        'customer_email' => $data['email'],
        'line_items' => [[
            'price_data' => [
                'currency' => strtolower(CURRENCY),
                'unit_amount' => SUBSCRIPTION_PRICE * 100, // Montant en centimes
                'recurring' => [
                    'interval' => 'year',
                ],
                'product_data' => [
                    'name' => 'Inscription Annuaire Professionnel',
                    'description' => 'Abonnement annuel renouvelable - Annuaire troubles-succion-oralite.ch',
                ],
            ],
            'quantity' => 1,
        ]],
        'metadata' => [
            'registration_token' => $sessionToken,
            'registration_id' => $registrationId,
            'email' => $data['email'],
        ],
        'success_url' => SUCCESS_URL . '?session_id={CHECKOUT_SESSION_ID}&token=' . $sessionToken,
        'cancel_url' => CANCEL_URL . '?cancelled=1',
        'subscription_data' => [
            'metadata' => [
                'registration_token' => $sessionToken,
                'email' => $data['email'],
            ],
        ],
    ]);
    
    // Mettre à jour la session avec l'ID Stripe
    $stmt = $pdo->prepare("
        UPDATE registration_sessions 
        SET stripe_checkout_session_id = ? 
        WHERE id = ?
    ");
    $stmt->execute([$checkoutSession->id, $registrationId]);
    
    // Retourner la réponse
    echo json_encode([
        'success' => true,
        'registrationId' => $sessionToken,
        'checkoutUrl' => $checkoutSession->url,
        'requiresBacklinkCheck' => !empty($data['siteWeb'])
    ]);
    
} catch (Exception $e) {
    error_log("Erreur lors de la création de l'inscription : " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Une erreur est survenue. Veuillez réessayer.'
    ]);
}
?>
