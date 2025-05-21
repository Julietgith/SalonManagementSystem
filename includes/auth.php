<?php
// This file can contain specific authentication checks for different roles
// For example, to ensure only admin can access admin pages:
function ensure_admin()
{
    if (!is_logged_in() || !is_admin()) {
        redirect('../index.php?error=Access denied');
    }
}
// Call this function at the top of admin-only pages.

function ensure_customer()
{
    if (!is_logged_in() || !is_customer()) {
        redirect('../index.php?error=Access denied');
    }
}
// Call this function at the top of customer-only pages.
?>