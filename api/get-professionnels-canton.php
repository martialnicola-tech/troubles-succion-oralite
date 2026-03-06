<?php
/**
 * API pour récupérer les professionnels par canton
 * 
 * Cette API permet d'afficher dynamiquement les professionnels
 * sur les pages cantonales pour le SEO
 */

require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

// Vérifier le canton demandé
$canton = $_GET['canton'] ?? '';

// Mapping des cantons
$cantons_valides = [
    'vaud' => 'VD',
    'valais' => 'VS',
    'geneve' => 'GE',
    'fribourg' => 'FR',
    'neuchatel' => 'NE',
    'jura' => 'JU'
];

if (empty($canton) || !isset($cantons_valides[$canton])) {
    http_response_code(400);
    echo json_encode(['error' => 'Canton non valide']);
    exit;
}

try {
    $pdo = getDBConnection();
    if (!$pdo) {
        throw new Exception("Erreur de connexion à la base de données");
    }
    
    // Récupérer les professionnels actifs du canton
    // On extrait le canton du code postal (approximatif, à affiner)
    $stmt = $pdo->prepare("
        SELECT 
            id,
            specialite,
            prenom,
            nom,
            adresse,
            code_postal,
            lieu,
            email,
            telephone,
            site_web,
            backlink_verified
        FROM professionnels 
        WHERE status = 'active'
        AND code_postal REGEXP :pattern
        ORDER BY lieu ASC, nom ASC
    ");
    
    // Patterns approximatifs par canton (codes postaux suisses)
    $patterns = [
        'VD' => '^1[0-9]{3}$',      // 1000-1999 (approximatif)
        'VS' => '^(19[0-9]{2}|3[0-9]{3})$',  // 1900-1999 + 3000-3999
        'GE' => '^12[0-9]{2}$',     // 1200-1299
        'FR' => '^17[0-9]{2}$',     // 1700-1799
        'NE' => '^2[0-9]{3}$',      // 2000-2999
        'JU' => '^28[0-9]{2}$'      // 2800-2899
    ];
    
    $pattern = $patterns[$cantons_valides[$canton]] ?? '^[0-9]{4}$';
    $stmt->execute(['pattern' => $pattern]);
    
    $professionnels = $stmt->fetchAll();
    
    // Grouper par spécialité
    $par_specialite = [];
    foreach ($professionnels as $pro) {
        $spec = $pro['specialite'];
        if (!isset($par_specialite[$spec])) {
            $par_specialite[$spec] = [];
        }
        
        // Masquer l'email complet pour la vie privée
        $pro['email_display'] = substr($pro['email'], 0, 3) . '***@' . substr(strrchr($pro['email'], "@"), 1);
        unset($pro['email']);
        
        // N'afficher le site web que si vérifié
        if (!$pro['backlink_verified']) {
            $pro['site_web'] = null;
        }
        unset($pro['backlink_verified']);
        
        $par_specialite[$spec][] = $pro;
    }
    
    // Noms de spécialités en français
    $noms_specialites = [
        'osteopathe' => 'Ostéopathes',
        'sage-femme' => 'Sages-Femmes',
        'ibclc' => 'Consultantes en lactation IBCLC',
        'consultante-lactation' => 'Consultantes en lactation',
        'logopediste' => 'Logopédistes',
        'orl' => 'ORL',
        'dentiste' => 'Dentistes',
        'physiotherapeute' => 'Physiothérapeutes',
        'nutritionniste' => 'Nutritionnistes',
        'autre' => 'Autres spécialistes'
    ];
    
    // Formater la réponse
    $response = [
        'canton' => $canton,
        'total' => count($professionnels),
        'specialites' => []
    ];
    
    foreach ($par_specialite as $code_spec => $pros) {
        $response['specialites'][] = [
            'code' => $code_spec,
            'nom' => $noms_specialites[$code_spec] ?? ucfirst($code_spec),
            'count' => count($pros),
            'professionnels' => $pros
        ];
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    error_log("Erreur API professionnels par canton : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur']);
}
?>
