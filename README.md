# 🚀 Test Technique Laravel

Ce projet est une API développée avec **Laravel 11**, dans le cadre d'un test technique pour un poste back-end senior.  
Elle met en œuvre les bonnes pratiques de structuration, typage, validation, sécurité et qualité de code.

---

## 🧱 Fonctionnalités principales

- Authentification sécurisée via **Laravel Sanctum**
- Gestion des entités :
  - **Administrateur** : seul type d'utilisateur authentifié
  - **Profil** : CRUD restreint aux administrateurs
  - **Commentaire** : un commentaire par administrateur et par profil
- Upload de fichier image pour les profils
- Endpoints publics et privés clairement séparés
- Accès public uniquement aux profils actifs, avec restriction de champs

---

## ✅ Prérequis

- Docker
- Docker Compose
- Git

---

## 🐳 Installation avec Docker

Le projet utilise Docker pour garantir un environnement de développement cohérent et facile à mettre en place.

### 1. Cloner le repository
```bash
git clone [votre-repo]
cd [votre-repo]
```

### 2. Configuration de l'environnement
```bash
cp .env.example .env
```

Assurez-vous que votre fichier `.env` contient les bonnes configurations pour PostgreSQL :
```env
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=laravel_test
DB_USERNAME=laravel
DB_PASSWORD=secret
```

### 3. Lancer l'environnement Docker
```bash
# Construire et démarrer les conteneurs
docker-compose up -d

# Installer les dépendances
docker-compose exec app composer install

# Générer la clé d'application
docker-compose exec app php artisan key:generate

# Exécuter les migrations
docker-compose exec app php artisan migrate

# Charger les données de test
docker-compose exec app php artisan db:seed
```

L'application est maintenant accessible à l'adresse : http://localhost:8000

### 🛠 Stack Technique

- PHP 8.2
- Laravel 11
- PostgreSQL 15
- Nginx dernière version stable
- Docker & Docker Compose

### 📦 Services Docker

- **app** : Application PHP/Laravel
- **nginx** : Serveur web
- **db** : Base de données PostgreSQL

### 🔧 Commandes Docker utiles

```bash
# Voir l'état des conteneurs
docker-compose ps

# Voir les logs
docker-compose logs

# Logs d'un service spécifique
docker-compose logs app
docker-compose logs db
docker-compose logs nginx

# Exécuter des commandes Artisan
docker-compose exec app php artisan [commande]

# Accéder au shell PHP
docker-compose exec app bash

# Arrêter l'environnement
docker-compose down

# Arrêter et supprimer les volumes
docker-compose down -v
```

## 📡 Endpoints

### 🔓 Public
- `GET /api/profils` : liste des profils actifs uniquement (le champ statut est masqué)

### 🔒 Authentifiés
- `POST /api/login` : connexion administrateur (retourne un token Sanctum)
- `POST /api/profils` : création d'un profil (avec image)
- `PUT /api/profils/{id}` : mise à jour d'un profil
- `DELETE /api/profils/{id}` : suppression d'un profil
- `POST /api/commentaires` : ajout d'un commentaire unique à un profil

## 📚 Documentation API

[![Run in Postman](https://run.pstmn.io/button.svg)](https://www.postman.com/workspace/Personal-Workspace~36238254-d24c-4c35-a285-f076a53b2d9b/collection/38215188-40a0e712-9237-4d0d-a904-63923a632ba7?action=share&creator=38215188&active-environment=38215188-6be0bf19-8f28-4887-aba7-b410310a7a9d)

Une collection Postman est disponible pour tester facilement tous les endpoints de l'API.

## 🧪 Tests

```bash
# Exécuter les tests avec Docker
docker-compose exec app php artisan test
```

Inclut :
- Tests unitaires
- Tests de validation
- Tests des règles de sécurité
- Tests de logique métier

## 🧰 Qualité du code

Formatage : PHP-CS-Fixer
```bash
docker-compose exec app ./vendor/bin/php-cs-fixer fix
```

Analyse statique : PHPStan
```bash
docker-compose exec app ./vendor/bin/phpstan analyse
```

- Séparation métier / contrôleur via Services & FormRequests
- Types PHP 8+ et validation forte
