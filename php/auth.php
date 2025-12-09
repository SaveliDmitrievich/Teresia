<?php
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

function requireAdmin() {
    if (!isset($_SESSION['user_id']) || !isAdmin()) {
        header("Location: /admin/index.php?error=unauthorized");
        exit();
    }
}
?>