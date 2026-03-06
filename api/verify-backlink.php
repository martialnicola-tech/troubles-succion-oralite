<?php
/**
 * API pour vérifier la présence du backlink sur le site du professionnel
 * 
 * Cette API vérifie si le site du professionnel contient un lien vers
 * www.troubles-succion-oralite.ch
 */

require_once 'config.php';

// Vérifier que c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// Récupérer les données JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || empty($data['registrationId']) || empty($data['siteWeb'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Données invalides']);
    exit;
}

$sessionToken = $data['registrationId'];
$siteWeb = $data['siteWeb'];

try {
    $pdo = getDBConnection();
    if (!$pdo) {
        throw new Exception("Erreur de connexion à la base de données");
    }
    
    // Récupérer la session d'inscription
    $stmt = $pdo->prepare("
        SELECT id, email, form_data, expires_at 
        FROM registration_sessions 
        WHERE session_token = ? AND completed = 0
    ");
    $stmt->execute([$sessionToken]);
    $session = $stmt->fetch();
    
    if (!$session) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Session non trouvée ou expirée']);
        exit;
    }
    
    // Vérifier si la session n'a pas expiré
    if (strtotime($session['expires_at']) < time()) {
        http_response_code(410);
        echo json_encode(['success' => false, 'message' => 'Session expirée']);
        exit;
    }
    
    // Fonction pour vérifier le backlink
    $backlinkFound = checkBacklink($siteWeb);
    
    // Enregistrer le résultat de la vérification
    $formData = json_decode($session['form_data'], true);
    
    // Si on n'a pas encore créé le professionnel, le créer temporairement
    $stmt = $pdo->prepare("SELECT id FROM professionnels WHERE email = ?");
    $stmt->execute([$session['email']]);
    $professionnel = $stmt->fetch();
    
    if (!$professionnel) {
        // Créer un professionnel temporaire (status = pending)
        $stmt = $pdo->prepare("
            INSERT INTO professionnels 
            (specialite, prenom, nom, adresse, code_postal, lieu, email, telephone, site_web, status, backlink_verified) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)
        ");
        $stmt->execute([
            $formData['specialite'],
            $formData['prenom'],
            $formData['nom'],
            $formData['adresse'],
            $formData['cp'],
            $formData['lieu'],
            $formData['email'],
            $formData['telephone'] ?? null,
            $formData['siteWeb'] ?? null,
            $backlinkFound ? 1 : 0
        ]);
        $professionnelId = $pdo->lastInsertId();
    } else {
        $professionnelId = $professionnel['id'];
        
        // Mettre à jour le statut du backlink
        if ($backlinkFound) {
            $stmt = $pdo->prepare("
                UPDATE professionnels 
                SET backlink_verified = 1, backlink_verified_date = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([$professionnelId]);
        }
    }
    
    // Enregistrer le check dans les logs
    $stmt = $pdo->prepare("
        INSERT INTO backlink_checks 
        (professionnel_id, site_web, found, details) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([
        $professionnelId,
        $siteWeb,
        $backlinkFound ? 1 : 0,
        json_encode(['timestamp' => date('Y-m-d H:i:s'), 'method' => 'automatic'])
    ]);
    
    if ($backlinkFound) {
        // Récupérer l'URL de checkout depuis la session
        $stmt = $pdo->prepare("SELECT stripe_checkout_session_id FROM registration_sessions WHERE id = ?");
        $stmt->execute([$session['id']]);
        $checkoutData = $stmt->fetch();
        
        // Générer l'URL de paiement
        require_once 'vendor/autoload.php';
        \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
        
        if ($checkoutData && $checkoutData['stripe_checkout_session_id']) {
            $checkoutSession = \Stripe\Checkout\Session::retrieve($checkoutData['stripe_checkout_session_id']);
            $checkoutUrl = $checkoutSession->url;
        } else {
            // Si pas de session existante, en créer une nouvelle
            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card', 'twint'],
                'mode' => 'subscription',
                'customer_email' => $session['email'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower(CURRENCY),
                        'unit_amount' => SUBSCRIPTION_PRICE * 100,
                        'recurring' => ['interval' => 'year'],
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
        }
        
        echo json_encode([
            'success' => true,
            'backlinkVerified' => true,
            'checkoutUrl' => $checkoutUrl
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'backlinkVerified' => false,
            'message' => 'Le lien vers troubles-succion-oralite.ch n\'a pas été trouvé sur votre site'
        ]);
    }
    
} catch (Exception $e) {
    error_log("Erreur lors de la vérification du backlink : " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Une erreur est survenue lors de la vérification'
    ]);
}

/**
 * Fonction pour vérifier la présence d'un backlink
 * 
 * @param string $url URL du site à vérifier
 * @return bool True si le backlink est trouvé, false sinon
 */
function checkBacklink($url) {
    try {
        // Nettoyer l'URL
        $url = filter_var($url, FILTER_SANITIZE_URL);
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        
        // Configuration du contexte pour la requête HTTP
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'user_agent' => 'Mozilla/5.0 (BacklinkChecker/1.0)',
                'follow_location' => 1,
                'max_redirects' => 3
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false
            ]
        ]);
        
        // Récupérer le contenu de la page
        $content = @file_get_contents($url, false, $context);
        
        if ($content === false) {
            return false;
        }
        
        // Convertir en minuscules pour la recherche
        $content = strtolower($content);
        
        // Rechercher différentes variantes du lien
        $patterns = [
            'troubles-succion-oralite.ch',
            'www.troubles-succion-oralite.ch',
            'https://troubles-succion-oralite.ch',
            'https://www.troubles-succion-oralite.ch',
            'http://troubles-succion-oralite.ch',
            'http://www.troubles-succion-oralite.ch'
        ];
        
        foreach ($patterns as $pattern) {
            if (strpos($content, $pattern) !== false) {
                return true;
            }
        }
        
        return false;
        
    } catch (Exception $e) {
        error_log("Erreur lors de la vérification du backlink pour $url : " . $e->getMessage());
        return false;
    }
}
?>
