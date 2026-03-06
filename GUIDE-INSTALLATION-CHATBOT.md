# 🤖 INSTALLATION CHATBOT SOPHIE - Guide Complet

## 🎉 FÉLICITATIONS !

Vous avez maintenant un chatbot IA complet connecté à Claude API !

**Coût réel** : ~3 CHF/mois pour 1000 conversations (vraiment pas cher !)

---

## 📋 CE QUI A ÉTÉ CRÉÉ

### Fichiers livrés

1. **api/chatbot.php** - Backend qui communique avec Claude API
2. **chatbot-widget.html** - Widget HTML/CSS/JS complet
3. **Ce guide** - Instructions d'installation

### Fonctionnalités

✅ **Réponses intelligentes** via Claude AI  
✅ **Ton empathique** et rassurant  
✅ **Détection automatique** du canton (si sur page canton)  
✅ **Orientation vers professionnels** locaux  
✅ **Historique de conversation** mémorisé  
✅ **Interface moderne** et responsive  
✅ **Animation de frappe** réaliste  

---

## 🚀 INSTALLATION EN 5 ÉTAPES

### ÉTAPE 1 : Obtenir une clé API Claude (5 min)

1. **Allez sur** : https://console.anthropic.com/
2. **Créez un compte** (gratuit)
3. **Obtenez votre clé API** :
   - Menu : API Keys
   - Create Key
   - Copiez la clé (commence par `sk-ant-...`)

**Tarification Claude API** :
- 3$ de crédit gratuit au départ
- Ensuite : ~0.003 CHF par conversation
- 1000 conversations/mois = ~3 CHF

### ÉTAPE 2 : Configurer le backend (2 min)

1. **Ouvrez** le fichier `api/chatbot.php`

2. **Ligne 20**, remplacez :
```php
define('CLAUDE_API_KEY', 'VOTRE_CLE_API_CLAUDE');
```

Par :
```php
define('CLAUDE_API_KEY', 'sk-ant-VOTRE_VRAIE_CLE_ICI');
```

3. **Uploadez** le fichier sur votre serveur Hostinger dans `/api/`

### ÉTAPE 3 : Intégrer le widget sur toutes les pages (3 min)

**Option A - Code à copier/coller** (Recommandé)

Dans **chaque page HTML**, juste avant `</body>`, ajoutez :

```html
<!-- Chatbot Sophie -->
<div id="sophie-chatbot">
    <button class="sophie-bubble" id="sophieBubble" aria-label="Ouvrir le chat">
        🤱
        <span class="sophie-badge" id="sophieBadge"></span>
    </button>
    
    <div class="sophie-window" id="sophieWindow">
        <div class="sophie-header">
            <div class="sophie-avatar">🤱</div>
            <div class="sophie-info">
                <h3>Sophie</h3>
                <p>Sage-femme virtuelle · En ligne</p>
            </div>
            <button class="sophie-close" id="sophieClose">×</button>
        </div>
        
        <div class="sophie-messages" id="sophieMessages"></div>
        
        <div class="sophie-input-container">
            <input type="text" class="sophie-input" id="sophieInput" placeholder="Posez votre question..." autocomplete="off">
            <button class="sophie-send" id="sophieSend">➤</button>
        </div>
        
        <div class="sophie-powered">
            Propulsé par Claude AI · Troubles-Succion-Oralite.ch
        </div>
    </div>
</div>

<link rel="stylesheet" href="/css/chatbot.css">
<script src="/js/chatbot.js"></script>
```

**Option B - Include PHP** (Si vous convertissez en PHP)

Créez un fichier `includes/chatbot.php` avec le code ci-dessus, puis dans chaque page :
```php
<?php include 'includes/chatbot.php'; ?>
```

### ÉTAPE 4 : Ajouter les fichiers CSS et JS

**4A. Créer** `/css/chatbot.css`

Copiez tout le CSS de `chatbot-widget.html` (entre les balises `<style>`) dans ce fichier.

**4B. Créer** `/js/chatbot.js`

Copiez tout le JavaScript de `chatbot-widget.html` (entre les balises `<script>`) dans ce fichier.

### ÉTAPE 5 : Tester ! (2 min)

1. **Allez sur** votre site
2. **Cliquez** sur la bulle Sophie en bas à droite
3. **Posez une question** : "Bonjour Sophie"
4. **Attendez** la réponse de Claude AI

**Si ça marche** → 🎉 Félicitations ! Votre chatbot est opérationnel !

**Si erreur** → Voir section Dépannage ci-dessous

---

## 🎨 PERSONNALISATION

### Changer les couleurs

Dans `css/chatbot.css`, modifiez :

```css
/* Couleur principale */
background: linear-gradient(135deg, #d4a574 0%, #b8875f 100%);

/* Remplacez par vos couleurs */
background: linear-gradient(135deg, #VOTRE_COULEUR_1 0%, #VOTRE_COULEUR_2 100%);
```

### Changer le message de bienvenue

Dans `api/chatbot.php`, ligne 183, modifiez le texte :

```php
$welcomeText = this.userLocation 
    ? `Votre message personnalisé pour ${this.userLocation}`
    : `Votre message par défaut`;
```

### Ajouter des informations spécifiques

Dans `api/chatbot.php`, le prompt système (fonction `getSystemPrompt`), vous pouvez :
- Ajouter des professionnels spécifiques
- Modifier le ton
- Ajouter des informations locales
- Personnaliser les recommandations

---

## 🔧 DÉPANNAGE

### Erreur : "Une erreur est survenue"

**Problème** : La clé API n'est pas correcte

**Solution** :
1. Vérifiez que la clé commence par `sk-ant-`
2. Pas d'espaces avant/après
3. Vérifiez dans la console Anthropic que la clé est active

### Le widget ne s'affiche pas

**Problème** : CSS ou JS non chargés

**Solution** :
1. Vérifiez les chemins : `/css/chatbot.css` et `/js/chatbot.js`
2. Ouvrez la console navigateur (F12) pour voir les erreurs
3. Vérifiez que les fichiers sont bien uploadés

### Le chatbot ne répond pas

**Problème** : Backend PHP ne fonctionne pas

**Solution** :
1. Vérifiez que `curl` est activé sur votre serveur
2. Testez directement l'URL : `votre-site.ch/api/chatbot.php`
3. Regardez les logs d'erreur PHP sur Hostinger

### Message : "Quota dépassé"

**Problème** : Crédit API épuisé

**Solution** :
1. Allez sur console.anthropic.com
2. Settings → Billing
3. Ajoutez du crédit (5-10 CHF suffisent pour des mois)

---

## 📊 SUIVI ET ANALYTICS

### Voir l'utilisation de l'API

1. **Console Anthropic** : https://console.anthropic.com/
2. **Menu** : Usage
3. **Voir** : Nombre de requêtes, coût, tokens utilisés

### Ajouter Google Analytics

Dans `js/chatbot.js`, ajoutez après chaque message :

```javascript
// Après l'envoi d'un message
gtag('event', 'chatbot_message_sent', {
    'event_category': 'Chatbot',
    'event_label': 'User Message'
});

// Après réception d'une réponse
gtag('event', 'chatbot_response_received', {
    'event_category': 'Chatbot',
    'event_label': 'Bot Response'
});
```

---

## 🎯 OPTIMISATIONS FUTURES

### 1. Sauvegarder les conversations

Ajoutez dans `api/chatbot.php` :

```php
// Après une conversation réussie
$stmt = $pdo->prepare("INSERT INTO chatbot_logs (user_message, bot_response, timestamp) VALUES (?, ?, NOW())");
$stmt->execute([$userMessage, $response['message']]);
```

**Avantages** :
- Analyser les questions fréquentes
- Améliorer Sophie
- Statistiques

### 2. Boutons de réponses rapides

Demandez à Claude de suggérer 2-3 boutons cliquables :

```php
// Dans le prompt système
"À la fin de ta réponse, si pertinent, suggère 2-3 actions rapides sous ce format :
[BOUTONS: Action 1 | Action 2 | Action 3]"
```

Puis parsez et affichez ces boutons dans le JS.

### 3. Multilingue (FR/DE/IT)

Détectez la langue du navigateur :

```javascript
const userLang = navigator.language.split('-')[0]; // 'fr', 'de', 'it'
```

Envoyez au backend et ajustez le prompt système.

### 4. Notification email à l'admin

Quand une question reste sans réponse satisfaisante :

```php
if (strpos($response['message'], 'ne peux pas') !== false) {
    mail(ADMIN_EMAIL, 'Question sans réponse chatbot', $userMessage);
}
```

---

## 💰 COÛTS RÉELS

### Estimation mensuelle

| Volume | Coût API | Coût total |
|--------|----------|------------|
| 100 conversations | 0.30 CHF | ~0.30 CHF |
| 500 conversations | 1.50 CHF | ~1.50 CHF |
| 1000 conversations | 3 CHF | ~3 CHF |
| 5000 conversations | 15 CHF | ~15 CHF |

**Pour démarrer** : 5 CHF de crédit = ~1500 conversations

---

## 📝 CHECKLIST D'INSTALLATION

- [ ] Compte Anthropic créé
- [ ] Clé API obtenue
- [ ] Fichier `api/chatbot.php` configuré avec la clé
- [ ] Fichier uploadé sur Hostinger
- [ ] Fichier `css/chatbot.css` créé
- [ ] Fichier `js/chatbot.js` créé
- [ ] Widget ajouté sur au moins 1 page
- [ ] Test réussi : message envoyé et réponse reçue
- [ ] Widget ajouté sur toutes les pages
- [ ] Personnalisation (couleurs, messages)
- [ ] Google Analytics ajouté (optionnel)

---

## 🎉 FÉLICITATIONS !

Votre chatbot Sophie est maintenant **opérationnel** !

**Ce que vous avez maintenant** :
- ✅ Assistant IA 24/7
- ✅ Réponses empathiques et intelligentes
- ✅ Orientation automatique vers professionnels
- ✅ Interface moderne et professionnelle
- ✅ Coût dérisoire (~3 CHF/mois)

**Différenciation** :
- 🚀 AUCUN concurrent en Suisse ne l'a
- 🚀 Wow effect garanti
- 🚀 Aide VRAIE aux parents
- 🚀 Données précieuses pour améliorer le service

---

## 🆘 BESOIN D'AIDE ?

**Console Anthropic** : https://console.anthropic.com/
**Documentation Claude** : https://docs.anthropic.com/
**Support Anthropic** : support@anthropic.com

**Questions courantes** :
- "Comment augmenter mon quota ?" → Ajoutez du crédit dans Billing
- "Puis-je changer de modèle ?" → Oui, dans chatbot.php ligne 21
- "Comment améliorer les réponses ?" → Modifiez le prompt système

---

## 📞 PROCHAINES ÉTAPES

1. **Testez** intensivement Sophie
2. **Demandez** à des amis de tester
3. **Récoltez** les questions fréquentes
4. **Améliorez** le prompt système
5. **Annoncez** sur votre site "Nouveau : Sophie, votre assistante IA !"

**Marketing** :
- Post sur réseaux sociaux
- Email aux professionnels inscrits
- Communiqué de presse (innovation IA en Suisse)

Vous avez maintenant un **avantage concurrentiel énorme** ! 🎊
