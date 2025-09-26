
# Century Group

Century Group is a PHP-based web application with a secure backend and a modern frontend (HTML, CSS, JavaScript).  
This project follows best practices for **project structure, security, and maintainability**.

---

## 🚀 Features
- PHP backend with **PDO** and **prepared statements** for secure database access.
- Environment-based configuration using **`.env`** and [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv).
- User authentication with **password hashing** (`password_hash` / `password_verify`).
- Session management and logout handling.
- Basic routing via `index.php`.
- Structured project layout:

```
project-dir/
    ├── public/ # Public files (index.php, css, js, assets)
    ├── src/ # PHP controllers, models, views
    ├── config/ # Config files (database, app config)
    ├── vendor/ # Composer dependencies
    └── .env # Environment variables
```
---

## 🛠️ Requirements
- PHP 8.0 or higher
- Composer (dependency manager)
- MySQL / MariaDB (or any PDO-supported database)
- Web server (Apache, Nginx, or PHP’s built-in server)

---

## ⚙️ Installation

1. **Clone the repository**:

```
git clone https://github.com/whitedevil-141/century-group.git
cd century-group
```
2. **Install dependencies**:
```
composer install
```
3. **Create `.env` file**:
```
DB_HOST=localhost
DB_NAME=myapp
DB_USER=myuser
DB_PASS=supersecret

```
3. **Set up the database:**:
```
CREATE DATABASE myapp;
USE myapp;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

```
3. **Run the project (using PHP’s built-in server)**:
```
php -S localhost:8000 -t public

```

## 🔐 Security Practices

- All sensitive data is stored in .env (never commit this file).
- Passwords are hashed using PHP’s built-in functions.
- SQL Injection protection via prepared statements.
- Output escaping with htmlspecialchars() to prevent XSS.
- Session security with session_regenerate_id() after login.
- CSRF tokens recommended for all forms.