# 📖 GUIDE COMPLET - SYSTÈME D'ANNUAIRE PROFESSIONNEL

## 🎯 VUE D'ENSEMBLE

Le site dispose maintenant d'un **système d'annuaire professionnel complet** avec :

✅ **Page "Pro"** - Présentation et inscription (pro.html)  
✅ **Page "Annuaire"** - Recherche de professionnels (annuaire.html)  
✅ **Formulaire d'inscription** - Déjà existant (inscription-professionnel.html)  
✅ **Paiement Stripe** - 30 CHF/an, renouvellement automatique  
✅ **Système de recherche** - Par canton, spécialité, ville/NPA  
✅ **API backend** - Pour la recherche et l'affichage  

---

## 📄 PAGES CRÉÉES

### 1. **pro.html** - Espace Professionnels
**URL** : https://troubles-succion-oralite.ch/pro.html

**Contenu** :
- Présentation des avantages (6 bénéfices)
- Prix : 30 CHF/an
- Liste des fonctionnalités incluses
- FAQ complète (7 questions)
- CTA vers l'inscription

**Menu** : Nouvel onglet "Pro" dans la navigation

---

### 2. **annuaire.html** - Annuaire des Professionnels
**URL** : https://troubles-succion-oralite.ch/annuaire.html

**Fonctionnalités** :
- 🔍 **Recherche multicritères** :
  - Par canton (VD, VS, GE, FR, NE, JU)
  - Par spécialité (9 professions)
  - Par ville ou NPA
  
- 📋 **Affichage des résultats** :
  - Carte détaillée par professionnel
  - Nom, spécialité, canton
  - Coordonnées (téléphone, email masqué, adresse)
  - Lien vers site web (si backlink vérifié)
  - Nombre de résultats trouvés

---

### 3. **inscription-professionnel.html** (déjà existant, amélioré)
**Fonctionnalités** :
- Formulaire complet
- Paiement Stripe 30 CHF/an
- Support TWINT
- Renouvellement automatique
- Vérification backlink automatique

---

## 🔧 API BACKEND

### **api/search-professionnels.php**

**Endpoint** : `GET /api/search-professionnels.php`

**Paramètres** :
```
?canton=VD             # Optionnel : VD, VS, GE, FR, NE, JU
&specialite=osteopathe # Optionnel : voir liste ci-dessous
&ville=Lausanne        # Optionnel : ville ou NPA
```

**Spécialités acceptées** :
- `osteopathe`
- `sage-femme`
- `ibclc`
- `consultante-lactation`
- `logopediste`
- `orl`
- `dentiste`
- `physiotherapeute`
- `nutritionniste`

**Réponse JSON** :
```json
{
  "success": true,
  "count": 15,
  "professionnels": [
    {
      "id": 1,
      "nom": "Dupont",
      "prenom": "Marie",
      "specialite": "osteopathe",
      "telephone": "079 123 45 67",
      "email": "mar***@example.com",
      "adresse": "Rue du Stand 11",
      "npa": "1880",
      "ville": "Bex",
      "canton": "VD",
      "site_web": "https://www.example.com",
      "backlink_verifie": 1
    }
  ],
  "filters": {
    "canton": "VD",
    "specialite": "osteopathe",
    "ville": ""
  }
}
```

**Sécurité** :
- ✅ Email masqué (ex: `mar***@domain.com`)
- ✅ Site web affiché UNIQUEMENT si backlink vérifié
- ✅ Seuls les professionnels avec `statut = 'actif'` et `abonnement_actif = 1`

---

## 💾 BASE DE DONNÉES

### Nouveaux champs ajoutés à `professionnels` :

```sql
-- Gestion abonnement
abonnement_actif TINYINT(1) DEFAULT 1
date_fin_abonnement DATE DEFAULT NULL

-- Index pour recherche rapide
INDEX idx_canton (canton)
INDEX idx_specialite (specialite)
INDEX idx_ville (ville)
INDEX idx_npa (npa)
INDEX idx_statut_actif (statut, abonnement_actif)
```

---

## 🔄 WORKFLOW COMPLET

### Pour un professionnel qui s'inscrit :

1. **Visite `pro.html`** → Découvre les avantages
2. **Clic "S'inscrire"** → Redirigé vers `inscription-professionnel.html`
3. **Remplit le formulaire** :
   - Informations personnelles
   - Coordonnées professionnelles
   - Canton, spécialité
   - Site web (optionnel)
4. **Paiement Stripe** : 30 CHF
   - Carte bancaire ou TWINT
   - Renouvellement automatique annuel
5. **Validation** :
   - Compte créé dans la base
   - `statut = 'actif'`
   - `abonnement_actif = 1`
   - Email de confirmation
6. **Backlink** (optionnel) :
   - Si site web renseigné
   - Vérification automatique dans les 24-48h
   - Si vérifié → site web affiché dans l'annuaire

---

### Pour un parent qui cherche un pro :

1. **Visite `annuaire.html`**
2. **Sélectionne critères** :
   - Canton : Vaud
   - Spécialité : Ostéopathe
   - Ville : Lausanne
3. **Clic "Rechercher"**
4. **Résultats affichés** :
   - Liste des professionnels correspondants
   - Coordonnées complètes
   - Lien vers site web (si disponible)
5. **Contacte directement** le professionnel

---

## 🎨 NAVIGATION MISE À JOUR

### Nouveau menu :

```
Pour qui ? | Troubles | Frein langue | On coupe ou pas ? | 
Frénectomie | Exercices | Annuaire | Pro | Bases Scientifiques | Contact
```

**2 nouveaux onglets** :
- 📋 **Annuaire** → Pour les parents (recherche)
- 👨‍⚕️ **Pro** → Pour les professionnels (inscription)

---

## 💰 SYSTÈME DE PAIEMENT

### Configuration Stripe

**Dans `api/config.php`** :

```php
// Clés Stripe
define('STRIPE_PUBLIC_KEY', 'pk_live_xxxxx');  // Clé publique
define('STRIPE_SECRET_KEY', 'sk_live_xxxxx');  // Clé secrète
define('STRIPE_PRICE_ID', 'price_xxxxx');      // ID du prix 30 CHF/an
```

### Créer le prix dans Stripe :

1. **Connexion** : https://dashboard.stripe.com
2. **Products** → Create product
   - **Name** : Abonnement Annuaire Pro
   - **Price** : 30 CHF
   - **Billing** : Recurring, yearly
   - **Tax** : Selon votre configuration
3. **Copier le Price ID** → Ajouter dans `config.php`

### Webhook Stripe (déjà configuré)

**URL** : `https://troubles-succion-oralite.ch/api/stripe-webhook.php`

**Events à écouter** :
- `checkout.session.completed` → Première inscription
- `invoice.payment_succeeded` → Renouvellement
- `customer.subscription.deleted` → Résiliation

---

## 🔐 SÉCURITÉ

### Protection des données

✅ **Emails masqués** dans l'affichage public  
✅ **Site web** affiché UNIQUEMENT si backlink vérifié  
✅ **Paiement** : Stripe PCI Level 1 certifié  
✅ **Base de données** : Requêtes préparées (PDO)  
✅ **HTTPS** : Obligatoire pour Stripe  

---

## 📊 STATISTIQUES & ADMINISTRATION

### Dashboard admin (à créer si besoin)

**Métriques utiles** :
- Nombre total de professionnels
- Professionnels par canton
- Professionnels par spécialité
- Taux de renouvellement
- Revenus mensuels/annuels

**Requêtes SQL utiles** :

```sql
-- Professionnels actifs par canton
SELECT canton, COUNT(*) as total 
FROM professionnels 
WHERE statut = 'actif' AND abonnement_actif = 1 
GROUP BY canton;

-- Professionnels par spécialité
SELECT specialite, COUNT(*) as total 
FROM professionnels 
WHERE statut = 'actif' AND abonnement_actif = 1 
GROUP BY specialite;

-- Abonnements expirant dans 30 jours
SELECT * FROM professionnels 
WHERE date_fin_abonnement BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY);
```

---

## 🚀 DÉPLOIEMENT

### Checklist complète :

- [ ] Uploader toutes les pages HTML
- [ ] Uploader l'API `search-professionnels.php`
- [ ] Exécuter le nouveau `database.sql`
- [ ] Configurer les clés Stripe dans `config.php`
- [ ] Créer le produit dans Stripe (30 CHF/an)
- [ ] Configurer le webhook Stripe
- [ ] Tester la recherche dans l'annuaire
- [ ] Tester une inscription complète
- [ ] Vérifier le paiement Stripe
- [ ] Vérifier l'affichage dans l'annuaire

---

## ✅ RÉSUMÉ

Vous disposez maintenant d'un **système d'annuaire professionnel complet** :

✅ Page de présentation pour les pros  
✅ Formulaire d'inscription avec paiement  
✅ Annuaire avec recherche multicritères  
✅ API backend performante  
✅ Système de renouvellement automatique  
✅ Protection des données personnelles  
✅ Backlink verification  

**Le tout pour 30 CHF/an par professionnel !** 🎉

---

## 📞 SUPPORT

Pour toute question :
- 📧 Email : info@troubles-succion-oralite.ch
- 📱 Via le formulaire de contact du site
