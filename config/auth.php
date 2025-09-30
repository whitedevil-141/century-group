<?php
// Start secure session
function secureSessionStart() {
    // Only allow cookies, no URL-based session IDs
    ini_set('session.use_only_cookies', 1);
    ini_set('session.use_strict_mode', 1);

    // Configure cookie params
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => 0,                 // expires when browser closes
        'path'     => $cookieParams['path'],
        'domain'   => $cookieParams['domain'],
        'secure'   => isset($_SERVER['HTTPS']), // true if using HTTPS
        'httponly' => true,              // JS can’t access session cookie
        'samesite' => 'Strict'           // block CSRF via cross-site requests
    ]);

    session_start();
    session_regenerate_id(true); // prevent fixation
}

// Protect pages
function requireLogin() {
    if (empty($_SESSION['user'])) {
        header("Location: index.php");
        exit;
    }

    // auto logout after 30 mins of inactivity
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
        logout();
    }
    $_SESSION['LAST_ACTIVITY'] = time();
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
