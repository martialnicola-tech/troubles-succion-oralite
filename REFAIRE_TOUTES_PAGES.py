#!/usr/bin/env python3
"""
Script pour recréer TOUTES les pages avec la structure exacte d'annuaire.html
"""

# Template de base avec header/footer identiques
HEADER_TPL = '''<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{title}</title>
    <meta name="description" content="{description}">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <a href="index.html"><img src="images/logo.png" alt="Troubles de la Succion" class="logo-img"></a>
            </div>
            <nav class="nav">
                <button class="nav-toggle">☰</button>
                <ul class="nav-menu">
                    <li><a href="pour-qui.html">Pour qui ?</a></li>
                    <li><a href="troubles.html">Troubles</a></li>
                    <li><a href="frein-langue.html">Frein langue</a></li>
                    <li><a href="on-coupe-ou-pas.html">On coupe ou pas ?</a></li>
                    <li><a href="frenectomie.html">Frénectomie</a></li>
                    <li><a href="exercices.html">Exercices</a></li>
                    <li><a href="annuaire.html">Annuaire</a></li>
                    <li><a href="pro.html">Pro</a></li>
                    <li><a href="bases-scientifiques.html">Bases Scientifiques</a></li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <section class="hero" style="background-image: url('{hero_img}'); background-color: var(--turquoise);">
        <div class="hero-content">
            <h1 class="hero-title">{hero_title}</h1>
        </div>
    </section>
'''

FOOTER_TPL = '''    <footer class="footer">
        <div class="footer-content">
            <p>Copyright ©2026 I Toute reproduction même partie est interdite. I <a href="contact.html">Contact</a> I Site réalisé par @mndesign</p>
            <div class="footer-links">
                <a href="#">Mentions Légale</a>
                <a href="#">Politiques de Confidentialité</a>
                <a href="#">Conditions Générales</a>
            </div>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>'''

# Configuration de TOUTES les pages principales
pages_config = {
    'index.html': {
        'title': 'Troubles de la Succion - Suisse Romande',
        'description': 'Site d\'information sur les troubles de la succion et de l\'oralité en Suisse romande',
        'hero_title': 'Troubles de la Succion<br>chez le nourrisson et l\'enfant',
        'hero_img': 'images/hero/accueil.jpg',
        'content': '''
    <main>
        <section class="section">
            <div class="container">
                <h2>Bienvenue</h2>
                <p>Bienvenue sur le site d'information dédié aux troubles de la succion et de l'oralité chez le nourrisson et l'enfant en Suisse romande.</p>
                <div class="btn-group">
                    <a href="pour-qui.html" class="btn">En savoir plus</a>
                    <a href="annuaire.html" class="btn btn-outline">Trouver un professionnel</a>
                </div>
            </div>
        </section>
    </main>'''
    },
    
    'pour-qui.html': {
        'title': 'Pour qui ? - Troubles de la Succion',
        'description': 'À qui s\'adresse ce site sur les troubles de la succion',
        'hero_title': 'Pour qui ?',
        'hero_img': 'images/hero/pour-qui.jpg',
        'content': '''
    <main>
        <section class="section">
            <div class="container">
                <h2>Ce site s'adresse à vous si...</h2>
                <p>Vous êtes parent, professionnel de santé ou simplement intéressé par les troubles de la succion.</p>
            </div>
        </section>
    </main>'''
    }
}

print("✅ Script prêt - Configuration de", len(pages_config), "pages")
print("📋 Prochaine étape : créer toutes les pages HTML")
