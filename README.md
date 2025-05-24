# Test Technique Laravel

Ce projet est une API Laravel 11 développée dans le cadre d'un test technique.  
Elle gère des entités **administrateur**, **profil** et **commentaire**, avec authentification via Sanctum.

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

## ✅ Prérequis

- PHP >= 8.2
- Composer
- MySQL ou PostgreSQL
- Laravel 11

## ⚙️ Authentification

Le projet utilise Laravel Sanctum pour l'authentification API.
Un administrateur peut générer un token via un endpoint sécurisé.

## 🧪 Lancer les tests

```bash
php artisan test
```

## 📦 Qualité du code

- Formatage : PHP-CS-Fixer (`./vendor/bin/php-cs-fixer fix`)
- Analyse statique : PHPStan (`./vendor/bin/phpstan analyse`)
