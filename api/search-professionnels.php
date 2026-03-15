<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

try {
    $pdo = getDBConnection();
    if (!$pdo) throw new Exception('Connexion échouée');

    // Paramètres de recherche
    $canton    = isset($_GET['canton'])    ? trim($_GET['canton'])    : '';
    $specialite = isset($_GET['specialite']) ? trim($_GET['specialite']) : '';
    $ville     = isset($_GET['ville'])     ? trim($_GET['ville'])     : '';

    // Construction de la requête
    $sql = "SELECT
                p.id,
                p.nom,
                p.prenom,
                p.specialite,
                p.telephone,
                p.telephone2,
                p.email,
                p.cabinet,
                p.adresse,
                p.npa,
                p.lieu,
                p.cabinet2,
                p.adresse2,
                p.cp2,
                p.lieu2,
                p.canton,
                p.site_web,
                p.description,
                p.status
            FROM professionnels p
            WHERE p.status IN ('active', 'gratuit', 'pending')";

    $params = [];

    // Filtre par canton
    if (!empty($canton)) {
        $sql .= " AND p.canton = :canton";
        $params[':canton'] = $canton;
    }

    // Filtre par spécialité (LIKE pour gérer les valeurs multiples ex: "sage-femme, consultante-lactation")
    if (!empty($specialite)) {
        $sql .= " AND p.specialite LIKE :specialite";
        $params[':specialite'] = "%" . $specialite . "%";
    }

    // Filtre par ville ou NPA
    if (!empty($ville)) {
        $sql .= " AND (p.lieu LIKE :ville OR p.npa LIKE :npa)";
        $params[':ville'] = "%$ville%";
        $params[':npa']   = "%$ville%";
    }

    // Tri : gratuit en premier, puis nom
    $sql .= " ORDER BY FIELD(p.status, 'gratuit', 'active', 'pending'), p.nom ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $professionnels = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Masquer uniquement les emails placeholder
    foreach ($professionnels as &$pro) {
        if (!empty($pro['email']) && strpos($pro['email'], 'noemail.') === 0) {
            $pro['email'] = null;
        }
    }
    unset($pro);

    echo json_encode([
        'success'        => true,
        'count'          => count($professionnels),
        'professionnels' => $professionnels,
        'filters'        => [
            'canton'    => $canton,
            'specialite' => $specialite,
            'ville'     => $ville
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Erreur de base de données',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
