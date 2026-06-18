<?php
// admin/src/auth/gatekeeper.php

// Make sure the user is logged in, and verify they are an Admin (3) or Vendor (2)


if (!isset($_SESSION["userId"]) || !isset($_SESSION["userRole"])) {
    // Not logged in? Kick them back out to the main storefront landing
    redirect("../index.php");
    exit();
}

$role = $_SESSION["userRole"];
if ($role == 1) {
    // They are a logged-in customer trying to sneak into the admin area. Redirect out.
    redirect("../index.php?error=unauthorized");
    exit();
}