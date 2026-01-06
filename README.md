# Restaurant Reservation System

A Symfony-based restaurant reservation management system.

## Features
- User registration and authentication
- Table management
- Reservation booking system
- Admin dashboard

## Installation
1. Clone the repository
2. Run composer install
3. Run php bin/console importmap:install
4. Configure .env.local with your database
5. Run migrations: php bin/console doctrine:migrations:migrate

## Technologies
- Symfony 6.4
- Doctrine ORM
- Twig templates
- AssetMapper
