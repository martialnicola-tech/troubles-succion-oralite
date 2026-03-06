# 🔧 MIGRATION VERS CLAUDE CODE (Cowork)

## 📋 Vue d'ensemble

Claude Code est l'outil idéal pour gérer votre projet car il permet :
- ✅ Édition de fichiers directement
- ✅ Création de nouvelles pages rapidement
- ✅ Modifications multiples en une seule commande
- ✅ Tests en local avant déploiement
- ✅ Gestion de version (Git)

## 🚀 ÉTAPES DE MIGRATION

### 1. Installation de Claude Code

Si pas encore installé :

**macOS/Linux** :
```bash
curl -fsSL https://desktop.anthropic.com/install.sh | sh
```

**Windows** :
Télécharger depuis https://claude.ai/download

### 2. Extraire votre projet

1. **Téléchargez** `nouveau-site.zip` depuis cette conversation
2. **Extrayez** le zip dans un dossier de votre choix :
   ```
   ~/Documents/troubles-succion-oralite/
   ```

### 3. Ouvrir le projet dans Claude Code

**Option A - Ligne de commande** :
```bash
cd ~/Documents/troubles-succion-oralite
claude-code
```

**Option B - Interface** :
1. Lancez Claude Code
2. File → Open Folder
3. Sélectionnez le dossier `nouveau-site`

### 4. Contexte initial à donner à Claude Code

Quand Claude Code s'ouvre, donnez-lui ce contexte :

```
Je travaille sur le site troubles-succion-oralite.ch - un site professionnel 
pour les troubles de la succion et freins restrictifs en Suisse romande.

Le projet contient :
- Site HTML/CSS responsive
- Système d'inscription professionnelle avec paiement Stripe (30 CHF/an)
- Pages SEO par canton (Vaud, Valais, Genève, Fribourg, Neuchâtel, Jura)
- API PHP backend
- Base de données MySQL

Structure :
- Pages HTML dans la racine
- CSS dans /css/
- JavaScript dans /js/
- API PHP dans /api/
- Pages cantonales dans /cantons/
- Admin dans /admin/

Toute la documentation est dans les fichiers .md
```

## 💡 UTILISATIONS AVEC CLAUDE CODE

### Créer les 5 pages cantonales manquantes

**Commande Claude Code** :
```
Crée les 5 pages cantonales manquantes (Valais, Genève, Fribourg, 
Neuchâtel, Jura) en te basant sur le modèle cantons/vaud.html.

Pour chaque canton :
- Adapte le titre, description, keywords
- Change les villes principales
- Adapte le contenu SEO avec les bonnes villes
- Modifie le Schema.org avec le bon canton
```

Claude Code créera les 5 fichiers automatiquement !

### Personnaliser le design

**Commande** :
```
J'aimerais modifier les couleurs du site. Peux-tu me proposer 
3 palettes de couleurs chaudes et professionnelles, puis modifier 
css/style.css avec celle que je choisis ?
```

### Ajouter du contenu

**Commande** :
```
Crée la page "troubles.html" qui explique en détail les différents 
troubles de la succion : frein de langue, frein de lèvre, problèmes 
d'allaitement, troubles de l'oralité. Format long (2000 mots), 
optimisé SEO.
```

### Corriger des bugs

**Commande** :
```
Le formulaire d'inscription ne valide pas correctement les emails. 
Peux-tu vérifier js/registration.js et corriger le problème ?
```

### Générer du contenu SEO

**Commande** :
```
Crée un article de blog "5 signes que votre bébé a un frein de langue" 
optimisé SEO avec mots-clés, images alt text, et structure H2/H3.
```

## 🔄 WORKFLOW RECOMMANDÉ

### Développement en local

1. **Ouvrir le projet** dans Claude Code
2. **Faire les modifications** avec l'aide de Claude
3. **Tester en local** avec un serveur local :
   ```bash
   # Python
   python3 -m http.server 8000
   
   # PHP (pour tester l'API)
   php -S localhost:8000
   
   # OU Node.js
   npx serve
   ```
4. **Vérifier dans le navigateur** : http://localhost:8000

### Déploiement sur Hostinger

Une fois satisfait des modifications :

**Option A - FTP (via Claude Code)** :
```
Claude, peux-tu me générer les commandes pour uploader 
les fichiers modifiés vers Hostinger via FTP ?

Serveur : ftp.troubles-succion-oralite.ch
Port : 21
Dossier : /public_html/
```

**Option B - File Manager Hostinger** :
1. Zipper les fichiers modifiés
2. Uploader via Hostinger File Manager
3. Extraire

**Option C - Git (recommandé)** :
```bash
# Initialiser Git
git init
git add .
git commit -m "Initial commit"

# Push vers GitHub
git remote add origin https://github.com/votre-compte/troubles-succion.git
git push -u origin main

# Sur Hostinger, cloner le repo
git clone https://github.com/votre-compte/troubles-succion.git
```

## 📝 EXEMPLES DE TÂCHES AVEC CLAUDE CODE

### 1. Compléter toutes les pages

**Vous** :
```
J'ai besoin de créer toutes les pages manquantes listées dans 
GUIDE-PERSONNALISATION.md. Peux-tu les créer une par une en me 
demandant le contenu spécifique pour chacune ?
```

**Claude Code** créera les pages interactivement avec vous.

### 2. Optimiser le SEO

**Vous** :
```
Analyse le SEO de toutes mes pages HTML et propose des améliorations :
- Meta descriptions trop courtes/longues
- Titres non optimisés
- Images sans alt text
- Headers mal structurés
```

**Claude Code** vous donnera un rapport détaillé.

### 3. Ajouter des fonctionnalités

**Vous** :
```
J'aimerais ajouter un système de newsletter. Les visiteurs peuvent 
s'inscrire avec leur email, et je reçois une notification. Crée le 
formulaire HTML, le CSS, le JavaScript et l'API PHP nécessaire.
```

**Claude Code** créera tous les fichiers nécessaires.

### 4. Traduire en allemand

**Vous** :
```
Peux-tu créer une version allemande du site ? Crée un dossier /de/ 
avec toutes les pages traduites, en adaptant aussi le SEO pour la 
Suisse alémanique.
```

**Claude Code** gérera la traduction complète.

### 5. Créer le dashboard admin

**Vous** :
```
Crée un dashboard admin complet (admin/dashboard.php) pour :
- Voir la liste des professionnels
- Approuver/refuser les inscriptions
- Vérifier manuellement les backlinks
- Voir les statistiques
- Envoyer des emails groupés

Style cohérent avec le reste du site.
```

## 🎯 AVANTAGES DE CLAUDE CODE POUR CE PROJET

### Pour le développement

✅ **Édition rapide** : "Change la couleur primaire en bleu"
✅ **Création masse** : "Crée 10 articles de blog sur les troubles de succion"
✅ **Refactoring** : "Rends le code plus modulaire"
✅ **Debug** : "Le paiement Stripe ne fonctionne pas, aide-moi"

### Pour le contenu

✅ **Rédaction SEO** : "Écris un article 2000 mots sur X"
✅ **Traduction** : "Traduis toutes les pages en allemand/italien"
✅ **Optimisation** : "Améliore le SEO de toutes les pages"

### Pour la maintenance

✅ **Mises à jour** : "Mets à jour Stripe SDK à la dernière version"
✅ **Sécurité** : "Vérifie toutes les failles de sécurité"
✅ **Performance** : "Optimise la vitesse de chargement"

## 📂 STRUCTURE DANS CLAUDE CODE

Quand vous ouvrez le projet, vous verrez :

```
troubles-succion-oralite/
├── 📄 index.html
├── 📄 inscription-professionnel.html
├── 📄 inscription-success.html
├── 📄 references.html
├── 📄 exercices.html
├── 📄 404.html
├── 📄 sitemap.xml
├── 📄 robots.txt
├── 📁 cantons/
│   └── 📄 vaud.html (modèle)
├── 📁 css/
│   └── 📄 style.css
├── 📁 js/
│   ├── 📄 script.js
│   ├── 📄 registration.js
│   └── 📄 canton-professionnels.js
├── 📁 api/
│   ├── 📄 config.example.php
│   ├── 📄 database.sql
│   ├── 📄 create-registration.php
│   ├── 📄 verify-backlink.php
│   ├── 📄 skip-backlink.php
│   ├── 📄 stripe-webhook.php
│   └── 📄 get-professionnels-canton.php
├── 📁 admin/
│   └── 📄 login.html
├── 📁 images/
├── 📄 composer.json
└── 📚 Documentation/
    ├── 📄 README.md
    ├── 📄 GUIDE-INSTALLATION-INSCRIPTION.md
    ├── 📄 STRATEGIE-SEO.md
    ├── 📄 SEO-RECAPITULATIF.md
    └── 📄 GUIDE-PERSONNALISATION.md
```

## 🎬 SCÉNARIO D'UTILISATION COMPLET

### Jour 1 - Setup

**Vous ouvrez Claude Code** :
```
Bonjour ! Je viens d'ouvrir mon projet troubles-succion-oralite. 
Peux-tu m'aider à créer les 5 pages cantonales manquantes ? 
Commence par Valais.
```

**Claude Code** :
- Crée cantons/valais.html
- Adapte tout le contenu
- Optimise le SEO
- Vous demande validation

Vous validez → il passe à Genève, etc.

### Jour 2 - Personnalisation

**Vous** :
```
Je veux changer les couleurs pour quelque chose de plus moderne. 
Propose-moi 3 palettes.
```

**Claude Code** vous propose des palettes, vous choisissez, il modifie CSS.

### Jour 3 - Contenu

**Vous** :
```
Crée la page "pour-qui.html" qui explique à qui s'adresse le site :
- Parents de nouveaux-nés
- Femmes enceintes
- Professionnels de santé

Format professionnel, 1500 mots, SEO optimisé.
```

**Claude Code** crée la page complète.

### Jour 4 - Fonctionnalités

**Vous** :
```
Ajoute un bouton "Partager sur WhatsApp" sur chaque page canton 
pour que les parents puissent partager facilement.
```

**Claude Code** ajoute le bouton partout.

## 🔐 SÉCURITÉ DES DONNÉES

**Important** : Claude Code travaille en LOCAL sur votre machine.

- ✅ Vos fichiers restent privés
- ✅ Pas d'envoi automatique vers le cloud
- ✅ Vous contrôlez tout

**Conseil** : Ne committez JAMAIS config.php avec vraies clés dans Git !

## 📚 RESSOURCES UTILES

### Documentation Claude Code
https://docs.anthropic.com/claude/docs/claude-code

### Commandes Git de base
```bash
git status                    # Voir les fichiers modifiés
git add .                     # Ajouter tous les fichiers
git commit -m "Message"       # Commit
git push                      # Envoyer sur GitHub
```

### Serveur local PHP
```bash
cd ~/Documents/troubles-succion-oralite
php -S localhost:8000
```

Puis ouvrir : http://localhost:8000

## ✅ CHECKLIST MIGRATION

- [ ] Claude Code installé
- [ ] Projet extrait dans un dossier
- [ ] Dossier ouvert dans Claude Code
- [ ] Contexte donné à Claude
- [ ] Test serveur local fonctionne
- [ ] Git initialisé (optionnel mais recommandé)
- [ ] Première tâche réalisée (ex: créer page Valais)

## 🎯 PROCHAINES ÉTAPES RECOMMANDÉES

1. **Ouvrir le projet dans Claude Code**
2. **Commencer par** : "Crée les 5 pages cantonales manquantes"
3. **Ensuite** : "Crée toutes les pages listées dans GUIDE-PERSONNALISATION.md"
4. **Puis** : "Aide-moi à configurer la base de données et Stripe"
5. **Enfin** : "Crée le dashboard admin complet"

## 💡 ASTUCES CLAUDE CODE

### Commandes efficaces

✅ **Être spécifique** :
❌ "Change les couleurs"
✅ "Change la couleur primaire de #d4a574 à #3498db dans css/style.css"

✅ **Demander des options** :
"Propose-moi 3 façons différentes d'afficher les professionnels"

✅ **Itérer** :
"C'est bien, mais rends le bouton plus gros et ajoute une ombre"

✅ **Tester** :
"Peux-tu créer une page de test pour vérifier que le formulaire fonctionne ?"

### Gestion de projet

Claude Code peut vous aider à :
- Créer un TODO list
- Planifier les tâches
- Prioriser les fonctionnalités
- Estimer les temps de développement

## 🎊 CONCLUSION

Avec Claude Code, vous pouvez :

- ✅ Gérer l'intégralité du projet
- ✅ Créer du contenu rapidement
- ✅ Modifier le design facilement
- ✅ Ajouter des fonctionnalités
- ✅ Débugger efficacement
- ✅ Maintenir le site à long terme

**C'est l'outil parfait pour votre projet !**

---

**Prêt à commencer ?**
1. Installez Claude Code
2. Ouvrez le projet
3. Dites : "Aide-moi à terminer mon site troubles-succion-oralite.ch"

Et c'est parti ! 🚀
