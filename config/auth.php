<?php
// Start secure session
function secureSessionStart() {
    ini_set('session.use_only_cookies', 1);
    ini_set('session.use_strict_mode', 1);

    $cookieParams = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => $cookieParams['path'],
        'domain'   => $cookieParams['domain'],
        'secure'   => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Strict'
    ]);

    session_start();
    session_regenerate_id(true);
}

/**
 * Require login & role in one call
 * 
 * @param array $roles Allowed roles (empty = any logged-in user)
 */
function requireAuth($roles = []) {
    if (empty($_SESSION['user'])) {
        header("Location: index.php");
        exit;
    }

    // Auto logout after 30 mins idle
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
        logout();
    }
    $_SESSION['LAST_ACTIVITY'] = time();

    // Role check (if specified)
    if (!empty($roles)) {
        $userRole = $_SESSION['user']['role'];
        if (!in_array($userRole, (array)$roles)) {
            header("Location: 403.php");
            exit;
        }
    }
}

// Logout
function logout() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
    header("Location: index.php");
    exit;
}
