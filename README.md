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
Bash
php bin/websocket-server.php


Lancer le script Python de simulation : Ouvrez un second terminal et lancez le script de simulation des temps d'attente :
Bash
python scripts/simulation_attente.py


🎢 4. Informations du Parc & Tarifs
Nom du Parc : Studio Toon
Tarifs de base :
Billet Adulte : [À remplir, ex: 39,00 €]
Billet Enfant (< 12 ans) : [À remplir, ex: 29,00 €]
Upsells / Options :
Pass Coupe-File Express : [À remplir, ex: +15,00 €]
Option Parking VIP : [À remplir, ex: +10,00 €]
🔑 5. Comptes de Test
Rôle
Email
Mot de passe
Administrateur
[À remplir, ex: admin@studiotoon.fr]
[À remplir]
Utilisateur Test
[À remplir, ex: test@studiotoon.fr]
[À remplir]

🎨 6. Maquettes & Design (Figma)
Lien vers le fichier de conception graphique : [Lien Figma - Studio Toon]([Insérer le lien Figma ici])
✅ 7. Fonctionnalités Validées (Palier par Palier)
[ ] Palier 1 : Architecture MVC, routeur et connexion PDO sécurisée.
[ ] Palier 2 : Authentification complète (Argon2id, sessions, récupération de mot de passe par token).
[ ] Palier 3 : Espace profil utilisateur et historique des réservations avec accès aux e-billets.
[ ] Palier 4 : Planificateur de journée intelligent (saisie dynamique de la taille, filtrage des attractions ouvertes et tri par temps d'attente).
[ ] Palier 5 : Temps réel (WebSocket et script Python de simulation).
🐛 8. Bugs Connus
Administration : Manque de responsivité (responsive design) sur l'interface de gestion du CRUD des attractions (affichage sur mobile perfectible).
💡 9. Choix Techniques
[À remplir : ex. Utilisation de PHP natif sans framework pour maîtriser le routing, choix du Brutalisme/Neumorphism pour l'UI, couplage PHP/Python pour la simulation, etc.]
