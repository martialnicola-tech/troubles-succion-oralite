<?php
/**
 * API pour passer l'étape de vérification du backlink
 * 
 * Permet au professionnel de continuer sans vérification du backlink
 * (le site web ne sera pas affiché dans son profil)
 */

require_once 'config.php';
require_once 'vendor/autoload.php';

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

if (!$data || empty($data['registrationId'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Données invalides']);
    exit;
}

$sessionToken = $data['registrationId'];

try {
    $pdo = getDBConnection();
    if (!$pdo) {
        throw new Exception("Erreur de connexion à la base de données");
    }
    
    // Récupérer la session d'inscription
    $stmt = $pdo->prepare("
        SELECT id, email, form_data, stripe_checkout_session_id, expires_at 
        FROM registration_sessions 
        WHERE session_token = ? AND completed = 0
    ");
    $stmt->execute([$sessionToken]);
    $session = $stmt->fetch();
    
    if (!$session) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Session non trouvée']);
        exit;
    }
    
    // Vérifier si la session n'a pas expiré
    if (strtotime($session['expires_at']) < time()) {
        http_response_code(410);
        echo json_encode(['success' => false, 'message' => 'Session expirée']);
        exit;
    }
    
    $formData = json_decode($session['form_data'], true);
    
    // Vérifier si le professionnel existe déjà
    $stmt = $pdo->prepare("SELECT id FROM professionnels WHERE email = ?");
    $stmt->execute([$session['email']]);
    $professionnel = $stmt->fetch();
    
    if (!$professionnel) {
        // Créer le professionnel sans site web vérifié
        $stmt = $pdo->prepare("
            INSERT INTO professionnels 
            (specialite, prenom, nom, adresse, code_postal, lieu, email, telephone, site_web, status, backlink_verified) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULL, 'pending', 0)
        ");
        $stmt->execute([
            $formData['specialite'],
            $formData['prenom'],
            $formData['nom'],
            $formData['adresse'],
            $formData['cp'],
            $formData['lieu'],
            $formData['email'],
            $formData['telephone'] ?? null
        ]);
    } else {
        // Mettre à jour pour retirer le site web
        $stmt = $pdo->prepare("
            UPDATE professionnels 
            SET site_web = NULL, backlink_verified = 0 
            WHERE id = ?
        ");
        $stmt->execute([$professionnel['id']]);
    }
    
    // Récupérer ou créer la session de paiement Stripe
    Stripe::setApiKey(STRIPE_SECRET_KEY);
    
    if ($session['stripe_checkout_session_id']) {
        // Récupérer la session existante
        $checkoutSession = StripeSession::retrieve($session['stripe_checkout_session_id']);
        $checkoutUrl = $checkoutSession->url;
    } else {
        // Créer une nouvelle session de paiement
        $checkoutSession = StripeSession::create([
            'payment_method_types' => ['card', 'twint'],
            'mode' => 'subscription',
            'customer_email' => $session['email'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower(CURRENCY),
                    'unit_amount' => SUBSCRIPTION_PRICE * 100,
                    'recurring' => [
                        'interval' => 'year',
                    ],
                    'product_data' => [
                        'name' => 'Inscription Annuaire Professionnel',
                        'description' => 'Abonnement annuel renouvelable',
                    ],
                ],
                'quantity' => 1,
            ]],
            'metadata' => [
                'registration_token' => $sessionToken,
                'email' => $session['email'],
            ],
            'success_url' => SUCCESS_URL . '?session_id={CHECKOUT_SESSION_ID}&token=' . $sessionToken,
            'cancel_url' => CANCEL_URL . '?cancelled=1',
        ]);
        
        $checkoutUrl = $checkoutSession->url;
        
        // Mettre à jour la session avec le nouvel ID Stripe
        $stmt = $pdo->prepare("
            UPDATE registration_sessions 
            SET stripe_checkout_session_id = ? 
            WHERE id = ?
        ");
        $stmt->execute([$checkoutSession->id, $session['id']]);
    }
    
    echo json_encode([
        'success' => true,
        'checkoutUrl' => $checkoutUrl
    ]);
    
} catch (Exception $e) {
    error_log("Erreur lors du passage de la vérification du backlink : " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Une erreur est survenue'
    ]);
}
?>
