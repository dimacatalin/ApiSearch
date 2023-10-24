# API search command

## Description

API search command with different calls and responses

## Getting started

```
create.env file from .env.example and fill: API_TOKEN and BASE_URL
docker-compose up -d --build
docker-compose exec php bash
composer install
php artisan migrate
```

## Created

- Console/Commands/Search.php
- Models/User.php
- Models/Email.php
- Utils/Provider.php
- Utils/FirstProvider.php
- Utils/SecondProvider.php
- Utils/ThirdProvider.php

## Usage

```php artisan search name company linkedin```
