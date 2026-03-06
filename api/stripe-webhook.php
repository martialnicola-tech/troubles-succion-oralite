<?php
/**
 * Webhook Stripe pour gérer les événements de paiement
 * 
 * Ce script est appelé par Stripe lors d'événements importants
 * (paiement réussi, abonnement annulé, etc.)
 */

require_once 'config.php';
require_once 'vendor/autoload.php';

use Stripe\Stripe;
use Stripe\Webhook;

// Initialiser Stripe
Stripe::setApiKey(STRIPE_SECRET_KEY);

// Clé de signature du webhook (à configurer dans le dashboard Stripe)
// IMPORTANT: Remplacez par votre vraie clé de signature webhook
$webhookSecret = 'whsec_VOTRE_CLE_SECRETE_WEBHOOK';

// Récupérer le payload et la signature
$payload = @file_get_contents('php://input');
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

try {
    // Vérifier la signature du webhook
    $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
    
    $pdo = getDBConnection();
    if (!$pdo) {
        throw new Exception("Erreur de connexion à la base de données");
    }
    
    // Enregistrer l'événement
    $stmt = $pdo->prepare("
        INSERT INTO stripe_webhooks (event_id, event_type, payload, processed) 
        VALUES (?, ?, ?, 0)
        ON DUPLICATE KEY UPDATE event_id = event_id
    ");
    $stmt->execute([$event->id, $event->type, $payload]);
    
    // Traiter l'événement selon son type
    switch ($event->type) {
        case 'checkout.session.completed':
            handleCheckoutCompleted($event->data->object, $pdo);
            break;
            
        case 'customer.subscription.updated':
            handleSubscriptionUpdated($event->data->object, $pdo);
            break;
            
        case 'customer.subscription.deleted':
            handleSubscriptionDeleted($event->data->object, $pdo);
            break;
            
        case 'invoice.payment_succeeded':
            handlePaymentSucceeded($event->data->object, $pdo);
            break;
            
        case 'invoice.payment_failed':
            handlePaymentFailed($event->data->object, $pdo);
            break;
    }
    
    // Marquer l'événement comme traité
    $stmt = $pdo->prepare("UPDATE stripe_webhooks SET processed = 1 WHERE event_id = ?");
    $stmt->execute([$event->id]);
    
    http_response_code(200);
    echo json_encode(['status' => 'success']);
    
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    error_log("Erreur de vérification de signature webhook : " . $e->getMessage());
    http_response_code(400);
    echo json_encode(['error' => 'Invalid signature']);
    exit;
} catch (Exception $e) {
    error_log("Erreur webhook Stripe : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal error']);
}

/**
 * Traiter la complétion du paiement initial
 */
function handleCheckoutCompleted($session, $pdo) {
    $registrationToken = $session->metadata->registration_token ?? null;
    
    if (!$registrationToken) {
        error_log("Token d'inscription manquant dans les métadonnées du checkout");
        return;
    }
    
    // Récupérer la session d'inscription
    $stmt = $pdo->prepare("
        SELECT id, email, form_data 
        FROM registration_sessions 
        WHERE session_token = ?
    ");
    $stmt->execute([$registrationToken]);
    $regSession = $stmt->fetch();
    
    if (!$regSession) {
        error_log("Session d'inscription non trouvée pour le token: $registrationToken");
        return;
    }
    
    // Récupérer l'abonnement Stripe
    $subscriptionId = $session->subscription;
    $customerId = $session->customer;
    
    // Mettre à jour ou créer le professionnel
    $stmt = $pdo->prepare("SELECT id FROM professionnels WHERE email = ?");
    $stmt->execute([$regSession['email']]);
    $professionnel = $stmt->fetch();
    
    $formData = json_decode($regSession['form_data'], true);
    
    if ($professionnel) {
        // Mettre à jour le professionnel existant
        $stmt = $pdo->prepare("
            UPDATE professionnels 
            SET status = 'active',
                stripe_customer_id = ?,
                stripe_subscription_id = ?,
                subscription_start_date = NOW(),
                subscription_end_date = DATE_ADD(NOW(), INTERVAL 1 YEAR)
            WHERE id = ?
        ");
        $stmt->execute([$customerId, $subscriptionId, $professionnel['id']]);
        $professionnelId = $professionnel['id'];
    } else {
        // Créer le professionnel
        $stmt = $pdo->prepare("
            INSERT INTO professionnels 
            (specialite, prenom, nom, adresse, code_postal, lieu, email, telephone, 
             site_web, backlink_verified, status, stripe_customer_id, stripe_subscription_id,
             subscription_start_date, subscription_end_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 'active', ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR))
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
            $customerId,
            $subscriptionId
        ]);
        $professionnelId = $pdo->lastInsertId();
    }
    
    // Enregistrer le paiement
    $stmt = $pdo->prepare("
        INSERT INTO payments 
        (professionnel_id, stripe_payment_id, amount, currency, status, payment_method) 
        VALUES (?, ?, ?, ?, 'succeeded', 'stripe')
    ");
    $stmt->execute([
        $professionnelId,
        $session->payment_intent ?? $session->id,
        SUBSCRIPTION_PRICE,
        CURRENCY
    ]);
    
    // Marquer la session comme complétée
    $stmt = $pdo->prepare("UPDATE registration_sessions SET completed = 1 WHERE id = ?");
    $stmt->execute([$regSession['id']]);
    
    // Envoyer un email de confirmation
    sendConfirmationEmail($regSession['email'], $formData);
    
    error_log("Inscription complétée pour: " . $regSession['email']);
}

/**
 * Traiter la mise à jour d'un abonnement
 */
function handleSubscriptionUpdated($subscription, $pdo) {
    $stmt = $pdo->prepare("
        UPDATE professionnels 
        SET subscription_end_date = FROM_UNIXTIME(?)
        WHERE stripe_subscription_id = ?
    ");
    $stmt->execute([$subscription->current_period_end, $subscription->id]);
}

/**
 * Traiter l'annulation d'un abonnement
 */
function handleSubscriptionDeleted($subscription, $pdo) {
    $stmt = $pdo->prepare("
        UPDATE professionnels 
        SET status = 'cancelled'
        WHERE stripe_subscription_id = ?
    ");
    $stmt->execute([$subscription->id]);
    
    error_log("Abonnement annulé: " . $subscription->id);
}

/**
 * Traiter un paiement réussi (renouvellement)
 */
function handlePaymentSucceeded($invoice, $pdo) {
    if (!$invoice->subscription) {
        return;
    }
    
    // Récupérer le professionnel
    $stmt = $pdo->prepare("SELECT id, email FROM professionnels WHERE stripe_subscription_id = ?");
    $stmt->execute([$invoice->subscription]);
    $professionnel = $stmt->fetch();
    
    if ($professionnel) {
        // Enregistrer le paiement
        $stmt = $pdo->prepare("
            INSERT INTO payments 
            (professionnel_id, stripe_payment_id, amount, currency, status) 
            VALUES (?, ?, ?, ?, 'succeeded')
        ");
        $stmt->execute([
            $professionnel['id'],
            $invoice->payment_intent,
            $invoice->amount_paid / 100,
            strtoupper($invoice->currency)
        ]);
        
        // Prolonger l'abonnement
        $stmt = $pdo->prepare("
            UPDATE professionnels 
            SET subscription_end_date = FROM_UNIXTIME(?),
                status = 'active'
            WHERE id = ?
        ");
        $stmt->execute([$invoice->lines->data[0]->period->end, $professionnel['id']]);
        
        error_log("Renouvellement réussi pour: " . $professionnel['email']);
    }
}

/**
 * Traiter un échec de paiement
 */
function handlePaymentFailed($invoice, $pdo) {
    if (!$invoice->subscription) {
        return;
    }
    
    $stmt = $pdo->prepare("SELECT id, email FROM professionnels WHERE stripe_subscription_id = ?");
    $stmt->execute([$invoice->subscription]);
    $professionnel = $stmt->fetch();
    
    if ($professionnel) {
        error_log("Échec de paiement pour: " . $professionnel['email']);
        // Envoyer un email d'alerte
        // sendPaymentFailedEmail($professionnel['email']);
    }
}

/**
 * Envoyer un email de confirmation
 */
function sendConfirmationEmail($email, $formData) {
    $subject = "Bienvenue dans l'annuaire Troubles de la Succion";
    $message = "
    <html>
    <body>
        <h2>Bienvenue " . htmlspecialchars($formData['prenom']) . " !</h2>
        <p>Votre inscription à l'annuaire professionnel de troubles-succion-oralite.ch a été confirmée.</p>
        <p>Votre profil sera visible dans quelques instants dans notre annuaire.</p>
        <p>Merci de votre confiance !</p>
        <p>L'équipe Troubles de la Succion</p>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . FROM_EMAIL . "\r\n";
    
    @mail($email, $subject, $message, $headers);
}
?>
