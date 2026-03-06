# 🤖 STRATÉGIE AEO - AI ENGINE OPTIMIZATION

## 🎯 Qu'est-ce que l'AEO ?

L'**AEO (AI Engine Optimization)** est l'optimisation de votre contenu pour les moteurs IA comme :
- **ChatGPT** (OpenAI)
- **Claude** (Anthropic)
- **Perplexity** (moteur de recherche IA)
- **Gemini** (Google)
- **Microsoft Copilot**
- **SearchGPT** (OpenAI)

### Différence avec le SEO traditionnel

| SEO (Google) | AEO (ChatGPT, Claude, etc.) |
|--------------|----------------------------|
| Mots-clés répétés | Langage naturel conversationnel |
| Backlinks importants | Autorité et citations importantes |
| Titre H1 crucial | Structure claire + contexte |
| Meta description | Contenu compréhensible par IA |
| Position dans SERP | Être cité comme source |

## 🚀 STRATÉGIE MISE EN PLACE

### 1. Contenu structuré pour IA ✅

**Format Question-Réponse**
Les IA adorent ce format pour générer des réponses :

```html
<section itemscope itemtype="https://schema.org/FAQPage">
  <h2>Questions fréquentes sur les troubles de la succion</h2>
  
  <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
    <h3 itemprop="name">Qu'est-ce qu'un frein de langue restrictif ?</h3>
    <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
      <p itemprop="text">Un frein de langue restrictif (ankyloglossie) est...</p>
    </div>
  </div>
</section>
```

**Déjà implémenté sur vos pages cantonales !**

### 2. Données structurées enrichies ✅

**MedicalWebPage Schema**
```json
{
  "@context": "https://schema.org",
  "@type": "MedicalWebPage",
  "name": "Troubles de la Succion - Canton de Vaud",
  "description": "...",
  "about": {
    "@type": "MedicalCondition",
    "name": "Troubles de la succion",
    "alternateName": ["Ankyloglossie", "Frein de langue restrictif"],
    "associatedAnatomy": {
      "@type": "AnatomicalStructure",
      "name": "Langue"
    }
  }
}
```

### 3. Citations et sources ✅

**Page Références bibliographiques**
Les IA favorisent les sites qui citent des sources scientifiques.

Votre page `references.html` :
- Articles scientifiques
- Études cliniques
- Recommandations officielles
- Permet aux IA de vous citer comme source fiable

### 4. Contenu conversationnel ✅

**Langage naturel**
Vos pages utilisent un ton naturel que les IA comprennent bien :

❌ Mauvais (trop SEO) :
"Troubles succion bébé Lausanne. Frein langue Lausanne. Consultante lactation Lausanne."

✅ Bon (AEO) :
"Vous habitez dans le canton de Vaud et rencontrez des difficultés d'allaitement ? Notre annuaire recense les professionnels formés aux troubles de la succion."

### 5. Contexte géographique clair ✅

Les IA ont besoin de contexte précis :

```html
<meta name="geo.region" content="CH-VD">
<meta name="geo.placename" content="Vaud, Suisse">
```

Plus :
- Mentions explicites des villes
- Descriptions géographiques claires
- Canton mentionné plusieurs fois naturellement

## 📝 OPTIMISATIONS SPÉCIFIQUES

### Pour ChatGPT & SearchGPT

**1. Contenu long et approfondi** ✅
- Vos pages font ~2000 mots
- Couvrent le sujet en profondeur
- ChatGPT préfère les sources complètes

**2. Format Markdown-friendly**
Structure claire avec H2, H3, listes :
```
## Titre principal
### Sous-titre
- Point 1
- Point 2
```

**3. Définitions claires**
Première occurrence d'un terme = définition :
```
L'ankyloglossie (frein de langue restrictif) est une condition...
```

### Pour Perplexity

**1. Citations et sources** ✅
Page références avec :
- Auteurs
- Dates de publication
- Titres d'études
- Liens vers articles

**2. Informations factuelles précises**
Perplexity aime les faits vérifiables :
- "30 CHF par an"
- "Canton de Vaud"
- "Lausanne, Montreux, Nyon"

**3. Mise à jour régulière**
- Date de dernière modification
- Contenu dynamique (professionnels)

### Pour Claude (Anthropic)

**1. Contenu éthique et médical** ✅
Votre disclaimer sur les exercices :
```
⚠️ Ces exercices doivent impérativement être réalisés 
sous la supervision d'un professionnel de santé qualifié.
```

Claude favorise les contenus responsables.

**2. Structure logique**
- Introduction
- Corps structuré
- Conclusion
- Navigation claire

**3. Liens contextuels**
Maillage interne avec contexte :
```
Consultez notre page sur la [frénectomie](lien) 
pour plus d'informations sur l'intervention.
```

## 🎯 NOUVELLES OPTIMISATIONS À AJOUTER

### 1. Page FAQ dédiée (URGENT)

Créer `/faq.html` avec questions courantes :

```html
<!DOCTYPE html>
<html lang="fr-CH">
<head>
    <title>FAQ - Troubles de la Succion en Suisse Romande</title>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "Comment savoir si mon bébé a un frein de langue ?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Plusieurs signes peuvent indiquer un frein de langue restrictif : langue en forme de cœur quand bébé pleure, difficulté à téter, claquements pendant l'allaitement, douleurs aux mamelons chez la maman. Une évaluation par un professionnel formé (consultante IBCLC, ostéopathe, sage-femme) est recommandée."
          }
        },
        {
          "@type": "Question",
          "name": "Où trouver un spécialiste des troubles de succion en Suisse romande ?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Notre annuaire recense des professionnels spécialisés dans tous les cantons romands : Vaud, Valais, Genève, Fribourg, Neuchâtel et Jura. Vous pouvez consulter la page de votre canton pour trouver un ostéopathe, consultante en lactation IBCLC, logopédiste ou autre spécialiste proche de chez vous."
          }
        },
        {
          "@type": "Question",
          "name": "Qu'est-ce qu'une frénectomie ?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "La frénectomie est une intervention simple qui consiste à couper le frein de langue ou de lèvre restrictif. Elle peut être réalisée au laser ou aux ciseaux par un dentiste ou ORL formé. L'intervention dure quelques minutes et permet d'améliorer la mobilité de la langue pour faciliter l'allaitement et prévenir d'autres problèmes."
          }
        },
        {
          "@type": "Question",
          "name": "L'allaitement est douloureux, est-ce normal ?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Non, l'allaitement ne devrait pas être douloureux. Des douleurs aux mamelons peuvent indiquer un problème de prise du sein, parfois causé par un frein de langue restrictif. Il est important de consulter rapidement une consultante en lactation IBCLC ou une sage-femme formée pour évaluer la situation."
          }
        },
        {
          "@type": "Question",
          "name": "Quels professionnels peuvent aider pour les troubles de la succion ?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "La prise en charge des troubles de succion nécessite souvent une approche pluridisciplinaire : consultante en lactation IBCLC pour l'allaitement, ostéopathe pour les tensions corporelles, dentiste ou ORL pour la frénectomie si nécessaire, logopédiste pour les troubles de l'oralité, et physiothérapeute pédiatrique pour les exercices."
          }
        },
        {
          "@type": "Question",
          "name": "Mon bébé refuse les morceaux, que faire ?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Le refus des morceaux peut être lié à des troubles de l'oralité. Un logopédiste spécialisé en oralité infantile peut évaluer et accompagner votre enfant. Des exercices de stimulation oro-faciale et une approche progressive peuvent aider. Il est parfois utile de consulter également un ostéopathe."
          }
        }
      ]
    }
    </script>
</head>
```

### 2. Ajouter des entités nommées

Aider les IA à comprendre le contexte :

```html
<span itemscope itemtype="https://schema.org/MedicalSpecialty">
  <span itemprop="name">Consultante en lactation IBCLC</span>
</span>
```

### 3. Enrichir les métadonnées médicales

```json
{
  "@context": "https://schema.org",
  "@type": "MedicalCondition",
  "name": "Ankyloglossie",
  "alternateName": ["Frein de langue restrictif", "Tongue-tie"],
  "associatedAnatomy": {
    "@type": "AnatomicalStructure",
    "name": "Frein lingual"
  },
  "possibleTreatment": {
    "@type": "MedicalTherapy",
    "name": "Frénectomie"
  },
  "signOrSymptom": [
    "Difficultés d'allaitement",
    "Douleurs aux mamelons",
    "Prise de poids insuffisante",
    "Langue en forme de cœur"
  ]
}
```

### 4. Breadcrumbs (fil d'Ariane)

Pour la navigation et le contexte :

```json
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Accueil",
      "item": "https://www.troubles-succion-oralite.ch/"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Cantons",
      "item": "https://www.troubles-succion-oralite.ch/cantons/"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "Vaud",
      "item": "https://www.troubles-succion-oralite.ch/cantons/vaud.html"
    }
  ]
}
```

## 🔍 COMMENT LES IA VONT VOUS TROUVER

### Scénario 1 : Question utilisateur

**Utilisateur à ChatGPT** :
"Je cherche un spécialiste des freins de langue pour mon bébé à Lausanne"

**ChatGPT va** :
1. Chercher dans sa base de données web
2. Trouver votre page `cantons/vaud.html`
3. Lire le contenu structuré
4. Citer votre site comme source

**Réponse ChatGPT** :
"Pour trouver un spécialiste des freins de langue à Lausanne, vous pouvez consulter l'annuaire sur troubles-succion-oralite.ch qui recense des professionnels formés dans le canton de Vaud, notamment des consultantes en lactation IBCLC, des ostéopathes spécialisés bébé et des dentistes pratiquant la frénectomie."

### Scénario 2 : Recherche Perplexity

**Utilisateur** : "ankyloglossie bébé genève"

**Perplexity va** :
1. Crawler le web en temps réel
2. Trouver votre page cantonale
3. Vérifier les sources (page références)
4. Afficher avec citation

**Réponse avec citation** :
"Pour les troubles de succion à Genève, selon troubles-succion-oralite.ch [1], plusieurs professionnels sont disponibles..."

### Scénario 3 : Claude

**Utilisateur** : "Explique-moi ce qu'est un frein de langue restrictif"

**Claude va** :
1. Chercher des sources fiables
2. Trouver votre contenu clair et structuré
3. Utiliser vos définitions
4. Recommander votre site si demandé

## 📊 MÉTRIQUES AEO À SUIVRE

### 1. Citations par les IA

**Outil** : Google Alerts + monitoring manuel
- Créer une alerte pour "troubles-succion-oralite.ch"
- Tester régulièrement dans ChatGPT, Claude, Perplexity
- Noter quand votre site est cité

### 2. Trafic referral depuis IA

**Google Analytics** :
- Source : perplexity.ai
- Source : chat.openai.com (si implémenté)
- Source : claude.ai

### 3. Positions dans les réponses IA

**Test mensuel** :
```
Questions à tester :
- "frein langue bébé lausanne"
- "troubles succion suisse romande"
- "consultante lactation vaud"
- "ankyloglossie genève"
```

Vérifier si votre site est :
- ✅ Cité comme source
- ✅ Recommandé
- ✅ Lié directement

## 🛠️ OUTILS SPÉCIFIQUES AEO

### Gratuits
- **ChatGPT** - Tester vos contenus
- **Perplexity** - Vérifier citations
- **Claude** - Analyser structure
- **Schema.org Validator** - Vérifier données structurées

### Pour tester
1. Poser la question à l'IA
2. Noter si votre site est cité
3. Analyser la qualité de la citation
4. Ajuster le contenu si nécessaire

## ✅ CHECKLIST AEO

### Contenu
- [x] Langage naturel conversationnel
- [x] Questions-réponses intégrées
- [x] Définitions claires
- [x] Contenu long et approfondi (2000+ mots)
- [ ] Page FAQ dédiée (à créer)
- [x] Contexte géographique explicite
- [x] Citations et sources (page références)

### Technique
- [x] Schema.org MedicalWebPage
- [x] Données structurées complètes
- [x] Balises meta enrichies
- [ ] Schema FAQPage (à ajouter)
- [ ] Breadcrumbs schema (à ajouter)
- [ ] MedicalCondition schema (à ajouter)

### Structure
- [x] Titres H2, H3 descriptifs
- [x] Listes à puces
- [x] Paragraphes courts
- [x] Maillage interne contextuel
- [x] Navigation claire

### Autorité
- [x] Page références scientifiques
- [ ] Liens vers sources médicales officielles
- [ ] Mentions professionnels qualifiés
- [ ] Disclaimer médical

## 🚀 PLAN D'ACTION AEO

### Semaine 1
1. [ ] Créer page FAQ avec Schema FAQPage
2. [ ] Ajouter breadcrumbs sur toutes les pages
3. [ ] Enrichir Schema.org MedicalCondition

### Semaine 2
4. [ ] Tester dans ChatGPT, Claude, Perplexity
5. [ ] Noter les citations
6. [ ] Ajuster contenu si nécessaire

### Mois 1
7. [ ] Créer 10 articles FAQ blog
8. [ ] Enrichir page références
9. [ ] Ajouter témoignages (si applicable)

### Suivi mensuel
- Tester 10 questions dans les IA
- Noter % de citations
- Optimiser pages peu citées

## 💡 ASTUCES AVANCÉES

### 1. Format "How-to" pour les IA

```html
<div itemscope itemtype="https://schema.org/HowTo">
  <h2 itemprop="name">Comment reconnaître un frein de langue</h2>
  <div itemprop="step" itemscope itemtype="https://schema.org/HowToStep">
    <span itemprop="name">Étape 1 : Observer la langue</span>
    <p itemprop="text">Quand bébé pleure, observez si la langue prend une forme de cœur...</p>
  </div>
</div>
```

### 2. Entités liées

Créer un glossaire interne :
```
- Ankyloglossie = Frein de langue restrictif
- IBCLC = International Board Certified Lactation Consultant
- Oralité = Ensemble des fonctions de la bouche
```

### 3. Contexte temporel

Ajouter dates :
```html
<time datetime="2026-02-13">Dernière mise à jour : 13 février 2026</time>
```

## 🎯 OBJECTIFS AEO

### 3 mois
- ✅ Être cité par au moins 1 IA sur 3
- ✅ 10% des requêtes testées citent le site
- ✅ FAQ page indexée

### 6 mois
- Top 3 des sources citées pour "troubles succion suisse"
- 30% des requêtes testées citent le site
- Trafic referral depuis IA visible

### 12 mois
- Source #1 en Suisse romande pour les IA
- 50%+ des requêtes pertinentes citent le site
- Featured dans les réponses IA

## 🎊 CONCLUSION

Votre site est **déjà bien positionné pour l'AEO** grâce à :
- ✅ Contenu structuré et clair
- ✅ Schema.org implémenté
- ✅ Page références
- ✅ Langage naturel

**Actions prioritaires** :
1. Créer page FAQ (1-2 heures)
2. Tester dans les IA (30 min/semaine)
3. Enrichir progressivement

Avec ces optimisations, vous serez **LA référence citée par les IA** pour les troubles de succion en Suisse romande ! 🚀
