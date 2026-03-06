# 💳 Système d'Inscription Professionnelle

## 🎯 Fonctionnalités

### Inscription en ligne
- Formulaire complet avec validation
- Champs requis : spécialité, nom, prénom, adresse, email
- Champ optionnel : site web, téléphone
- Validation en temps réel

### Vérification des backlinks
- **Automatique** : vérification par script PHP
- Recherche du lien vers www.troubles-succion-oralite.ch
- Possibilité de passer l'étape (site web non affiché)
- Vérification manuelle possible par l'admin

### Paiement Stripe
- **30 CHF par an** - renouvelable automatiquement
- Support des cartes bancaires
- **Support de TWINT** (méthode de paiement suisse)
- Paiement sécurisé (certificat SSL requis)
- Mode test et production

### Gestion des abonnements
- Renouvellement automatique annuel
- Webhooks Stripe pour synchronisation
- Historique des paiements
- Annulation possible à tout moment

## 📁 Structure des fichiers

```
nouveau-site/
├── inscription-professionnel.html    # Formulaire public
├── inscription-success.html          # Page de confirmation
├── js/
│   └── registration.js              # Logique formulaire
├── api/
│   ├── config.example.php           # Configuration (à renommer)
│   ├── database.sql                 # Structure BDD
│   ├── create-registration.php      # Créer inscription
│   ├── verify-backlink.php          # Vérifier backlink
│   ├── skip-backlink.php            # Passer vérification
│   ├── stripe-webhook.php           # Gérer événements Stripe
│   └── vendor/                      # Dépendances Composer
├── admin/
│   ├── login.html                   # Connexion admin
│   └── dashboard.php                # Tableau de bord (à créer)
└── composer.json                    # Dépendances PHP
```

## 🔄 Flux d'inscription

```
1. Professionnel remplit le formulaire
   ↓
2. Données sauvegardées dans registration_sessions (temporaire)
   ↓
3. Si site web fourni → Vérification du backlink
   ↓
4. Redirection vers Stripe Checkout
   ↓
5. Paiement 30 CHF (carte ou TWINT)
   ↓
6. Webhook Stripe → Activation du compte
   ↓
7. Création du professionnel dans la BDD
   ↓
8. Email de confirmation
   ↓
9. Profil visible dans l'annuaire
```

## 💾 Base de données

### Tables principales

**professionnels**
- Informations du professionnel
- Coordonnées complètes
- Statut de l'abonnement
- IDs Stripe (customer, subscription)
- Dates de début et fin d'abonnement

**registration_sessions**
- Sessions temporaires d'inscription
- Lien avec Stripe Checkout
- Expire après 1 heure

**backlink_checks**
- Historique des vérifications de backlinks
- Résultats (trouvé/non trouvé)
- Détails techniques

**payments**
- Historique de tous les paiements
- Montants, devises, statuts
- Lien avec Stripe

**stripe_webhooks**
- Log de tous les événements Stripe
- Traçabilité complète
- Debug facilité

**admin_users**
- Comptes administrateurs
- Mots de passe hashés
- Dernière connexion

## 🔐 Sécurité

### Implémentée
✅ Validation côté client et serveur
✅ Protection CSRF via tokens
✅ Mots de passe hashés (bcrypt)
✅ Requêtes préparées (PDO)
✅ Vérification signature webhooks Stripe
✅ HTTPS obligatoire (recommandé)
✅ Sessions sécurisées

### Recommandations
⚠️ Activez HTTPS (SSL/TLS)
⚠️ Limitez l'accès au dossier /admin/
⚠️ Sauvegardez régulièrement la BDD
⚠️ Surveillez les logs d'erreurs
⚠️ Mettez à jour Stripe SDK régulièrement

## 💰 Tarification

- **Inscription** : 30 CHF/an
- **Renouvellement** : Automatique chaque année
- **Annulation** : Possible à tout moment
- **Remboursement** : Selon votre politique

## 📊 Tableau de bord admin

### Fonctionnalités (à développer)
- [ ] Liste des professionnels actifs
- [ ] Statistiques mensuelles/annuelles
- [ ] Recherche et filtres
- [ ] Vérification manuelle des backlinks
- [ ] Export des données
- [ ] Gestion des abonnements
- [ ] Envoi d'emails groupés

## 🧪 Tests

### En mode TEST (clés sk_test_...)
- Utilisez les cartes de test Stripe
- Aucun vrai paiement effectué
- Webhooks de test disponibles

### Cartes de test Stripe

**Succès** :
- 4242 4242 4242 4242 (Visa)
- 5555 5555 5555 4444 (Mastercard)

**Échec** :
- 4000 0000 0000 0002 (carte déclinée)

**TWINT Test** :
- Disponible en mode test Stripe

### En mode PRODUCTION
- Utilisez les clés sk_live_...
- Vrais paiements effectués
- Configuration webhook production

## 🔧 Configuration Stripe

### Dashboard Stripe

1. **Modes de paiement** :
   - Activez "Cartes"
   - Activez "TWINT" (pour la Suisse)

2. **Webhooks** :
   - URL : `https://votre-domaine.ch/api/stripe-webhook.php`
   - Événements :
     - checkout.session.completed
     - customer.subscription.updated
     - customer.subscription.deleted
     - invoice.payment_succeeded
     - invoice.payment_failed

3. **Paramètres d'abonnement** :
   - Autoriser les annulations
   - Envoyer des emails de rappel
   - Configurer les tentatives de paiement

## 📧 Emails automatiques

### Actuellement envoyés
1. **Confirmation d'inscription** (après paiement)
2. **Rappel avant renouvellement** (optionnel)
3. **Confirmation de renouvellement** (optionnel)

### À configurer
- Templates HTML personnalisés
- Service SMTP (SendGrid, Mailgun...)
- Tracking des ouvertures

## 🐛 Debug

### Logs à vérifier

**PHP** :
```php
// Dans config.php, activez les erreurs en dev :
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

**Stripe** :
- Dashboard → Développeurs → Logs
- Dashboard → Webhooks → Événements

**Base de données** :
```sql
-- Voir les dernières inscriptions
SELECT * FROM registration_sessions 
ORDER BY created_at DESC LIMIT 10;

-- Voir les webhooks non traités
SELECT * FROM stripe_webhooks 
WHERE processed = 0;
```

## 📝 TODO / Améliorations futures

- [ ] Dashboard admin complet avec PhP
- [ ] Système de notifications email amélioré
- [ ] Export Excel des professionnels
- [ ] API REST pour afficher les professionnels
- [ ] Système de badges/certifications
- [ ] Statistiques de consultation des profils
- [ ] Espace personnel pour les professionnels
- [ ] Modification de profil en ligne
- [ ] Système de reviews/avis (optionnel)
- [ ] Multi-langues (FR/DE/IT)

## 💡 Notes importantes

### TWINT
TWINT est disponible pour les paiements en Suisse. Les utilisateurs avec l'app TWINT installée peuvent payer directement depuis leur mobile.

### Renouvellement automatique
Le système gère automatiquement :
- Les renouvellements annuels
- Les échecs de paiement
- Les annulations
- Les mises à jour de carte bancaire

### Conformité RGPD
- Les données personnelles sont stockées de manière sécurisée
- Les professionnels peuvent demander la suppression de leurs données
- Politique de confidentialité requise

## 🆘 Support

En cas de problème :
1. Consultez les logs PHP et Stripe
2. Vérifiez la configuration dans config.php
3. Testez avec une carte de test
4. Vérifiez que les webhooks fonctionnent
5. Consultez la documentation Stripe

---

**Système développé pour** : www.troubles-succion-oralite.ch  
**Version** : 1.0  
**Dernière mise à jour** : Février 2026
