# CMS API (Laravel + MySQL)

This is a Content Management System (CMS) API built with Laravel and Mysql, featuring:

-   AI-powered slug and summary generation using LLM (Gemini)
-   Role-based access (Admin / Author)
-   Filterable article listings
-   Full RESTful CRUD for articles & categories
-   Token-based authentication (Laravel Sanctum)

## Features

### User Authentication

-   Login API with email/password.
-   Logout API to revoke token.
-   Seeder create:
    -   Admin user
    -   Author user

### Categories (Admin Only)

-   CRUD for categories
-   Category assignment to articles (many-to-many)

### Articles (Content Management)

-   CRUD operations on articles
-   Slug & Summary generated asynchronously via Jobs and GeminiService

### Article Listing & Filtering

-   Filter articles by:
    -   category_id
    -   status
    -   from / to date range

### Role-Based Access Control

-   Admin: Full access to all articles and categories
-   Author: Can manage only their own articles

---

## Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/cms-api.git
cd cms-api
```

### 2. Install Dependency

composer install

### 3. Setup Environment

cp .env.example .env
php artisan key:generate

### 4. Run Migrations and Seeders

php artisan migrate --seed

### 5. Queue Worker (for Slug & Summary Jobs)

<!-- Make sure QUEUE_CONNECTION=database is set in .env -->

php artisan queue:work

### 6. Serve the application

php artisan serve
