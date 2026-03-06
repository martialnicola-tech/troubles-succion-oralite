# Site Troubles de la Succion et Oralité

## 🎨 Description
Site web moderne et professionnel dédié aux troubles de la succion chez le nourrisson et l'enfant.
Design sobre avec des teintes chaudes et cosy, optimisé pour mobile et desktop.

## 📁 Structure du site

```
nouveau-site/
├── index.html              # Page d'accueil
├── references.html         # Page des références bibliographiques
├── exercices.html          # Page des exercices (avec disclaimer vidéos)
├── css/
│   └── style.css          # Styles principaux
├── js/
│   └── script.js          # Scripts JavaScript
└── images/
    └── logo.png           # À ajouter : votre logo
```

## 🚀 Déploiement sur Hostinger

### Étape 1 : Préparer les fichiers
1. Téléchargez tous les fichiers du site
2. Récupérez le logo depuis votre site actuel (https://www.troubles-succion-oralite.ch)
3. Placez le logo dans le dossier `images/` sous le nom `logo.png`

### Étape 2 : Connexion à Hostinger
1. Connectez-vous à votre compte Hostinger
2. Accédez au panneau de contrôle (hPanel)
3. Trouvez la section "Gestionnaire de fichiers" ou "File Manager"

### Étape 3 : Upload des fichiers
**Option A - Via File Manager :**
1. Ouvrez le File Manager
2. Naviguez vers le dossier `public_html` (ou le dossier racine de votre domaine)
3. Supprimez les anciens fichiers (faites une sauvegarde avant !)
4. Uploadez tous les fichiers et dossiers du nouveau site
   - index.html (à la racine)
   - references.html (à la racine)
   - exercices.html (à la racine)
   - Dossier css/ complet
   - Dossier js/ complet
   - Dossier images/ complet

**Option B - Via FTP (recommandé pour les gros sites) :**
1. Installez un client FTP comme FileZilla
2. Connectez-vous avec vos identifiants FTP Hostinger :
   - Hôte : ftp.votredomaine.ch
   - Utilisateur : [fourni par Hostinger]
   - Mot de passe : [fourni par Hostinger]
   - Port : 21
3. Uploadez tous les fichiers vers `public_html/`

### Étape 4 : Configuration du domaine
1. Assurez-vous que votre domaine pointe vers le bon dossier
2. Dans hPanel > Domaines, vérifiez que `troubles-succion-oralite.ch` pointe vers `public_html`

### Étape 5 : Vérification
1. Visitez https://www.troubles-succion-oralite.ch
2. Testez tous les liens de navigation
3. Vérifiez l'affichage sur mobile et desktop
4. Testez le menu hamburger sur mobile

## 📝 Pages à créer ensuite

Les pages suivantes devront être créées pour compléter le site :
- pour-qui.html
- troubles.html
- frein-langue.html
- frein-levre.html
- frenectomie.html
- jouets.html
- equipe.html (+ sous-pages de l'équipe)
- contact.html

## 🎨 Palette de couleurs

- **Primaire** : #d4a574 (beige doré chaleureux)
- **Primaire foncé** : #b8875f
- **Primaire clair** : #e8c9a8
- **Secondaire** : #9d8b7c (taupe)
- **Accent** : #c9937e (terre de sienne)
- **Fond** : #faf8f6 (blanc cassé chaleureux)
- **Texte** : #4a4238 (brun foncé)

## 🔧 Personnalisation

### Modifier les couleurs
Ouvrez `css/style.css` et modifiez les variables CSS au début du fichier (section `:root`).

### Ajouter du contenu
Les pages HTML sont structurées avec des sections claires. Vous pouvez :
- Copier/coller une section existante
- Modifier le texte directement dans le HTML
- Ajouter des images dans le dossier `images/`

### Ajouter des vidéos
Pour intégrer des vidéos YouTube :
```html
<div class="video-container">
    <iframe src="https://www.youtube.com/embed/VIDEO_ID" 
            allowfullscreen>
    </iframe>
</div>
```

## 📱 Responsive Design

Le site est entièrement responsive et s'adapte automatiquement :
- Desktop (> 768px) : Navigation horizontale complète
- Tablette et Mobile (< 768px) : Menu hamburger
- Petits mobiles (< 480px) : Optimisations supplémentaires

## ✅ Checklist avant la mise en ligne

- [ ] Logo ajouté dans `images/logo.png`
- [ ] Toutes les images du site actuel récupérées
- [ ] Références bibliographiques ajoutées dans `references.html`
- [ ] Vidéos intégrées (si disponibles) dans `exercices.html`
- [ ] Coordonnées de contact mises à jour
- [ ] Test sur différents navigateurs (Chrome, Firefox, Safari)
- [ ] Test sur mobile et tablette
- [ ] Vérification de tous les liens

## 🆘 Support

Si vous rencontrez des problèmes :
1. Vérifiez que tous les fichiers sont bien uploadés
2. Vérifiez les permissions des fichiers (644 pour les fichiers, 755 pour les dossiers)
3. Videz le cache de votre navigateur (Ctrl+F5)
4. Contactez le support Hostinger si nécessaire

## 📞 Contact

Site réalisé par @mndesign
Pour toute question technique, référez-vous à la documentation Hostinger.

---

**Note importante** : Gardez toujours une sauvegarde de votre site actuel avant de le remplacer !
