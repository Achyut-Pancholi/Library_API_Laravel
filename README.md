# Library Management API

A complete, production-quality RESTful API for a Library Management System built with Laravel 11.

## Features

- **Authentication**: Secure token-based auth using Laravel Sanctum.
- **Author Management**: CRUD operations for Authors.
- **Book Management**: CRUD operations for Books with an advanced availability scope.
- **Borrowing System**: Borrow and return books, with strict availability checks.
- **Validation**: Centralized Form Requests.
- **Clean Architecture**: API Resources for standardized JSON output and clean controllers.

## Prerequisites

- PHP >= 8.2
- Composer
- SQLite (usually bundled with PHP)

## Setup Instructions

1. **Clone or Extract the Project**

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Environment Setup**
   The `.env` file is already configured to use SQLite for maximum portability. No database server configuration is required!
   ```env
   DB_CONNECTION=sqlite
   ```

4. **Create Database**
   Since SQLite is used, the database file will be automatically created when you run the migrations, or you can manually create an empty file at `database/database.sqlite`.

5. **Run Migrations & Seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```
   This will create all tables and populate the database with:
   - 1 Admin User
   - 10 Fake Authors
   - 30 Fake Books (randomly assigned to authors)

6. **Serve the API**
   ```bash
   php artisan serve
   ```
   The API will be available at `http://127.0.0.1:8000`.

7. **Serve the Frontend**
   Open a **second terminal** window, navigate to the `php_frontend` folder, and use the built-in PHP server:
   ```bash
   cd php_frontend
   php -S 127.0.0.1:8001
   ```
   Access the beautiful glassmorphism frontend at `http://127.0.0.1:8001`.

## Admin Credentials (Seeded)

- **Email**: `admin@example.com`
- **Password**: `password`

## Postman Usage

A Postman collection is included in the project root: `Library_Management_API.postman_collection.json`.

1. Open Postman.
2. Click **Import**.
3. Select `Library_Management_API.postman_collection.json` from the project directory.
4. Set up an environment variable `base_url` to `http://localhost:8000/api` or simply use the pre-configured endpoints.
5. First, use the **Login** endpoint with the admin credentials above.
6. Copy the returned `token`.
7. For all other endpoints, go to the **Authorization** tab, select **Bearer Token**, and paste your token.

## Endpoints Overview

- **Auth**
  - `POST /api/register`
  - `POST /api/login`

- **Authors** (Requires Auth)
  - `GET /api/authors`
  - `POST /api/authors`
  - `GET /api/authors/{id}`
  - `PUT /api/authors/{id}`
  - `DELETE /api/authors/{id}`

- **Books** (Requires Auth)
  - `GET /api/books` (supports `?available=true` and `?search=xyz`)
  - `POST /api/books`
  - `GET /api/books/{id}`
  - `PUT /api/books/{id}`
  - `DELETE /api/books/{id}`

- **Borrows** (Requires Auth)
  - `POST /api/borrows`
  - `GET /api/borrows/my`
  - `PATCH /api/borrows/{id}/return`
