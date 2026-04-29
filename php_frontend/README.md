# Vanilla PHP Frontend for Library API

This is a complete frontend for the Library Management API built using pure HTML, CSS, JavaScript, and PHP.
It does not use any JS frameworks (like React or Vue) or CSS frameworks (like Tailwind or Bootstrap).

## Features
- **Glassmorphism Design:** Modern, premium aesthetic with dark mode and vibrant gradients.
- **Authentication:** Login and Registration using Laravel Sanctum (token stored in localStorage).
- **Dashboard:** View all books and borrow them.
- **My Borrows:** View your active borrows and return books.
- **Manage Authors:** Add and delete authors.
- **Manage Books:** Add and delete books (requires selecting an author).

## How to Run

1. Ensure your Laravel backend API is running on `http://localhost:8000`.
   ```bash
   cd "Library api"
   php artisan serve
   ```

2. Open a new terminal, navigate to this `php_frontend` directory, and start the PHP built-in server on port 8001:
   ```bash
   cd "Library api\php_frontend"
   php -S localhost:8001
   ```

3. Open your browser and go to:
   [http://localhost:8001](http://localhost:8001)

## Architecture
- **PHP:** Used for layout routing (includes header and footer on each page).
- **HTML/CSS:** Modern structure with `flexbox` and `grid`. Styling in `css/style.css`.
- **Vanilla JavaScript:** `fetch()` API calls are centralized in `js/api.js`. DOM manipulation is handled inline on respective pages.
