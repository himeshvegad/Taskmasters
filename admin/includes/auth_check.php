<?php
// Prevent direct access - must be included
if (!defined('AUTH_CHECK')) {
    define('AUTH_CHECK', true);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../login.php');
    exit;
}
?>
