<?php

class UserController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Register user
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars($_POST['username']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $stmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $password]);

            echo "User registered successfully.";
        } else {
            echo '<form method="POST">
                    Username: <input name="username" required><br>
                    Password: <input type="password" name="password" required><br>
                    <button type="submit">Register</button>
                  </form>';
        }
    }

    // Login user
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user['username'];
                echo "Welcome, " . htmlspecialchars($user['username']);
            } else {
                echo "Invalid credentials.";
            }
        } else {
            echo '<form method="POST">
                    Username: <input name="username" required><br>
                    Password: <input type="password" name="password" required><br>
                    <button type="submit">Login</button>
                  </form>';
        }
    }

    // Logout user
    public function logout() {
        session_destroy();
        echo "You have been logged out.";
    }
}
