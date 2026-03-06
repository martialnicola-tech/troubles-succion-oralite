<?php
/**
 * Configuration de la base de données et de l'API Stripe
 * 
 * IMPORTANT : Renommez ce fichier en config.php et complétez les informations
 */

// Configuration de la base de données
define('DB_HOST', 'localhost');  // Généralement 'localhost' sur Hostinger
define('DB_NAME', 'votre_nom_de_base');  // À remplacer par le nom de votre base de données
define('DB_USER', 'votre_utilisateur');  // À remplacer par votre nom d'utilisateur
define('DB_PASS', 'votre_mot_de_passe');  // À remplacer par votre mot de passe

// Configuration Stripe
define('STRIPE_SECRET_KEY', 'sk_test_VOTRE_CLE_SECRETE');  // Clé secrète Stripe (mode test ou live)
define('STRIPE_PUBLIC_KEY', 'pk_test_VOTRE_CLE_PUBLIQUE');  // Clé publique Stripe

// Prix de l'abonnement
define('SUBSCRIPTION_PRICE', 30);  // 30 CHF
define('CURRENCY', 'CHF');

// URL de base du site
define('SITE_URL', 'https://www.troubles-succion-oralite.ch');

// URLs de retour après paiement
define('SUCCESS_URL', SITE_URL . '/inscription-success.html');
define('CANCEL_URL', SITE_URL . '/inscription-professionnel.html');

// Configuration email
define('ADMIN_EMAIL', 'admin@troubles-succion-oralite.ch');
define('FROM_EMAIL', 'noreply@troubles-succion-oralite.ch');

// Fuseau horaire
date_default_timezone_set('Europe/Zurich');

// Connexion à la base de données
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch(PDOException $e) {
        error_log("Erreur de connexion à la base de données : " . $e->getMessage());
        return false;
    }
}

// Headers CORS pour les requêtes AJAX
header('Access-Control-Allow-Origin: ' . SITE_URL);
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
?>
