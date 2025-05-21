<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contact_number = $_POST['contact_number'];

    // Basic validation (you should add more robust validation)
    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($contact_number)) {
        redirect('signup.php?error=All fields are required');
    }

    // Check if an admin already exists
    $admin_check_sql = "SELECT COUNT(*) FROM users WHERE role = 'admin'";
    $admin_check_result = $conn->query($admin_check_sql);
    $admin_count = $admin_check_result->fetch_row()[0];

    // Determine the role
    $role = ($admin_count == 0) ? 'admin' : 'customer';

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, contact_number, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $username, $email, $hashed_password, $contact_number, $role);

    if ($stmt->execute()) {
        // Redirect to login page after successful signup
        redirect('login.php');
    } else {
        redirect('signup.php?error=Error creating account: ' . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect to signup page if accessed directly
    redirect('signup.php');
}
?>