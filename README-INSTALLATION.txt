=== UMMA RAHMA - SITE PHP + MYSQL AVEC DASHBOARD ADMIN ===

PROJET PRÊT POUR INFINITYFREE

## COMPTE ADMIN PAR DÉFAUT
Email : admin@umma-rahma.org
Mot de passe : admin123
IMPORTANT : Changer ce mot de passe immédiatement après première connexion !

## INSTALLATION

### 1. Créer base de données sur InfinityFree
- Aller dans MySQL Databases
- Créer base + utilisateur avec tous les privilèges
- Noter nom base, utilisateur, mot de passe

### 2. Importer SQL
- Ouvrir phpMyAdmin
- Sélectionner votre base
- Importer umma_rahma.sql

### 3. Configurer
- Éditer includes/config.php
- Remplir DB_HOST, DB_NAME, DB_USER, DB_PASS
- Mettre SITE_URL à votre domaine

### 4. Uploader
- Uploader tout le contenu dans htdocs/ via File Manager ou FTP
- Créer dossier uploads/ avec permissions 755 (généralement automatique)

### 5. Tester
- Site : https://votre-nom.infinityfreeapp.com
- Admin : https://votre-nom.infinityfreeapp.com/admin/login.php
- Connexion : admin@umma-rahma.org / admin123

## FONCTIONNALITÉS
- Dashboard admin protégé
- Gestion événements (ajout/modification/suppression)
- Système automatique À venir/Passés
- Galerie multimédia par événement
- Upload images/vidéos
- Gestion actualités
- Paramètres modifiables
- Gestion utilisateurs

## SÉCURITÉ
- Mots de passe hashés
- Sessions PHP
- Protection pages admin
- Upload vérifié
