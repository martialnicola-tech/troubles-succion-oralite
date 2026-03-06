<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Paramètres de recherche
    $canton = isset($_GET['canton']) ? $_GET['canton'] : '';
    $specialite = isset($_GET['specialite']) ? $_GET['specialite'] : '';
    $ville = isset($_GET['ville']) ? $_GET['ville'] : '';
    
    // Construction de la requête
    $sql = "SELECT 
                p.id,
                p.nom,
                p.prenom,
                p.specialite,
                p.telephone,
                p.email,
                p.adresse,
                p.npa,
                p.ville,
                p.canton,
                p.site_web,
                p.backlink_verifie,
                p.statut,
                p.date_creation
            FROM professionnels p
            WHERE p.statut = 'actif'
            AND p.abonnement_actif = 1";
    
    $params = [];
    
    // Filtre par canton
    if (!empty($canton)) {
        $sql .= " AND p.canton = :canton";
        $params[':canton'] = $canton;
    }
    
    // Filtre par spécialité
    if (!empty($specialite)) {
        $sql .= " AND p.specialite = :specialite";
        $params[':specialite'] = $specialite;
    }
    
    // Filtre par ville ou NPA
    if (!empty($ville)) {
        $sql .= " AND (p.ville LIKE :ville OR p.npa LIKE :npa)";
        $params[':ville'] = "%$ville%";
        $params[':npa'] = "%$ville%";
    }
    
    // Tri par canton puis par nom
    $sql .= " ORDER BY p.canton ASC, p.nom ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $professionnels = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Masquer partiellement les emails pour la vie privée
    foreach ($professionnels as &$pro) {
        if (!empty($pro['email'])) {
            $email_parts = explode('@', $pro['email']);
            if (count($email_parts) == 2) {
                $local = $email_parts[0];
                if (strlen($local) > 3) {
                    $pro['email'] = substr($local, 0, 3) . '***@' . $email_parts[1];
                } else {
                    $pro['email'] = $local . '***@' . $email_parts[1];
                }
            }
        }
        
        // Ne pas afficher le site web si le backlink n'est pas vérifié
        if ($pro['backlink_verifie'] == 0) {
            $pro['site_web'] = null;
        }
    }
    
    echo json_encode([
        'success' => true,
        'count' => count($professionnels),
        'professionnels' => $professionnels,
        'filters' => [
            'canton' => $canton,
            'specialite' => $specialite,
            'ville' => $ville
        ]
    ], JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur de base de données',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
