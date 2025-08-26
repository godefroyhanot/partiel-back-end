# Partiel Back-End - Système de Location de Matériel

## Description

**Projet académique** - Application web de gestion de location de matériel nautique développée dans le cadre d'un partiel de développement back-end. Le projet simule un système de gestion pour le parc d'activité du lac de Poses, permettant au gérant de gérer l'inventaire de matériel et les commandes de location sans système d'authentification.

## Stack Technique

- **Framework** : Symfony 6.4
- **Langage** : PHP 8.1+
- **Base de données** : SQLite (par défaut)
- **ORM** : Doctrine
- **Templates** : Twig
- **CSS Framework** : Bootstrap 5.3
- **Icons** : Bootstrap Icons

## Fonctionnalités

### Gestion du Matériel
- ✅ Ajouter du matériel (nom, quantité, prix)
- ✅ Modifier un matériel existant
- ✅ Visualiser la liste complète du matériel
- ✅ Affichage du statut de stock (en stock, stock faible, rupture)

### Gestion des Commandes
- ✅ Créer une commande de location
- ✅ Sélectionner plusieurs matériels et quantités
- ✅ Calcul automatique du prix total
- ✅ Vérification de la disponibilité du stock
- ✅ Mise à jour automatique des stocks lors de la location
- ✅ Fonction "Rendre la commande" (restitution + suppression)
- ✅ Historique des commandes

### Interface Utilisateur
- ✅ Interface moderne et responsive avec Bootstrap
- ✅ Navigation intuitive
- ✅ Messages flash pour les notifications
- ✅ Gestion d'erreurs personnalisée
- ✅ Tableaux de bord avec statistiques

## Installation

### Prérequis

- PHP 8.1 ou supérieur
- Composer
- Symfony CLI (recommandé)

### Étapes d'installation

1. **Cloner le projet**
   ```bash
   git clone <url-du-repo>
   cd partiel-back-end
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   ```

3. **Configurer la base de données**
   
   Le projet est configuré par défaut avec SQLite. La configuration se trouve dans le fichier `.env` :
   ```
   DATABASE_URL="sqlite:///%kernel.project_dir%/var/data_dev.db"
   ```

4. **Créer la base de données et exécuter les migrations**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Lancer le serveur de développement**
   ```bash
   symfony serve
   ```
   
   Ou avec PHP :
   ```bash
   php -S localhost:8000 -t public/
   ```

6. **Accéder à l'application**
   
   Ouvrez votre navigateur et allez sur : `http://localhost:8000`

## Utilisation

### Page d'accueil
- Vue d'ensemble avec statistiques
- Accès rapide aux fonctionnalités principales
- Aperçu du matériel disponible et des commandes récentes

### Gestion du matériel
1. Aller dans "Matériel" dans le menu
2. Cliquer sur "Ajouter du matériel" pour créer un nouveau matériel
3. Remplir le formulaire (nom, quantité, prix)
4. Le matériel apparaît dans la liste avec son statut de stock

### Création d'une commande
1. Aller dans "Commandes" puis "Nouvelle commande"
2. Saisir le nom du client
3. Ajouter les matériels souhaités avec leurs quantités
4. Le système vérifie automatiquement la disponibilité
5. La commande est créée et les stocks sont mis à jour

### Restitution d'une commande
1. Dans la liste des commandes, cliquer sur "Rendre" pour une commande
2. Confirmer l'action
3. Les matériels sont remis en stock et la commande est supprimée

## Structure du Projet

```
src/
├── Controller/          # Contrôleurs Symfony
│   ├── HomeController.php
│   ├── MaterielController.php
│   ├── CommandeController.php
│   └── ErrorController.php
├── Entity/              # Entités Doctrine
│   ├── Materiel.php
│   ├── Commande.php
│   └── CommandeMateriel.php
├── Form/                # Formulaires Symfony
│   ├── MaterielType.php
│   ├── CommandeType.php
│   └── CommandeMaterielType.php
└── Repository/          # Repositories Doctrine
    ├── MaterielRepository.php
    ├── CommandeRepository.php
    └── CommandeMaterielRepository.php

templates/
├── base.html.twig       # Template de base
├── home/                # Templates page d'accueil
├── materiel/            # Templates gestion matériel
├── commande/            # Templates gestion commandes
└── bundles/             # Templates d'erreur

migrations/              # Migrations de base de données
```

## Modèle de Données

### Entité Materiel
- `id` : Identifiant unique
- `nom` : Nom du matériel
- `quantiteDisponible` : Stock disponible
- `prixLocation` : Prix de location unitaire

### Entité Commande
- `id` : Identifiant unique
- `nomClient` : Nom du client
- `prixTotal` : Prix total de la commande
- `dateCreation` : Date et heure de création

### Entité CommandeMateriel (Pivot)
- `id` : Identifiant unique
- `commande` : Référence vers la commande
- `materiel` : Référence vers le matériel
- `quantite` : Quantité louée
- `prixUnitaire` : Prix unitaire au moment de la commande

## Fonctionnalités Techniques

### Validation
- Validation côté serveur avec les contraintes Symfony
- Vérification de stock avant création de commande
- Messages d'erreur explicites

### Gestion des Erreurs
- Pages d'erreur personnalisées (404, 500, etc.)
- Messages flash pour les notifications utilisateur
- Gestion des exceptions dans les contrôleurs

### Sécurité
- Protection CSRF sur les formulaires
- Validation des données d'entrée
- Pas d'authentification requise (selon cahier des charges)

## Développement

### Commandes utiles

```bash
# Créer une nouvelle migration
php bin/console make:migration

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Vider le cache
php bin/console cache:clear

# Créer un contrôleur
php bin/console make:controller

# Créer une entité
php bin/console make:entity
```

### Tests

Pour ajouter des données de test :

```bash
# Créer des fixtures (optionnel)
composer require --dev doctrine/doctrine-fixtures-bundle
php bin/console make:fixtures
php bin/console doctrine:fixtures:load
```

## Déploiement

### Production

1. Configurer les variables d'environnement dans `.env.local`
2. Optimiser l'autoloader : `composer install --no-dev --optimize-autoloader`
3. Vider le cache : `php bin/console cache:clear --env=prod`
4. Exécuter les migrations : `php bin/console doctrine:migrations:migrate --env=prod`

### Base de données alternative

Pour utiliser MySQL/PostgreSQL, modifier le `DATABASE_URL` dans `.env` :

```bash
# MySQL
DATABASE_URL="mysql://user:password@127.0.0.1:3306/partiel_back_end?serverVersion=8.0.32&charset=utf8mb4"

# PostgreSQL
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/partiel_back_end?serverVersion=16&charset=utf8"
```

## Support

Pour toute question ou problème :
1. Vérifier les logs dans `var/log/`
2. Consulter la documentation Symfony : https://symfony.com/doc
3. Vérifier la configuration de la base de données

## Licence

Projet académique - Partiel de développement back-end
