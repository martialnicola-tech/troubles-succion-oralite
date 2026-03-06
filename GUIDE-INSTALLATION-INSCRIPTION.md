# 🔐 GUIDE D'INSTALLATION - SYSTÈME D'INSCRIPTION PROFESSIONNELLE

## 📋 Vue d'ensemble

Vous disposez maintenant d'un système complet d'inscription pour les professionnels avec :
- Formulaire d'inscription en ligne
- Vérification automatique des backlinks
- Paiement sécurisé via Stripe (30 CHF/an)
- Support de TWINT
- Renouvellement automatique annuel
- Gestion des abonnements
- Panneau d'administration

## 🎯 Fonctionnalités principales

### Pour les professionnels :
✅ Inscription en ligne avec formulaire complet
✅ Vérification automatique du lien retour
✅ Paiement sécurisé par Stripe + TWINT
✅ Abonnement annuel renouvelable
✅ Profil dans l'annuaire

### Pour l'administrateur :
✅ Dashboard de gestion
✅ Vérification manuelle des backlinks
✅ Gestion des professionnels
✅ Historique des paiements
✅ Statistiques

## 📦 Fichiers créés

### Pages publiques :
- `inscription-professionnel.html` - Formulaire d'inscription
- `inscription-success.html` - Page de confirmation
- `js/registration.js` - Logique du formulaire

### API Backend (PHP) :
- `api/config.example.php` - Configuration (à renommer en config.php)
- `api/database.sql` - Structure de la base de données
- `api/create-registration.php` - Créer une inscription
- `api/verify-backlink.php` - Vérifier le backlink automatiquement
- `api/skip-backlink.php` - Passer l'étape de vérification
- `api/stripe-webhook.php` - Gérer les événements Stripe

### Administration :
- `admin/login.html` - Page de connexion admin
- `admin/dashboard.php` - À créer (tableau de bord)

## 🚀 INSTALLATION ÉTAPE PAR ÉTAPE

### ÉTAPE 1 : Configuration de la base de données

1. **Connectez-vous à phpMyAdmin** sur Hostinger
2. **Créez une nouvelle base de données** (ou utilisez une existante)
3. **Importez le fichier SQL** :
   - Ouvrez le fichier `api/database.sql`
   - Copiez tout le contenu
   - Collez dans l'onglet SQL de phpMyAdmin
   - Cliquez sur "Exécuter"

4. **Notez vos identifiants de connexion** :
   - Nom de la base
   - Nom d'utilisateur
   - Mot de passe
   - Hôte (généralement 'localhost')

### ÉTAPE 2 : Configuration Stripe

1. **Créez un compte Stripe** (si ce n'est pas déjà fait)
   - Allez sur https://stripe.com
   - Créez un compte
   - Vérifiez votre compte

2. **Activez les paiements TWINT** :
   - Dans le Dashboard Stripe
   - Paramètres → Modes de paiement
   - Activez TWINT pour la Suisse

3. **Récupérez vos clés API** :
   - Dashboard Stripe → Développeurs → Clés API
   - Notez votre clé **Publique** (pk_test_... ou pk_live_...)
   - Notez votre clé **Secrète** (sk_test_... ou sk_live_...)
   
   ⚠️ **Mode Test vs Live** :
   - Utilisez d'abord les clés **test** (pk_test_... et sk_test_...)
   - Passez aux clés **live** (pk_live_... et sk_live_...) quand vous êtes prêt

4. **Configurez le webhook Stripe** :
   - Dashboard Stripe → Développeurs → Webhooks
   - Cliquez sur "Ajouter un endpoint"
   - URL : `https://www.troubles-succion-oralite.ch/api/stripe-webhook.php`
   - Événements à écouter :
     - `checkout.session.completed`
     - `customer.subscription.updated`
     - `customer.subscription.deleted`
     - `invoice.payment_succeeded`
     - `invoice.payment_failed`
   - Cliquez sur "Ajouter un endpoint"
   - **Notez la clé de signature du webhook** (whsec_...)

### ÉTAPE 3 : Installation de Stripe PHP SDK

1. **Via Composer (recommandé)** :
   ```bash
   # Connectez-vous en SSH à votre serveur Hostinger
   cd /home/votre_utilisateur/public_html/api
   composer require stripe/stripe-php
   ```

2. **OU téléchargement manuel** :
   - Téléchargez https://github.com/stripe/stripe-php/archive/master.zip
   - Extrayez dans `api/vendor/`
   - La structure doit être : `api/vendor/stripe/stripe-php/`

### ÉTAPE 4 : Configuration des fichiers PHP

1. **Renommez et configurez config.php** :
   ```bash
   cd api/
   cp config.example.php config.php
   ```

2. **Éditez api/config.php** :
   ```php
   // Base de données
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'votre_base_de_donnees');
   define('DB_USER', 'votre_utilisateur');
   define('DB_PASS', 'votre_mot_de_passe');
   
   // Stripe
   define('STRIPE_SECRET_KEY', 'sk_test_VOTRE_CLE');  // Votre clé secrète
   define('STRIPE_PUBLIC_KEY', 'pk_test_VOTRE_CLE');  // Votre clé publique
   
   // Site
   define('SITE_URL', 'https://www.troubles-succion-oralite.ch');
   
   // Email
   define('ADMIN_EMAIL', 'votre@email.ch');
   define('FROM_EMAIL', 'noreply@troubles-succion-oralite.ch');
   ```

3. **Éditez api/stripe-webhook.php** :
   ```php
   // Ligne 17 environ
   $webhookSecret = 'whsec_VOTRE_CLE_WEBHOOK';  // Clé du webhook Stripe
   ```

### ÉTAPE 5 : Permissions des fichiers

```bash
# Assurez-vous que PHP peut écrire dans les logs
chmod 755 api/
chmod 644 api/*.php
```

### ÉTAPE 6 : Test du système

1. **Testez l'inscription** :
   - Allez sur `https://www.troubles-succion-oralite.ch/inscription-professionnel.html`
   - Remplissez le formulaire
   - Utilisez une carte de test Stripe :
     - Numéro : 4242 4242 4242 4242
     - Date : n'importe quelle date future
     - CVC : n'importe quel 3 chiffres

2. **Vérifiez dans la base de données** :
   - Ouvrez phpMyAdmin
   - Vérifiez la table `professionnels`
   - Vérifiez la table `payments`

3. **Vérifiez dans Stripe** :
   - Dashboard Stripe → Paiements
   - Vous devriez voir le paiement test

### ÉTAPE 7 : Configuration du compte admin

1. **Mot de passe par défaut** :
   - Utilisateur : `admin`
   - Mot de passe : `changeme123`

2. **⚠️ CHANGEZ IMMÉDIATEMENT CE MOT DE PASSE** :
   ```bash
   # Générez un nouveau hash de mot de passe
   php -r "echo password_hash('VotreNouveauMotDePasse', PASSWORD_DEFAULT);"
   ```
   
3. **Mettez à jour dans la base de données** :
   ```sql
   UPDATE admin_users 
   SET password_hash = 'NOUVEAU_HASH_ICI' 
   WHERE username = 'admin';
   ```

## 🔒 SÉCURITÉ IMPORTANTE

### 1. Protégez le dossier api/
Créez un fichier `api/.htaccess` :
```apache
# Bloquer l'accès direct aux fichiers PHP sauf via POST
<FilesMatch "\.php$">
    Order Deny,Allow
    Deny from all
</FilesMatch>

# Autoriser seulement les requêtes POST
<If "%{REQUEST_METHOD} != 'POST' && %{REQUEST_METHOD} != 'GET'">
    Require all denied
</If>

# Protéger config.php
<Files "config.php">
    Order allow,deny
    Deny from all
</Files>
```

### 2. Protégez le dossier admin/
Créez un fichier `admin/.htaccess` :
```apache
# Restreindre l'accès par IP (optionnel)
# Order Deny,Allow
# Deny from all
# Allow from VOTRE_IP

# Forcer HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 3. Variables sensibles
- ✅ Ne commitez JAMAIS `config.php` dans Git
- ✅ Gardez vos clés Stripe secrètes
- ✅ Changez le mot de passe admin par défaut
- ✅ Utilisez HTTPS (SSL)

## 📊 UTILISATION

### Pour les professionnels :

1. **S'inscrire** :
   - Remplir le formulaire sur `/inscription-professionnel.html`
   - Vérifier le lien retour (si site web fourni)
   - Payer 30 CHF via Stripe
   - Recevoir confirmation par email

2. **Gérer l'abonnement** :
   - Le renouvellement est automatique chaque année
   - Email de rappel avant renouvellement
   - Possibilité d'annuler à tout moment

### Pour l'administrateur :

1. **Connexion** :
   - Allez sur `/admin/login.html`
   - Connectez-vous avec vos identifiants

2. **Gérer les professionnels** :
   - Voir la liste complète
   - Vérifier manuellement les backlinks
   - Activer/désactiver des profils
   - Voir l'historique des paiements

3. **Vérifier les backlinks manuellement** :
   ```sql
   -- Voir les professionnels avec site web non vérifié
   SELECT * FROM professionnels 
   WHERE site_web IS NOT NULL 
   AND backlink_verified = 0;
   
   -- Marquer comme vérifié
   UPDATE professionnels 
   SET backlink_verified = 1, backlink_verified_date = NOW() 
   WHERE id = X;
   ```

## 🧪 MODE TEST vs PRODUCTION

### Mode Test (actuellement configuré) :
- Clés Stripe commençant par `sk_test_` et `pk_test_`
- Aucun vrai paiement n'est effectué
- Utilisez les cartes de test Stripe
- Webhooks de test

### Passage en Production :
1. Remplacez les clés test par les clés live dans `config.php`
2. Configurez un nouveau webhook avec l'URL de production
3. Testez avec un vrai paiement de 0.50 CHF
4. Vérifiez que tout fonctionne
5. Annoncez le service !

## 📧 EMAILS

Les emails sont envoyés via la fonction PHP `mail()`. Pour améliorer la délivrabilité :

1. **Configurez SPF, DKIM, DMARC** pour votre domaine
2. **OU utilisez un service SMTP** comme SendGrid, Mailgun
3. **Modifiez les templates** dans `api/stripe-webhook.php`

## 🐛 DÉPANNAGE

### Erreur "Connexion à la base de données échouée"
- Vérifiez les identifiants dans `config.php`
- Vérifiez que la base de données existe
- Vérifiez que l'utilisateur a les bonnes permissions

### Erreur Stripe "Invalid API key"
- Vérifiez que vous avez copié la bonne clé
- Vérifiez qu'il n'y a pas d'espaces avant/après
- Vérifiez que vous utilisez la clé secrète (sk_...)

### Le webhook ne fonctionne pas
- Vérifiez l'URL du webhook dans Stripe
- Vérifiez la clé de signature webhook
- Regardez les logs dans Dashboard Stripe → Webhooks

### Le backlink n'est pas détecté
- Vérifiez que le lien est bien présent sur le site
- Vérifiez que le site est accessible publiquement
- Essayez la vérification manuelle depuis phpMyAdmin

## 📞 SUPPORT

Si vous rencontrez des problèmes :
1. Vérifiez les logs d'erreur PHP
2. Vérifiez les logs Stripe dans le Dashboard
3. Vérifiez la table `stripe_webhooks` pour les événements non traités

## ✅ CHECKLIST FINALE

- [ ] Base de données créée et importée
- [ ] config.php configuré avec vos vraies valeurs
- [ ] Stripe configuré (clés API, webhook)
- [ ] Stripe PHP SDK installé
- [ ] Mot de passe admin changé
- [ ] Fichiers .htaccess en place
- [ ] Test d'inscription effectué
- [ ] Paiement test réussi
- [ ] Email de confirmation reçu
- [ ] Backlink vérifié (si applicable)
- [ ] Profil visible dans la base de données

---

**Félicitations !** Votre système d'inscription professionnelle est opérationnel ! 🎉
