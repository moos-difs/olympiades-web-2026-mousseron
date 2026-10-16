# 🎬 Studio Toon - Application Web MVC & Temps Réel

> **Studio Toon** est une application web interactive aux couleurs d'un parc d'attractions sur le thème de l'animation. Développée en **PHP pur (architecture MVC)** avec une base de données MySQL via **PDO**, elle intègre également un système de WebSocket en PHP et un script de simulation en Python pour actualiser les temps d'attente en direct.

---

## 🛠️ 1. Prérequis & Installations (Composer, Python, Pip)

Avant de lancer le projet, assurez-vous d'avoir installé sur votre machine :
* **PHP** (>= 8.0) avec l'extension PDO activée.
* **Composer** (pour la gestion des dépendances PHP).
* **Python** (>= 3.8) et **Pip** (pour le script de simulation).

### Étapes d'installation :
1. **Cloner le projet** dans votre répertoire serveur local (ex: `htdocs/studio-toon` sous XAMPP).
2. **Installer les dépendances PHP :**
   ```bash
   composer install
3. **Installer les dépendances Python (nécessaires pour le script de simulation mysql-connector-python)** :
    ```bash
    pip install mysql-connector-python requests
4. **Importer la base de données MySQL via phpMyAdmin (database.sql).**

## ⚙️ 2. Configuration (`.env`)

Le projet utilise un fichier d'environnement pour sécuriser les accès. 
1. **Dupliquez le fichier `.env.example` et renommez-le en `.env`.**
2. **Renseignez vos informations de connexion locales :**

    ```env
    DB_HOST=localhost
    DB_NAME=olympiades
    DB_USER=root
    DB_PASSWORD=
## 🚀 3. Lancement des Services (WebSocket & Simulation Python)

Pour profiter de l'expérience en temps réel (mise à jour dynamique des temps d'attente dans le parc) :

1. **Lancer le serveur WebSocket en PHP :**
Ouvrez un terminal à la racine du projet et exécutez :
    ```bash
    php bin/websocket-server.php
2. **Lancer le script Python de simulation : Ouvrez un second terminal et lancez le script de simulation des temps d'attente :**
    ```bash
    python scripts/simulation_attente.py
## 🎢 4. Informations du Parc & Tarifs
○ **Nom du Parc :** Studio Toon

○ **Tarifs de base :**

• Billet Adulte : 45,00€

• Billet Enfant (3 à 12 ans) : 30,00€

• Billet Moins de 3 ans : Gratuit

○ **Augmentations éventuelles :**

• Selon le nombre de places disponibles : De +20% à +75% du prix

## 🔑 5. Comptes de Test
| Rôle | Email | Mot de passe |
| --- | --- | --- |
| Administrateur | admin@studiotoon.com | admin123
| Utilisateur Test | user@studiotoon.com | user
| Utilisateur Test 2 | test@studiotoon.com | toon

## 🎨 6. Maquettes & Design (Figma)
Lien vers le fichier de conception graphique : [Lien Figma - Studio Toon](https://www.figma.com/design/25r2zlfSOSYl4IoHIAkQ4t/Sans-titre?node-id=0-1&t=IAXerCPM8LtLsEzT-1/)

## ✅ 7. Fonctionnalités Validées (Palier par Palier)
**[✅] Palier 1 : Le parc en ligne**
- Page d'accueil attrayant,
- Page de liste des attractions avec filtre par univers,
- Page détail des attractions avec image, description, taille minimale er statut et page d'erreur,
- Site responsive,
- Base de donnéees contenant univers, attractions et affichage des infos à partir de celle-ci.

**[✅] Palier 2 : L'espace administrateur**
- Page de connexion avec compte administrateur, option de déconnexion, et compte sécurisé par mot de passe haché par l'algorithme argon2id,
- Protection du serveur contre les injections SQL à l'aide de requêtes préparées,
- Page CRUD des attractions, avec refus d'un mauvais formulaire et confirmation de suppression,
- Temps d'attente saisi par l'administrateur et conservation par base de log des changements.

**[✅] Palier 3 : La billetterie**
- Ajout d'un compte utilisateur avec email et mot de passe, refus d'un email non unique ou invalide, mots de passes hachés également,
- Page billetterie avec choix de la date et du nombre de billets de chaque tarif, impossibilité de choisir une date passée et restriction à 2000 tickets par jour,
- Calcul du prix coté back-end pour éviter les injections,
- Page de paiement, avec faux formulaire de carte bancaire, page de validation de commande,
- Page de visualisation des réservations,
- Page admin de consultation des commandes, inaccessibles coté client.

**[✅] Palier 4 : Le parc en direct**
- Page En Direct affichant le statut et le temps d'attente de chaque attraction par mise à jour automatique,
- Script python de simulation des temps d'attentes réalistes,
- Email de confirmation après commande avec e-billets et qr codes uniques,
- Le mail est représenté en page html accessible depuis la page Mon Espace Personnel,
- Page admin de contrôle des billets, avec saisie du code de billet et vérification de validité de celui-ci.

**[✅] Palier 5 : Pour aller plus loin**
- Page administrateur de statistiques en graphiques,
- Prix dynamique évoluant par rapport au nombre de billets restants par jours,
- Page planificateur pour proposer aux utilisateurs un ordre de visite avec le moins d'attente possible par rapport à la taille du visiteur,
- Fonction mot de passe oublié avec mail et lien à usage unique et durée limitée, avec page de réinitialisation fonctionnelle,
- Amélioration de la page En Direct avec WebSocket pour vrai temps réel d'actualisation,
- Lien d'api (index.php?action=api&id=*) pour récupération en format json des informations d'une attraction précise,
- Fichier CSS ajoutant des animations fluides et soignées pour améliorer l'exérience utilisateur,
- Idée personnelle -> Page Carte Intéractive, avec plan du site et bouton de chaque attraction sur le plan pour les informations la concernant.
## 🐛 8. Bugs Connus
Administration : Manque de responsivité (responsive design) sur l'interface de gestion du CRUD des attractions (affichage sur mobile perfectible).

## 💡 9. Choix Techniques
[À remplir : ex. Utilisation de PHP natif sans framework pour maîtriser le routing, choix du Brutalisme/Neumorphism pour l'UI, couplage PHP/Python pour la simulation, etc.]

**Techno utilisées :**
PHP vanilla

**Architecture :**
MVC avec CRUD et routeur 

**Explication des choix :**
J'ai décidé d'utiliser le php vanilla pour construire mon mvc car je suis plutôt à l'aise avec celui-ci grâce à mon expérience. Je voulais un thème original pour mon parc alors mon choix s'est orienté pour cet UI aux informations minimalistes tout en utilisant une charte graphique inspirée des cartoons, et ai donc basé le thème de mon parc sur les cartoons, avec des attractions représentant les gags cartoons les plus connus.