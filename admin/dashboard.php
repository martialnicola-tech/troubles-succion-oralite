<?php
/**
 * Tableau de bord d'administration
 */

session_start();

// Vérification de l'authentification
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.html');
    exit;
}

require_once '../api/config.php';

$pdo = getDBConnection();
$message = '';
$messageType = '';

// --- Actions POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['action'])) {
    $action = $_POST['action'];
    $id     = intval($_POST['id'] ?? 0);

    if ($id > 0) {
        if ($action === 'activer') {
            $pdo->prepare("UPDATE professionnels SET statut = 'actif', abonnement_actif = 1 WHERE id = ?")
                ->execute([$id]);
            $message = 'Professionnel activé.';
            $messageType = 'success';
        } elseif ($action === 'desactiver') {
            $pdo->prepare("UPDATE professionnels SET statut = 'inactif', abonnement_actif = 0 WHERE id = ?")
                ->execute([$id]);
            $message = 'Professionnel désactivé.';
            $messageType = 'warning';
        } elseif ($action === 'supprimer') {
            $pdo->prepare("DELETE FROM professionnels WHERE id = ?")
                ->execute([$id]);
            $message = 'Professionnel supprimé.';
            $messageType = 'danger';
        }
    }
}

// --- Stats ---
$stats = [];
$stats['total']   = $pdo->query("SELECT COUNT(*) FROM professionnels")->fetchColumn();
$stats['actifs']  = $pdo->query("SELECT COUNT(*) FROM professionnels WHERE statut = 'actif'")->fetchColumn();
$stats['pending'] = $pdo->query("SELECT COUNT(*) FROM professionnels WHERE status = 'pending'")->fetchColumn();
$stats['backlinks_ok'] = $pdo->query("SELECT COUNT(*) FROM professionnels WHERE backlink_verified = 1")->fetchColumn();

// --- Filtres ---
$filtre_statut    = $_GET['statut'] ?? '';
$filtre_specialite = $_GET['specialite'] ?? '';
$filtre_search    = $_GET['search'] ?? '';

$where = ['1=1'];
$params = [];

if ($filtre_statut !== '') {
    $where[] = 'statut = :statut';
    $params[':statut'] = $filtre_statut;
}
if ($filtre_specialite !== '') {
    $where[] = 'specialite = :specialite';
    $params[':specialite'] = $filtre_specialite;
}
if ($filtre_search !== '') {
    $where[] = '(nom LIKE :search OR prenom LIKE :search OR email LIKE :search OR lieu LIKE :search)';
    $params[':search'] = '%' . $filtre_search . '%';
}

$sql = "SELECT * FROM professionnels WHERE " . implode(' AND ', $where) . " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$professionnels = $stmt->fetchAll();

// --- Spécialités disponibles pour le filtre ---
$specialites = $pdo->query("SELECT DISTINCT specialite FROM professionnels ORDER BY specialite")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tableau de bord</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { background: #f0f4f8; font-family: var(--font-primary, sans-serif); }

        .admin-header {
            background: var(--color-primary, #2a9d8f);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header h1 { font-size: 1.4rem; margin: 0; }
        .admin-header a { color: white; text-decoration: none; font-size: 0.9rem; }
        .admin-header a:hover { text-decoration: underline; }

        .admin-main { max-width: 1300px; margin: 2rem auto; padding: 0 1rem; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.2rem 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
            text-align: center;
        }
        .stat-card .number { font-size: 2.2rem; font-weight: 700; color: var(--color-primary, #2a9d8f); }
        .stat-card .label  { font-size: 0.85rem; color: #666; margin-top: 0.25rem; }

        .filters {
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
            margin-bottom: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: flex-end;
        }
        .filters label { font-size: 0.85rem; color: #555; display: flex; flex-direction: column; gap: 0.3rem; }
        .filters input, .filters select {
            padding: 0.5rem 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        .btn { padding: 0.5rem 1rem; border: none; border-radius: 6px; cursor: pointer; font-size: 0.9rem; font-weight: 500; }
        .btn-primary  { background: var(--color-primary, #2a9d8f); color: white; }
        .btn-success  { background: #2e7d32; color: white; }
        .btn-warning  { background: #e65100; color: white; }
        .btn-danger   { background: #c62828; color: white; }
        .btn-sm { padding: 0.3rem 0.7rem; font-size: 0.8rem; }

        .table-wrap { background: white; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.08); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th { background: #f5f5f5; padding: 0.8rem 1rem; text-align: left; font-weight: 600; color: #333; border-bottom: 2px solid #e0e0e0; }
        td { padding: 0.75rem 1rem; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
        tr:hover td { background: #fafafa; }
        tr:last-child td { border-bottom: none; }

        .badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }
        .badge-actif     { background: #e8f5e9; color: #2e7d32; }
        .badge-inactif   { background: #fce4ec; color: #c62828; }
        .badge-suspendu  { background: #fff3e0; color: #e65100; }
        .badge-pending   { background: #e3f2fd; color: #1565c0; }

        .backlink-ok  { color: #2e7d32; font-weight: 600; }
        .backlink-non { color: #999; }

        .actions { display: flex; gap: 0.4rem; flex-wrap: wrap; }

        .alert {
            padding: 0.9rem 1.2rem;
            border-radius: 8px;
            margin-bottom: 1.2rem;
            font-weight: 500;
        }
        .alert-success { background: #e8f5e9; color: #2e7d32; }
        .alert-warning { background: #fff3e0; color: #e65100; }
        .alert-danger  { background: #fce4ec; color: #c62828; }

        .count-result { color: #666; font-size: 0.9rem; margin-bottom: 0.75rem; }
    </style>
</head>
<body>

<header class="admin-header">
    <h1>Administration — Troubles de la Succion</h1>
    <div style="display:flex; gap:1.5rem; align-items:center;">
        <span>Connecté : <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong></span>
        <a href="logout.php">Se déconnecter</a>
        <a href="../index.html" target="_blank">← Voir le site</a>
    </div>
</header>

<main class="admin-main">

    <?php if ($message): ?>
        <div class="alert alert-<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="number"><?= $stats['total'] ?></div>
            <div class="label">Total inscrits</div>
        </div>
        <div class="stat-card">
            <div class="number"><?= $stats['actifs'] ?></div>
            <div class="label">Actifs</div>
        </div>
        <div class="stat-card">
            <div class="number"><?= $stats['pending'] ?></div>
            <div class="label">En attente de paiement</div>
        </div>
        <div class="stat-card">
            <div class="number"><?= $stats['backlinks_ok'] ?></div>
            <div class="label">Backlinks vérifiés</div>
        </div>
    </div>

    <!-- Filtres -->
    <form method="GET" class="filters">
        <label>
            Recherche
            <input type="text" name="search" value="<?= htmlspecialchars($filtre_search) ?>" placeholder="Nom, email, ville...">
        </label>
        <label>
            Statut
            <select name="statut">
                <option value="">Tous</option>
                <option value="actif"    <?= $filtre_statut === 'actif'    ? 'selected' : '' ?>>Actif</option>
                <option value="inactif"  <?= $filtre_statut === 'inactif'  ? 'selected' : '' ?>>Inactif</option>
                <option value="suspendu" <?= $filtre_statut === 'suspendu' ? 'selected' : '' ?>>Suspendu</option>
            </select>
        </label>
        <label>
            Spécialité
            <select name="specialite">
                <option value="">Toutes</option>
                <?php foreach ($specialites as $sp): ?>
                    <option value="<?= htmlspecialchars($sp) ?>" <?= $filtre_specialite === $sp ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sp) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit" class="btn btn-primary">Filtrer</button>
        <a href="dashboard.php" class="btn" style="background:#eee; color:#333;">Réinitialiser</a>
    </form>

    <!-- Table -->
    <p class="count-result"><?= count($professionnels) ?> résultat(s)</p>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Spécialité</th>
                    <th>Lieu</th>
                    <th>Email</th>
                    <th>Backlink</th>
                    <th>Statut</th>
                    <th>Inscrit le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($professionnels)): ?>
                <tr><td colspan="8" style="text-align:center; color:#999; padding:2rem;">Aucun professionnel trouvé.</td></tr>
            <?php else: ?>
                <?php foreach ($professionnels as $p): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($p['prenom'] . ' ' . $p['nom']) ?></strong></td>
                    <td><?= htmlspecialchars($p['specialite']) ?></td>
                    <td>
                        <?= htmlspecialchars($p['lieu'] ?? $p['ville'] ?? '') ?>
                        <?php if (!empty($p['canton'])): ?>
                            <br><small style="color:#999"><?= htmlspecialchars($p['canton']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="mailto:<?= htmlspecialchars($p['email']) ?>"><?= htmlspecialchars($p['email']) ?></a>
                        <?php if (!empty($p['site_web'])): ?>
                            <br><a href="<?= htmlspecialchars($p['site_web']) ?>" target="_blank" style="font-size:0.8rem; color:#666;">
                                <?= htmlspecialchars(parse_url($p['site_web'], PHP_URL_HOST) ?? $p['site_web']) ?>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($p['backlink_verified']): ?>
                            <span class="backlink-ok">✓ Vérifié</span>
                            <?php if ($p['backlink_verified_date']): ?>
                                <br><small style="color:#999"><?= date('d.m.Y', strtotime($p['backlink_verified_date'])) ?></small>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="backlink-non">✗ Non vérifié</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                        $statut = $p['statut'] ?? 'inactif';
                        $badgeClass = [
                            'actif'    => 'badge-actif',
                            'inactif'  => 'badge-inactif',
                            'suspendu' => 'badge-suspendu',
                        ][$statut] ?? 'badge-pending';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars(ucfirst($statut)) ?></span>
                    </td>
                    <td style="white-space:nowrap; font-size:0.85rem; color:#666;">
                        <?= date('d.m.Y', strtotime($p['created_at'])) ?>
                    </td>
                    <td>
                        <div class="actions">
                            <?php if ($statut !== 'actif'): ?>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <input type="hidden" name="action" value="activer">
                                <button class="btn btn-success btn-sm" type="submit">Activer</button>
                            </form>
                            <?php endif; ?>
                            <?php if ($statut === 'actif'): ?>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <input type="hidden" name="action" value="desactiver">
                                <button class="btn btn-warning btn-sm" type="submit">Désactiver</button>
                            </form>
                            <?php endif; ?>
                            <form method="POST" onsubmit="return confirm('Supprimer définitivement ce professionnel ?')">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <input type="hidden" name="action" value="supprimer">
                                <button class="btn btn-danger btn-sm" type="submit">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>
</body>
</html>
