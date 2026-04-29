<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div id="notification-area"></div>

    <nav class="navbar glass" id="main-nav" style="display: none;">
        <div class="nav-brand">
            📚 LibraryApp
        </div>
        <div class="nav-links">
            <a href="dashboard.php" id="nav-dashboard">Dashboard</a>
            <a href="my_borrows.php" id="nav-borrows">My Borrows</a>
            <a href="authors.php" id="nav-authors">Authors</a>
            <a href="manage_books.php" id="nav-books">Manage Books</a>
            <a href="#" id="logout-btn" class="btn btn-danger btn-sm" style="color: white;">Logout</a>
        </div>
    </nav>
    
    <div class="container">
