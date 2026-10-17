# News Aggregator API

A RESTful Laravel API for a news aggregator service that pulls articles from multiple sources (New York Times, TheNewsAPI, NewsAPI) and exposes endpoints for a front-end application to consume.

## Tech Stack

- PHP 8.x, Laravel
- MySQL
- Docker (nginx + php-fpm)
- Laravel queues & scheduler, Scribe (API docs)

## Prerequisites

- Docker

## Installation

```sh
git clone https://github.com/andreattamatheus/newsAggregatorApi
cd newsAggregatorApi
```

Copy `.env.example` to `.env` in the project root, and `src/.env.example` to `src/.env` — both should share the same DB values:

```
DB_CONNECTION=mysql
DB_HOST=api-db
DB_PORT=3306
DB_DATABASE=YOUR_DATABASE
DB_USERNAME=YOUR_USERNAME
DB_PASSWORD=YOUR_PASSWORD
```

### News source integration

Add API keys for the sources you want to use:

```
NEWS_API_KEY=your_newsapi_key
NEWS_API_URL=https://newsapi.org/v2/everything

NEW_YORK_TIMES_API_KEY=your_nyt_key
NEW_YORK_TIMES_API_URL=https://api.nytimes.com/svc/search/v2/articlesearch.json

THE_NEWS_API_KEY=your_thenewsapi_key
THE_NEWS_API_URL=https://api.thenewsapi.com/v1/news/top
```

### Run

```sh
docker compose up -d
docker exec -it api bash
```

Inside the container:

```sh
composer install --no-interaction && php artisan optimize
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap
chmod -R 777 /var/www/html/resources
php artisan key:generate
php artisan migrate:fresh --seed
```

Seeding creates two users: `backoffice@yopmail.com` and `admin@yopmail.com`, both with password `123123123`.

## Background Jobs

```sh
php artisan schedule:list                    # article-fetch job runs hourly
php artisan app:fetch-articles-from-apis      # fetch articles manually
php artisan queue:listen                      # process queued jobs
```

## Quality & Tests

```sh
./vendor/bin/pint
./vendor/bin/phpstan analyse
php artisan test
```

## Authentication

Requests are authenticated with a Bearer token:

```
Authorization: Bearer {your_token}
```

## API Docs

Generate interactive API docs with Scribe:

```sh
php artisan scribe:generate
```

Docs are then available at `src/public/docs/index.html`.

## Challenge

Take-home challenge for a Backend Laravel Developer position: a RESTful API for a news aggregator that pulls articles from several sources and serves them to a frontend.

- [x] **User authentication** — register, login, logout (token invalidation) and password reset
- [x] **Article management** — paginated listing; search by keyword, date range, category and source; single article by ID; rate limiting
- [x] **User preferences** — set and get preferred sources, categories and authors; personalized feed filtered by them
- [x] **Data aggregation** — fetch from at least 3 news APIs on a schedule, store title/content/author/source/category/publish date, handle and log failures with retries
- [x] **API docs** — Swagger/OpenAPI
- [x] **Tests** — unit and feature tests for endpoints and core logic
- [x] **Docker** — `docker-compose.yml` for the dev environment

## Contact

Matheus Andreatta — [@andreattamatheus](https://github.com/andreattamatheus)
