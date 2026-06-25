<?php
// admin/src/auth/check.php

// 1. Always ensure sessions are started at the absolute top of an auth file
// Step up three directories to reach your new global classes folder
require_once '../../../classes/Database.php';
require_once '../../../classes/Utils.php';
Utils::startSecureSession();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Grab the inputs (PDO handles SQL security, but trimming whitespace is still good practice)
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); 

    try {
        // Get your PDO instance (adjust this to match how your new Database.php exposes PDO)
        // For example: $pdo = Database::getConnection(); or simply using a global $pdo variable
        $pdo = Database::connect();
        // 2. Prepare the SQL blueprint with a safe named placeholder (:email)
        $query = "SELECT id, role_id, password FROM users WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($query);
        
        // 3. Execute the statement by passing the data securely bound to the placeholder
        $stmt->execute([':email' => $email]);
        
        // 4. Fetch the resulting row as an associative array
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 5. Check if a user was found and verify their credentials
        // (If you are using plain text passwords for now, keep this. If using password_hash, use password_verify)
        if ($user && $password === $user['password']) {
            // 🛡️ Erases the old session ID file on the server and issues a brand new random ID token to the browser
            session_regenerate_id(true);
            // Save authorization identity properties to active session arrays (exclude the password!)
            $_SESSION["user"] = [
                'id' => $user['id'],
                'email' => $user['email'],
                // 'first_name' => $user['first_name'],
                // 'last_name' => $user['last_name'],
                'role_id' => $user['role_id']
            ];
            
            // Traffic Control Routing Layer
            if ($user['role_id'] == 3 || $user['role_id'] == 2) {
                // Authorized Dashboard operators (Admin / Vendor)
                Utils::redirect(ADMIN_URL . "index.php"); 
            } else {
                // Normal consumer client identity -> route to general public catalog
                Utils::redirect(BASE_URL . "index.php"); 
            }
        } else {
            // Credential matching failure (bad email or bad password)
            Utils::redirect(ADMIN_URL . "login.php?error=failed_login");
        }

    } catch (PDOException $e) {
        // If anything breaks with the query, catch the error instead of crashing silently
        die("Database Authentication Error: " . $e->getMessage());
    }

} else {
    // Direct URL access protection: Safely bounce back to the login page using the class wrapper
    redirect(ADMIN_URL . "login.php");
}