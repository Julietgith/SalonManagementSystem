<?php

require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1️⃣ CAPTCHA Verification
    $recaptchaSecret = $_ENV['RECAPTCHA_SECRET_KEY'];
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
    $captchaSuccess = json_decode($verifyResponse);

    if (!$captchaSuccess->success) {
        $_SESSION['error'] = "Captcha verification failed. Please try again.";
        header('Location: login.php');
        exit();
    }

    // 2️⃣ Retrieve Credentials
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // 3️⃣ Prepared Statement with Error Checking
    $stmt = $conn->prepare("SELECT user_id, password, role FROM users WHERE username = ? OR email = ?");
    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // 4️⃣ Check if User Exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // 5️⃣ Verify Password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            // 6️⃣ Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: admin/admin_dashboard.php');
                exit();
            } else {
                header('Location: customer/customer_dashboard.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "Incorrect password.";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "User not found.";
        header('Location: login.php');
        exit();
    }

    // 7️⃣ Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    header('Location: login.php');
    exit();
}
