<?php
// classes/Gatekeeper.php

class Gatekeeper {
    /**
     * Enforces role-based access control using the session user array.
     *
     * @param array $allowedRoleIds Array of permitted role IDs.
     * @param string $loginRedirect Path to the login screen for unauthenticated users.
     */
    public static function allow(array $allowedRoleIds, string $loginRedirect = 'login.php') {
        // 1. Ensure the session is running
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Authentication Check: Is the user array set in the session?
        if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
            header("Location: " . $loginRedirect);
            exit();
        }

        // 3. Extract the role_id safely from the session user array
        $userRoleId = $_SESSION['user']['role_id'] ?? null;

        // 4. Authorization Check: Does their role match the allowed list?
        if (!in_array($userRoleId, $allowedRoleIds)) {
            
            // Explicitly set the server response header to 403 Forbidden
            http_response_code(403);
            
            // Since error-403.html is in the same directory as your pages,
            // we can look for it right next to the running script.
            if (file_exists('error-403.html')) {
                require_once 'error-403.html';
            } else {
                // Fallback layout if the file is missing or renamed
                echo "<h1>403 Forbidden</h1>";
                echo "<p>Your client does not have permission to get this page from the server.</p>";
            }
            
            // Stop execution completely so nothing below leaks out
            exit();
        }
    }
}