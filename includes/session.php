<?php
/**
 * Session & Authentication Handler
 * Referral Management System (RMS)
 */

session_start();

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']);
}

// Redirect to login if not authenticated
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /FinalProject_1/modules/auth/login.php");
        exit();
    }
}

// Check user role
function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

// Require specific role
function requireRole($roles) {
    if (!isLoggedIn()) {
        header("Location: /FinalProject_1/modules/auth/login.php");
        exit();
    }
    
    if (!in_array($_SESSION['role'], (array)$roles)) {
        die("Access Denied. You do not have permission to view this page.");
    }
}

// Get current user info
function getCurrentUser() {
    return array(
        'id' => $_SESSION['user_id'] ?? null,
        'name' => $_SESSION['full_name'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'role' => $_SESSION['role'] ?? null,
        'facility_id' => $_SESSION['facility_id'] ?? null
    );
}

// Logout user
function logout() {
    session_destroy();
    header("Location: /FinalProject_1/modules/auth/login.php");
    exit();
}

?>
