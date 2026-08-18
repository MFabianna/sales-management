# SALES - Application de Gestion des Ventes

## Description
SALES est une application web de gestion développée avec Laravel. Elle permet à une entreprise
de gérer ses ventes, ses clients, son inventaire de produits et de générer des factures PDF.

## Fonctionnalités
- Authentification sécurisée (Laravel Breeze, mots de passe hachés avec bcrypt)
- Gestion des clients (CRUD + historique des achats)
- Gestion des produits (CRUD + stock, unités, catégories, codes automatiques)
- Gestion des ventes (CRUD + codes automatiques + décrémentation du stock via transactions)
- Génération et téléchargement de factures PDF
- Tableau de bord (statistiques, top 1 par catégorie, alertes stock faible)

## Stack Technique
- Backend : PHP 8+, Laravel, MySQL
- Frontend : Bootstrap 5, Blade, JavaScript
- Authentification : Laravel Breeze
- PDF : barryvdh/laravel-dompdf
- Versioning : Git & GitHub

## Prérequis
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL
- Git

## Installation
1. Cloner le dépôt
   git clone https://github.com/TON_UTILISATEUR/sales.git
   cd sales

2. Installer les dépendances
   composer install
   npm install

3. Configurer l'environnement
   cp .env.example .env
   php artisan key:generate

4. Configurer la base de données dans le fichier .env
   DB_DATABASE=db_sales_management
   DB_USERNAME=root
   DB_PASSWORD=

5. Créer les tables et générer les données de démonstration
   php artisan migrate:fresh --seed

6. Lancer l'application
   php artisan serve
   npm run dev

## Compte de démonstration
- Email : admin@sales.com
- Mot de passe : Salesfff123

## Références (APA)
- Otwell, T. (2026). Laravel documentation. https://laravel.com/docs
- The Bootstrap Authors. (2026). Bootstrap documentation. https://getbootstrap.com/docs
- Barry vd. (2026). Laravel DomPDF. https://github.com/barryvdh/laravel-dompdf

## Auteur
Développé par Fabiana M.R
