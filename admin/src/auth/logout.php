<?php
// 1. Load the database/config bootstrap which starts the session globally
require_once '../../../config/database.php';

// 2. Unset all session superglobal variables in-memory
$_SESSION = array();

// 3. Clear the session cookie from the user's browser completely
// if (ini_get("session.use_cookies")) {
//     $params = session_get_cookie_params();
//     setcookie(
//         session_name(), 
//         '', 
//         time() - 42000, // Back-date expiration time to make the browser delete it instantly
//         $params["path"], 
//         $params["domain"], 
//         $params["secure"], 
//         $params["httponly"]
//     );
// }

// 4. Wipe out the session data payload registry on the web server
session_destroy();

// 5. Use your global APP_URL base path to safely route them back to the login gateway
// This passes the 'logged_out' flag to trigger your beautiful green success alert banner!
redirect("../../../admin/login.php?error=logged_out");
exit;