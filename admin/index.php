<?php
    require_once __DIR__ . '/../config/auth.php';
    secureSessionStart(); // secure session handling
    require_once __DIR__ . '/../config/db.php';

    $pdo = Database::connect();

    // Redirect if already logged in
    if (!empty($_SESSION['user'])) {
        header("Location: dashboard.php");
        exit;
    }

    if (!isset($_SESSION['attempts'])) $_SESSION['attempts'] = 0;
    $error = "";

    // Handle login
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check CSRF token
        if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
            die("❌ Invalid CSRF token.");
        }

        if ($_SESSION['attempts'] >= 5) {
            $error = "Too many failed attempts. Try again later.";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
            $stmt->execute([$_POST['username']]);
            $user = $stmt->fetch();

            if ($user && password_verify($_POST['password'], $user['password'])) {
                session_regenerate_id(true); // prevent fixation
                $_SESSION['user'] = [
                    "id" => $user['id'],
                    "username" => $user['username'],
                    "role" => $user['role']
                ];
                $_SESSION['attempts'] = 0;
                header("Location: dashboard.php");
                exit;
            } else {
                $_SESSION['attempts']++;
                $error = "❌ Invalid username or password!";
            }
        }
    }

    // Generate CSRF token
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../admin/assets/css/admin.css">
</head>
<body>
<div class="login-box">
    <h2>Century Group Admin</h2>
    <?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
    <form method="POST">
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
    <?php if ($_SESSION['attempts'] > 0): ?>
        <p style="color:orange;">Failed attempts: <?= $_SESSION['attempts'] ?></p>
    <?php endif; ?>
</div>
</body>
</html>
