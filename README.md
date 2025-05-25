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

- PHP >= 8.2
- Composer
- MySQL ou PostgreSQL
- Laravel 11

---

## 🔧 Installation

```bash
git clone <url-du-repo>
cd nom-du-projet
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## 🔐 Authentification

L'authentification API repose sur Laravel Sanctum.

Un administrateur peut obtenir un token d'accès via un endpoint sécurisé.

Toutes les routes sensibles sont protégées par le middleware `auth:sanctum`.

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
php artisan test
```

Inclut :
- Tests unitaires
- Tests de validation
- Tests des règles de sécurité
- Tests de logique métier

## 🧰 Qualité du code

Formatage : PHP-CS-Fixer
```bash
./vendor/bin/php-cs-fixer fix
```

Analyse statique : PHPStan
```bash
./vendor/bin/phpstan analyse
```

- Séparation métier / contrôleur via Services & FormRequests
- Types PHP 8+ et validation forte

## Installation avec Docker

Ce projet utilise Docker pour garantir un environnement de développement cohérent et facile à mettre en place.

### Prérequis
- Docker
- Docker Compose

### Installation

1. Cloner le repository
```bash
git clone [votre-repo]
cd [votre-repo]
```

2. Copier le fichier d'environnement
```bash
cp .env.example .env
```

3. Lancer les conteneurs Docker
```bash
docker-compose up -d
```

4. Installer les dépendances et configurer Laravel
```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
```

L'application est maintenant accessible à l'adresse : http://localhost:8000

### Informations techniques

- PHP 8.2
- MySQL 8.0
- Nginx dernière version stable
- Composer 2

### Arrêter l'environnement
```bash
docker-compose down
```
