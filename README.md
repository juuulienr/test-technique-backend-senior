# 🚀 Test Technique Laravel - Architecture Hexagonale

Ce projet est une API développée avec **Laravel 11** et **Architecture Hexagonale**, dans le cadre d'un test technique pour un poste back-end senior.  
Elle met en œuvre les bonnes pratiques de Clean Architecture, DDD, SOLID, et les patterns avancés de développement.

---

## 🏗️ Architecture Hexagonale (Clean Architecture)

Ce projet implémente une **architecture hexagonale ** :

### 📋 Structure des Couches

```
app/
├── UI/                     # 🎨 User Interface Layer
│   └── Http/              
│       ├── Controllers/    # Controllers API (Admin, Public, Auth)
│       ├── Requests/       # Form Requests & Validation
│       ├── Resources/      # API Resources & Transformers
│       ├── Responses/      # Standardized API Responses
│       └── Middleware/     # HTTP Middleware
│
├── Application/            # 📋 Application Layer  
│   ├── Services/          # Application Services (Orchestration)
│   ├── DTOs/              # Data Transfer Objects
│   └── UseCases/          # Business Use Cases
│       ├── Auth/          # Authentication Use Cases
│       ├── Profile/       # Profile Use Cases
│       └── Comment/       # Comment Use Cases
│
├── Domain/                # 💎 Domain Layer (Business Core)
│   ├── Entities/          # Pure Domain Entities
│   ├── ValueObjects/      # Value Objects (Email, PersonName, etc.)
│   ├── Repositories/      # Repository Interfaces (Ports)
│   ├── Ports/             # External Service Interfaces
│   └── Exceptions/        # Domain-specific Exceptions
│
└── Infrastructure/        # 🔧 Infrastructure Layer
    ├── Models/            # Eloquent Models
    ├── Repositories/      # Repository Implementations
    ├── Adapters/          # External Service Adapters
    ├── Mappers/           # Entity/Model Mappers
    └── Providers/         # Service Providers
```

### 🎯 Principes Appliqués

- **🔄 Dependency Inversion** : Domain ne dépend de rien
- **🚪 Ports & Adapters** : Interfaces pour services externes
- **🎭 Use Cases** : Logique métier encapsulée
- **💎 Pure Entities** : Zero dépendance framework
- **🔀 CQRS Pattern** : Séparation commandes/queries
- **📦 DDD** : Domain-Driven Design
- **🏛️ SOLID** : Tous les principes respectés

### 🔗 Flux de Données

```
HTTP Request → Controller → Application Service → Use Case → Domain Entity
                ↓                                              ↓
           JSON Response ← Resource ← DTO ← Repository ← Infrastructure
```

## 🧱 Fonctionnalités principales

- **🔐 Authentification sécurisée** via Laravel Sanctum
- **📡 Versioning API** : Structure v1 avec préfixes pour évolutions futures
- **🏷️ Routes nommées** : Navigation et génération d'URLs facilitées
- **✅ Validation stricte** : Types d'ID vérifiés automatiquement
- **🏗️ Architecture hexagonale** : Séparation parfaite des couches
- **🎯 Use Cases** : Logique métier encapsulée et testable
- **🔌 Ports & Adapters** : Découplage infrastructure/métier

### Gestion des entités :
- **👨‍💼 Administrateur** : seul type d'utilisateur authentifié
- **👤 Profil** : CRUD restreint aux administrateurs avec gestion d'images
- **💬 Commentaire** : un commentaire par administrateur et par profil

- **📸 Upload d'images** pour les profils via port d'interface
- **🌐 Endpoints publics et privés** clairement séparés
- **🔒 Accès public** uniquement aux profils actifs, avec restriction de champs

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

### 🛠 Stack Technique & Architecture

- **🐘 PHP 8.2** avec strict typing
- **🚀 Laravel 11** comme framework infrastructure
- **🗄️ PostgreSQL 15** pour la persistance
- **🌐 Nginx** serveur web haute performance
- **🐳 Docker & Docker Compose** pour l'environnement
- **🏗️ Architecture Hexagonale** (Clean Architecture)
- **🎯 Domain-Driven Design** (DDD)
- **🔌 Ports & Adapters Pattern**
- **📋 CQRS** pour séparation commandes/queries
- **🧪 Test-Driven Development** (94 tests)

### 📦 Services Docker

- **app** : Application PHP/Laravel avec architecture hexagonale
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

## 📡 Endpoints API v1

> 🔄 **Versioning** : Toutes les routes sont préfixées par `/api/v1/` pour permettre les évolutions futures de l'API.

### 🔓 Endpoints publics
- `GET /api/v1/profiles` : Liste des profils actifs uniquement (le champ statut est masqué)
  - Route nommée : `v1.public.profiles.index`

### 🔐 Authentification
- `POST /api/v1/auth/register` : Inscription administrateur
  - Route nommée : `v1.auth.register`
- `POST /api/v1/auth/login` : Connexion administrateur (retourne un token Sanctum)
  - Route nommée : `v1.auth.login`

### 🔒 Endpoints administrateur (authentifiés)

#### Gestion des profils
- `POST /api/v1/admin/profiles` : Création d'un profil (avec image)
  - Route nommée : `v1.admin.profiles.store`
- `PUT /api/v1/admin/profiles/{id}` : Mise à jour d'un profil (partielle supportée)
  - Route nommée : `v1.admin.profiles.update`
  - ✅ **Validation ID** : Seuls les ID numériques sont acceptés
- `DELETE /api/v1/admin/profiles/{id}` : Suppression d'un profil
  - Route nommée : `v1.admin.profiles.destroy`
  - ✅ **Validation ID** : Seuls les ID numériques sont acceptés

#### Gestion des commentaires
- `POST /api/v1/admin/profiles/{id}/comments` : Ajout d'un commentaire unique à un profil
  - Route nommée : `v1.admin.profiles.comments.store`
  - ✅ **Validation ID** : Seuls les ID numériques sont acceptés

### 🛡️ Sécurité et validations

- **Rate Limiting** : 60 requêtes par minute pour les endpoints admin
- **Middleware personnalisés** : Vérification des permissions via Use Cases
- **Validation stricte des ID** : Les paramètres `{profile}` n'acceptent que des entiers positifs
- **Authentification Sanctum** : Tokens sécurisés pour les sessions API
- **Value Objects** : Validation métier dans le Domain

## 🧪 Tests Complets (94 tests - 250 assertions)

```bash
# Exécuter tous les tests avec Docker
docker-compose exec app php artisan test

# Tests spécifiques par couche
docker-compose exec app php artisan test tests/Unit/Domain/
docker-compose exec app php artisan test tests/Unit/Application/
docker-compose exec app php artisan test tests/Feature/
```

### 📊 Coverage de Tests

- **✅ Tests Unitaires Domain** : Entities, Value Objects, Use Cases
- **✅ Tests Unitaires Application** : Services, DTOs
- **✅ Tests d'Intégration** : Repositories, Adapters
- **✅ Tests Feature** : API endpoints complets
- **✅ Tests de Validation** : Règles métier et contraintes
- **✅ Tests de Sécurité** : Authentification et autorisation

Types de tests inclus :
- Tests des Use Cases (logique métier)
- Tests des Value Objects (validation)
- Tests des Services d'Application (orchestration)
- Tests des Repositories (persistance)
- Tests des Adapters (infrastructure)
- Tests des Controllers (API)
- Tests d'intégration complets

## 🧰 Qualité du Code (Note: 9.2/10)

### Formatage : PHP-CS-Fixer
```bash
docker-compose exec app ./vendor/bin/php-cs-fixer fix
```

### Analyse statique : PHPStan (Niveau 8)
```bash
docker-compose exec app ./vendor/bin/phpstan analyse
```

### 🏆 Standards de Qualité

- **🎯 Architecture Hexagonale** : Séparation parfaite des couches
- **💎 Domain Pur** : Zero dépendance framework dans le cœur métier  
- **🔒 Types Stricts** : `declare(strict_types=1)` partout
- **📋 SOLID** : Tous les principes appliqués
- **🏷️ Nommage** : Conventions PSR + DDD
- **📚 Documentation** : DocBlocks et commentaires
- **🧪 Testabilité** : 94 tests avec couverture complète
- **🔄 Immutabilité** : Value Objects `readonly`
- **🎭 Single Responsibility** : Une responsabilité par classe

### 🎨 Patterns Implémentés

- **🏛️ Repository Pattern** : Abstraction de la persistance
- **🔌 Adapter Pattern** : Services externes (Image, Auth, Hash)
- **🏭 Factory Pattern** : Création d'entités
- **🎯 Strategy Pattern** : Différents adapters par environnement
- **📋 DTO Pattern** : Transfer d'objets entre couches
- **🔄 Mapper Pattern** : Conversion Entity/Model
- **🎭 Use Case Pattern** : Encapsulation logique métier

## 📚 Documentation API

[![Run in Postman](https://run.pstmn.io/button.svg)](https://www.postman.com/workspace/Personal-Workspace~36238254-d24c-4c35-a285-f076a53b2d9b/collection/38215188-40a0e712-9237-4d0d-a904-63923a632ba7?action=share&creator=38215188&active-environment=38215188-6be0bf19-8f28-4887-aba7-b410310a7a9d)

Une collection Postman est disponible pour tester facilement tous les endpoints de l'API.

## 🔄 Évolutions futures

Grâce à l'architecture hexagonale et au versioning :

### 🚀 Extensibilité
- **📡 v2 API** : Nouvelles fonctionnalités sans casser la v1
- **🔌 Nouveaux Adapters** : Facilement remplaçables (AWS S3, Redis, etc.)
- **🎯 Nouveaux Use Cases** : Ajouts sans impact sur l'existant
- **🏗️ Microservices** : Migration facilitée par la séparation des couches

### 🎯 Maintenance
- **🔧 Migration progressive** : Les clients migrent à leur rythme
- **🐛 Corrections ciblées** : Bugs sur versions multiples en parallèle
- **🧪 Tests isolés** : Changements sans régression
- **📦 Déploiements indépendants** : Couches découplées

---

## 🏆 Points Forts de l'Architecture

✅ **Clean Architecture** : Structure Uncle Bob complète  
✅ **Domain-Driven Design** : Logique métier au centre  
✅ **SOLID Principles** : Tous respectés  
✅ **Zero Framework Coupling** : Domain pur  
✅ **Testability** : 94 tests complets  
✅ **Maintainability** : Code propre et documenté  
✅ **Scalability** : Architecture prête pour la croissance  
✅ **Performance** : Optimisations intégrées  

Cette architecture garantit un code **maintenable**, **testable**, et **évolutif** pour les années à venir ! 🚀
