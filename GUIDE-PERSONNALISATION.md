# Guide de Personnalisation et Prochaines Étapes

## 🎯 Ce qui a été créé

✅ **Pages complètes :**
- index.html (Page d'accueil avec tous les symptômes)
- references.html (Page des références avec structure prête)
- exercices.html (Page des exercices avec disclaimer vidéos)
- 404.html (Page d'erreur personnalisée)

✅ **Fichiers techniques :**
- style.css (Design complet avec palette chaude et cosy)
- script.js (Navigation mobile, animations, smooth scroll)
- .htaccess (Optimisations performance et sécurité)
- README.md (Instructions de déploiement)

## 📋 Pages à créer prochainement

### 1. pour-qui.html
**Contenu suggéré :**
- Parents de nouveaux-nés
- Parents de bébés allaités avec difficultés
- Parents d'enfants avec troubles de l'oralité
- Professionnels de santé

### 2. troubles.html
**Contenu suggéré :**
- Description détaillée des différents troubles
- Signes d'alerte
- Quand consulter
- Approche pluridisciplinaire

### 3. frein-langue.html
**Contenu suggéré :**
- Qu'est-ce qu'un frein de langue restrictif ?
- Types de freins (antérieur, postérieur)
- Diagnostic
- Impact sur l'allaitement et l'oralité
- Tests d'évaluation

### 4. frein-levre.html
**Contenu suggéré :**
- Anatomie du frein de lèvre
- Symptômes
- Différence avec le frein de langue
- Traitement

### 5. frenectomie.html
**Contenu suggéré :**
- En quoi consiste l'intervention
- Techniques (ciseaux, laser)
- Préparation
- Récupération et soins post-opératoires
- Résultats attendus

### 6. jouets.html
**Contenu suggéré :**
- Jouets de stimulation orale recommandés
- Par âge
- Où les trouver
- Comment les utiliser

### 7. equipe.html (+ sous-pages)
**Contenu suggéré :**
- Présentation de l'équipe pluridisciplinaire
- Rôle de chaque spécialiste
- Coordonnées et zones de consultation
- Sous-pages pour chaque spécialité avec liste des praticiens

### 8. contact.html
**Contenu suggéré :**
- Formulaire de contact
- Informations pratiques
- Carte ou localisation
- Liens vers réseaux sociaux (si applicable)

## 🔧 Comment créer une nouvelle page

### Template de base à utiliser :

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titre de la page - Troubles de la Succion</title>
    <meta name="description" content="Description pour SEO">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Copier le header depuis index.html -->
    <header class="header">
        <!-- ... -->
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Titre principal</h1>
                <p class="hero-subtitle">Sous-titre</p>
            </div>
        </div>
    </section>

    <!-- Contenu principal -->
    <main>
        <section class="section">
            <div class="container">
                <h2>Votre contenu ici</h2>
                <p>Texte...</p>
            </div>
        </section>
    </main>

    <!-- Copier le footer depuis index.html -->
    <footer class="footer">
        <!-- ... -->
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
```

## 📝 Ajouter des références bibliographiques

Dans `references.html`, ajoutez vos références en copiant ce modèle :

```html
<div class="reference-item">
    <div class="reference-title">Titre de l'article</div>
    <div class="reference-authors">Nom des auteurs</div>
    <div class="reference-source">Journal, année</div>
    <p class="mt-small">Résumé ou description</p>
    <a href="URL" class="reference-link">→ Accéder à l'article</a>
</div>
```

## 🎥 Intégrer des vidéos

Dans la page `exercices.html`, remplacez les placeholders par vos vidéos :

### Pour YouTube :
```html
<div class="video-container">
    <iframe src="https://www.youtube.com/embed/VIDEO_ID" 
            allowfullscreen>
    </iframe>
</div>
```

### Pour Vimeo :
```html
<div class="video-container">
    <iframe src="https://player.vimeo.com/video/VIDEO_ID" 
            allowfullscreen>
    </iframe>
</div>
```

**Note :** Le disclaimer est déjà en place au début de la page exercices.html

## 🖼️ Récupérer les images du site actuel

Les images sont déjà référencées dans `index.html` avec leurs URLs Google Sites.

**Option 1 - Les laisser telles quelles :**
Elles continueront à fonctionner depuis Google Sites.

**Option 2 - Les télécharger et héberger localement (recommandé) :**

1. Téléchargez chaque image depuis les URLs :
   - Image bébé : https://lh3.googleusercontent.com/sitesv/APaQ0SSrc5j7VvO...
   - Image maman : https://lh3.googleusercontent.com/sitesv/APaQ0STOQ9L9-koq...
   - Image bébé qui tète : https://lh3.googleusercontent.com/sitesv/APaQ0SRuX7cF...
   - Image enfant : https://lh3.googleusercontent.com/sitesv/APaQ0SQbwcZ6...

2. Placez-les dans le dossier `images/` avec des noms clairs :
   - bebe-difficulte.jpg
   - maman-allaitante.jpg
   - bebe-tete.jpg
   - enfant-orl.jpg

3. Mettez à jour les chemins dans `index.html` :
   ```html
   <img src="images/bebe-difficulte.jpg" alt="Bébé avec difficulté à téter">
   ```

## 🎨 Personnaliser les couleurs

Si vous souhaitez ajuster la palette de couleurs, modifiez dans `css/style.css` :

```css
:root {
    --color-primary: #d4a574;        /* Couleur principale */
    --color-primary-dark: #b8875f;   /* Version foncée */
    --color-primary-light: #e8c9a8;  /* Version claire */
    --color-secondary: #9d8b7c;      /* Couleur secondaire */
    --color-accent: #c9937e;         /* Couleur d'accent */
}
```

## 📱 Tester le site localement

Avant de mettre en ligne, vous pouvez tester le site localement :

1. Ouvrez simplement `index.html` dans votre navigateur
2. Ou utilisez un serveur local (VSCode avec extension Live Server, Python SimpleHTTPServer, etc.)

## ✅ Checklist finale avant mise en ligne

- [ ] Logo ajouté dans images/logo.png
- [ ] Toutes les images téléchargées et placées dans images/
- [ ] Références bibliographiques complétées dans references.html
- [ ] Vidéos intégrées dans exercices.html (si disponibles)
- [ ] Pages manquantes créées (pour-qui, troubles, etc.)
- [ ] Coordonnées de contact ajoutées
- [ ] Test de tous les liens
- [ ] Test sur mobile
- [ ] Test sur différents navigateurs

## 🚀 Mise en ligne

Suivez les instructions détaillées dans `README.md` pour le déploiement sur Hostinger.

## 💡 Conseils SEO

1. **Métadonnées :** Chaque page a une balise meta description - personnalisez-les
2. **Titres :** Utilisez des titres H1, H2, H3 de manière hiérarchique
3. **Alt text :** Ajoutez des descriptions alt sur toutes les images
4. **URLs :** Les URLs sont déjà propres (pas de caractères spéciaux)
5. **Sitemap :** Pensez à créer un sitemap.xml une fois toutes les pages créées

## 📞 Besoin d'aide ?

Si vous avez besoin d'aide pour :
- Créer les pages manquantes
- Ajouter du contenu spécifique
- Résoudre un problème technique
- Personnaliser davantage le design

N'hésitez pas à demander !

---

**Prochaine étape recommandée :** Commencez par ajouter votre logo et vos images, puis créez les pages une par une en utilisant le template fourni.
