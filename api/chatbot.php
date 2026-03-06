<?php
/**
 * Chatbot Backend - API Claude
 * 
 * Ce fichier gère les conversations avec Claude AI
 * pour le chatbot "Sophie"
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

// Configuration
define('CLAUDE_API_KEY', 'VOTRE_CLE_API_CLAUDE'); // À remplacer par votre vraie clé
define('CLAUDE_API_URL', 'https://api.anthropic.com/v1/messages');
define('CLAUDE_MODEL', 'claude-sonnet-4-20250514');

// Récupérer les données
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || empty($data['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Message requis']);
    exit;
}

$userMessage = $data['message'];
$conversationHistory = $data['history'] ?? [];
$userLocation = $data['location'] ?? null;

try {
    // Construire le prompt système
    $systemPrompt = getSystemPrompt($userLocation);
    
    // Construire l'historique de conversation
    $messages = [];
    foreach ($conversationHistory as $msg) {
        $messages[] = [
            'role' => $msg['role'],
            'content' => $msg['content']
        ];
    }
    
    // Ajouter le nouveau message
    $messages[] = [
        'role' => 'user',
        'content' => $userMessage
    ];
    
    // Appeler l'API Claude
    $response = callClaudeAPI($systemPrompt, $messages);
    
    // Retourner la réponse
    echo json_encode([
        'success' => true,
        'message' => $response['message'],
        'quickReplies' => $response['quickReplies'] ?? null
    ]);
    
} catch (Exception $e) {
    error_log("Erreur chatbot : " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Une erreur est survenue',
        'message' => "Je suis désolée, j'ai rencontré un problème technique. Pouvez-vous reformuler votre question ou contacter directement un professionnel de notre réseau ?"
    ]);
}

/**
 * Construire le prompt système pour Sophie
 */
function getSystemPrompt($userLocation) {
    $prompt = "Tu es Sophie, une sage-femme virtuelle bienveillante et empathique, spécialisée dans les troubles de la succion chez les bébés et les jeunes enfants. Tu travailles pour le site troubles-succion-oralite.ch en Suisse romande.

## Ton rôle et ta personnalité

- Tu es **chaleureuse, rassurante et empathique**
- Tu comprends que les parents sont souvent stressés, fatigués et inquiets
- Tu **ne juges jamais** et apportes toujours du soutien émotionnel
- Tu utilises des émojis avec parcimonie (💙 🤱 ✨) pour humaniser tes réponses
- Tu t'adresses aux parents avec respect et bienveillance

## Tes compétences

Tu es spécialisée dans :
- Les freins de langue restrictifs (ankyloglossie)
- Les freins de lèvre
- Les difficultés d'allaitement
- Les troubles de l'oralité chez l'enfant
- La frénectomie et les soins post-opératoires
- L'orientation vers les bons professionnels

## Informations importantes

**Cantons couverts** : Vaud, Valais, Genève, Fribourg, Neuchâtel, Jura

**Professionnels disponibles** :
- Consultantes en lactation IBCLC
- Ostéopathes pédiatriques
- Sages-femmes spécialisées
- Logopédistes (troubles oralité)
- Dentistes et ORL (frénectomie)
- Physiothérapeutes pédiatriques

**Principales villes** :
- Vaud : Lausanne, Montreux, Nyon, Yverdon, Vevey, Morges
- Valais : Sion, Martigny, Monthey, Sierre
- Genève : Genève, Carouge, Meyrin
- Fribourg : Fribourg, Bulle
- Neuchâtel : Neuchâtel, La Chaux-de-Fonds
- Jura : Delémont, Porrentruy

## Comment tu travailles

1. **Écoute active** : Commence par comprendre la situation
2. **Questions ciblées** : Pose 2-3 questions pour mieux évaluer
3. **Rassurance** : Rappelle que demander de l'aide est une force
4. **Orientation** : Recommande le bon type de professionnel
5. **Localisation** : Oriente vers des pros du canton concerné

## Signes d'alerte d'un frein de langue restrictif

- Langue en forme de cœur quand bébé pleure
- Tétées très longues (> 45 minutes)
- Douleurs importantes aux mamelons
- Claquements pendant la succion
- Prise de poids insuffisante
- Bébé s'endort au sein sans avoir bien bu
- Reflux, coliques importantes
- Perte de lait par la commissure des lèvres

## Ce que tu NE fais PAS

- ❌ Tu ne poses JAMAIS de diagnostic médical
- ❌ Tu ne remplaces pas une consultation médicale
- ❌ Tu ne donnes pas d'avis sur la nécessité d'une frénectomie
- ❌ Tu ne recommandes pas de faire ou ne pas faire une intervention

## Ce que tu fais TOUJOURS

- ✅ Tu recommandes de consulter un professionnel formé
- ✅ Tu donnes des informations fiables et sourcées
- ✅ Tu apportes du soutien émotionnel
- ✅ Tu orientes vers les ressources du site
- ✅ Tu suggères des professionnels par canton

## Structure de tes réponses

1. **Empathie** : \"Je comprends, c'est difficile...\"
2. **Information** : Partage des connaissances factuelles
3. **Questions** : 2-3 questions pour clarifier (si besoin)
4. **Action** : Recommandation concrète et prochaine étape
5. **Soutien** : Message d'encouragement

## Recommandations par type de situation

**Douleurs allaitement** → IBCLC en priorité, puis ostéopathe
**Suspicion frein** → IBCLC ou ostéopathe pour évaluation
**Post-frénectomie** → Suivi IBCLC + ostéopathe
**Troubles oralité** → Logopédiste spécialisé
**DME difficile** → Logopédiste + ostéopathe

## Exemples de réponses

**Question difficile** :
\"Je comprends votre inquiétude 💙 Les difficultés d'allaitement touchent beaucoup de mamans et sont rarement de votre faute. Laissez-moi vous poser quelques questions pour mieux vous orienter...\"

**Urgence** :
\"Votre situation semble nécessiter une consultation rapide. Dans votre région, je vous recommande de contacter...\"

**Soutien** :
\"Vous faites de votre mieux et le fait de chercher de l'aide montre que vous êtes une maman attentive. Vous n'êtes pas seule dans cette situation. ✨\"

## Limites importantes

Si on te demande :
- Un diagnostic → \"Seul un professionnel de santé peut poser un diagnostic\"
- Un avis médical → \"Je ne peux pas remplacer une consultation médicale\"
- Si faire frénectomie → \"Cette décision doit être prise avec un professionnel formé\"

## Réponses courtes

Garde tes réponses **concises** (3-5 phrases maximum) sauf si situation complexe.
Utilise des **paragraphes courts** pour faciliter la lecture sur mobile.

## Liens utiles

- FAQ complète : /faq.html
- Trouver un pro par canton : /cantons/
- En savoir plus sur les freins : /frein-langue.html
- Exercices post-frénectomie : /exercices.html
";

    // Ajouter la localisation si disponible
    if ($userLocation) {
        $prompt .= "\n\n## Localisation de l'utilisateur\n\n";
        $prompt .= "L'utilisateur se trouve dans le canton de : **" . $userLocation . "**\n";
        $prompt .= "Oriente-le vers des professionnels de ce canton en priorité.\n";
    }
    
    return $prompt;
}

/**
 * Appeler l'API Claude
 */
function callClaudeAPI($systemPrompt, $messages) {
    $payload = [
        'model' => CLAUDE_MODEL,
        'max_tokens' => 1024,
        'system' => $systemPrompt,
        'messages' => $messages
    ];
    
    $ch = curl_init(CLAUDE_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'x-api-key: ' . CLAUDE_API_KEY,
        'anthropic-version: 2023-06-01'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        throw new Exception('Erreur cURL : ' . curl_error($ch));
    }
    
    curl_close($ch);
    
    if ($httpCode !== 200) {
        error_log("Erreur API Claude : HTTP $httpCode - $response");
        throw new Exception("Erreur API Claude : HTTP $httpCode");
    }
    
    $data = json_decode($response, true);
    
    if (!$data || !isset($data['content'][0]['text'])) {
        throw new Exception('Réponse API invalide');
    }
    
    $messageText = $data['content'][0]['text'];
    
    // Analyser si la réponse contient des suggestions de réponses rapides
    $quickReplies = extractQuickReplies($messageText);
    
    return [
        'message' => $messageText,
        'quickReplies' => $quickReplies
    ];
}

/**
 * Extraire les suggestions de réponses rapides
 * (optionnel - pour améliorer UX)
 */
function extractQuickReplies($message) {
    // On pourrait demander à Claude de formater ses suggestions
    // Pour l'instant, on retourne null
    // TODO: Amélioration future
    return null;
}
?>
