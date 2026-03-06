# 🎨 INSTRUCTIONS FINALES - Site identique à troubles-succion-oralite.ch

## ✅ CE QUI EST FAIT

### Structure et Design
- ✅ CSS **100% identique** au site actuel
- ✅ Couleur turquoise (#4ECDC4) appliquée partout
- ✅ Header transparent sur hero
- ✅ Sections alternées blanc/turquoise
- ✅ Layout avec images à gauche/droite
- ✅ Grilles 4 colonnes pour les professionnels
- ✅ Footer turquoise
- ✅ Responsive design

### Pages créées
- ✅ 26 pages HTML complètes
- ✅ Navigation mise à jour
- ✅ Système d'inscription professionnelle
- ✅ API backend PHP
- ✅ Base de données MySQL

## 📸 ÉTAPE SUIVANTE : AJOUTER LES IMAGES

### Option 1 : Télécharger depuis le site actuel (RECOMMANDÉ)

1. **Allez sur** https://www.troubles-succion-oralite.ch
2. **Pour chaque page**, faites :
   - Clic droit sur l'image hero → "Enregistrer l'image sous"
   - Enregistrez dans `images/hero/[nom-page].jpg`
3. **Pour les images de contenu** :
   - Clic droit → "Enregistrer l'image sous"
   - Enregistrez dans `images/content/[description].jpg`

### Option 2 : Utiliser l'outil d'inspection

1. **Ouvrir le site** troubles-succion-oralite.ch
2. **F12** (outils développeur)
3. **Onglet "Network"** → Rafraîchir la page
4. **Filtrer par "Images"**
5. **Clic droit sur chaque image** → "Open in new tab"
6. **Télécharger** et placer dans les dossiers

### Option 3 : Utiliser un outil de téléchargement

Utilisez un outil comme **HTTrack** ou **wget** pour télécharger toutes les images automatiquement.

## 📁 Structure des images

```
images/
├── logo.png                    # Logo du site (header)
├── hero/                       # Images de fond des sections hero
│   ├── accueil.jpg
│   ├── troubles.jpg
│   ├── frein-langue.jpg
│   ├── frein-levre.jpg
│   ├── frenectomie.jpg
│   ├── exercices.jpg
│   ├── jouets.jpg
│   ├── equipe.jpg
│   ├── contact.jpg
│   ├── pour-qui.jpg
│   ├── bases-scientifiques.jpg
│   ├── on-coupe.jpg
│   └── [specialites].jpg      # Pour chaque page d'équipe
├── content/                    # Images dans le contenu
│   ├── bebe-pleure.jpg
│   ├── bebe-biberon.jpg
│   ├── enfant-saute.jpg
│   └── [autres].jpg
└── team/                       # Photos des catégories équipe
    ├── osteopathe.jpg
    ├── sage-femme.jpg
    ├── ibclc.jpg
    └── [autres].jpg
```

## 🚀 DÉPLOIEMENT

### 1. Ajouter les images

Suivez le guide `IMAGES-GUIDE.md` pour savoir exactement quelles images placer où.

### 2. Tester en local (optionnel)

```bash
# Avec Python
cd nouveau-site
python3 -m http.server 8000

# Ou avec PHP
php -S localhost:8000
```

Puis ouvrez http://localhost:8000

### 3. Déployer sur Hostinger

**Via File Manager :**
1. Connectez-vous à https://hpanel.hostinger.com
2. **Hosting** → **File Manager**
3. Uploadez tout le contenu dans `/public_html/`

**Via FTP (FileZilla) :**
1. Créez un compte FTP dans hPanel
2. Connectez-vous avec FileZilla
3. Uploadez tous les fichiers dans `/public_html/`

### 4. Configurer la base de données

1. **Créer la base** dans hPanel → MySQL Databases
2. **Importer** `api/database.sql` via phpMyAdmin
3. **Éditer** `api/config.php` avec vos identifiants
4. **Tester** le formulaire d'inscription

## 🎯 CHECKLIST FINALE

Avant de mettre en ligne :

- [ ] Toutes les images sont dans les bons dossiers
- [ ] Le logo est en place (`images/logo.png`)
- [ ] Base de données créée et importée
- [ ] `api/config.php` configuré avec vrais identifiants
- [ ] Clés Stripe ajoutées dans `api/config.php`
- [ ] Test du site en local réussi
- [ ] Upload sur Hostinger terminé
- [ ] Test du site en ligne OK
- [ ] Formulaire d'inscription fonctionne
- [ ] Paiement Stripe fonctionne

## 🆘 BESOIN D'AIDE ?

### Images manquantes ?
Consultez `IMAGES-GUIDE.md` pour la liste complète

### Problème de base de données ?
Voir `GUIDE-INSTALLATION-INSCRIPTION.md`

### Style pas identique ?
Le CSS est dans `css/style.css` - vérifiez que vos pages l'utilisent bien

### Questions ?
Contactez via le formulaire du site une fois en ligne !

## 🎉 C'EST PRÊT !

Une fois les images ajoutées, votre site sera **100% identique** au site actuel avec :
- ✅ Même design
- ✅ Mêmes couleurs
- ✅ Même structure
- ✅ PLUS : Système d'inscription professionnelle
- ✅ PLUS : SEO optimisé par canton
- ✅ PLUS : Backend moderne et sécurisé

**Bonne mise en ligne ! 🚀**
