<?php
session_start();

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function is_admin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function is_customer()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'customer';
}

function redirect($url)
{
    header("Location: " . $url);
    exit();
}

// Example function to fetch services
function get_services($conn)
{
    $sql = "SELECT * FROM services";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// ... more common functions will go here
?>