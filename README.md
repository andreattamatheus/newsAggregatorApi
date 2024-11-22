# Laravel API Project

## Overview

The challenge is to build a RESTful API for a news aggregator service that pulls articles from various sources and provides endpoints for a front-end application to consume.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [API Endpoints](#api-endpoints)
- [Authentication](#authentication)

## Requirements

- Docker

## Installation

**Clone the Repository**

```bash
git clone https://github.com/andreattamatheus/newsAggregator
```

## Project API

Copy the .env-example and rename it to .env

Inside the src folder, exists the .env file, you must filled the correct info about your DB.
This info must be same as the one in the .env of the root folder.

- DB_CONNECTION=mysql
- DB_HOST=api-db
- DB_PORT=3306
- DB_DATABASE=news_aggregator
- DB_USERNAME=andreatta
- DB_PASSWORD=root

You need to connect to the container running the app:

```
docker exec -it api bash
```

Install Laravel dependencies using Composer - Build and optimize Laravel application files

```bash
composer install --no-interaction && php artisan optimize
```

Set permissions for storage and bootstrap folders in Laravel application directory

```bash
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap
```

Set permissions for the resources folder

```bash
chmod -R 777 /var/www/html/resources
```

Expose port 8000 for the Laravel application

```bash
php artisan key:generate
```

Two users will be created here: backoffice@yopmail.com and admin@yopmail.com. Both have the password _123123123_

```
php artisan migrate:fresh --seed
```

### Pint

```
 ./vendor/bin/pint
```

### PHPStan

```
 ./vendor/bin/phpstan analyse
```

### Test

```
php artisan test
```

### Schedules job

You can check the schedules jobs. The job of fetch runs every hour:

```
php artisan schedule:list
```

If you want to run the command to fetch the content, you can try:

```
php artisan app:fetch-articles-from-apis
```

Then run teh queues:

```
php artisan queue:listen
```

## Authentication

### Generating a Token

You need to generate a token to authenticate API requests. Use Laravel’s built-in authentication features or a package like Passport to manage tokens.

### Making Authenticated Requests

Include the token in the Authorization header of your requests:

Authorization: Bearer {your_token}

```
4|FtA7npeqHV6pA926caMyK62V6KTu0xJaLphzfVUQ3550142d
```

## API Endpoints

### Create user

- URL: /register
- Method: POST
- Headers: Content-Type: multipart/form-data
- Description: Creates a new user

### Login user

- URL: /login
- Method: POST
- Headers: Authorization: Bearer {token}, Content-Type: multipart/form-data
- Description: Login a user

### Store a New Record

- URL: /api/v1/emails
- Method: POST
- Headers: Authorization: Bearer {token}, Content-Type: multipart/form-data
- Description: Creates a new record and parses the raw email content.

### Get Record by ID

- URL: /api/v1/emails/{emailId}
- Method: GET
- Headers: Authorization: Bearer {token}
- Description: Fetches a single record by its ID.

### Update a Record

- URL: /api/v1/emails/{emailId}
- Method: PUT
- Headers: Authorization: Bearer {token}, Content-Type: application/json
- Description: Updates a record by its ID.

### Get All Records

- URL: /api/v1/emails
- Method: GET
- Headers: Authorization: Bearer {token}
- Description: Returns all records, excluding deleted items. Pagination is optional.

### Delete a Record by ID

- URL: /api/v1/emails/{emailId}
- Method: DELETE
- Headers: Authorization: Bearer {token}
- Description: Soft deletes a record by its ID.
