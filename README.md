# SALES - Application de Gestion des Ventes

## Description du projet
SALES est une application web de gestion développée avec le framework Laravel. Elle permet à une entreprise de gérer efficacement ses ventes, son inventaire de produits, sa base de clients et de générer des factures au format PDF.

Ce projet a été réalisé dans le cadre du cours de développement web avancé.

## Fonctionnalités Principales
- **Authentification sécurisée** : Système de connexion via Laravel Breeze (mots de passe hachés avec bcrypt).
- **Gestion des Clients (CRUD)** : Ajout, modification, suppression et historique complet des achats par client.
- **Gestion des Produits (CRUD)** : Suivi de l'inventaire, gestion des catégories, des unités (Kg, Litre, Unité, Nombre) et alertes de stock faible.
- **Gestion des Ventes (CRUD)** : Enregistrement des ventes avec génération automatique de codes uniques (V-0001-AAAA-MM-JJ), calcul automatique des montants et **décrémentation atomique du stock** (via DB Transactions).
- **Facturation** : Génération et téléchargement de factures en PDF pour chaque vente.
- **Tableau de bord dynamique** : Statistiques mensuelles, évolution des ventes sur 6 mois, Top 5 des clients, et Top 1 des produits les plus vendus par catégorie.

## Stack Technique
- **Backend** : PHP 8+, Laravel 10/11
- **Frontend** : HTML5, Bootstrap 5, JavaScript (Vanilla)
- **Base de données** : MySQL
- **Authentification** : Laravel Breeze
- **Génération PDF** : barryvdh/laravel-dompdf
- **Versioning** : Git & GitHub

## Prérequis
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL / MariaDB
- Git

## Installation et Lancement

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/[MFabianna]/sales.git
   cd sales
