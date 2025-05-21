<?php

require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_SESSION['error']) && $_SESSION['error'] === "Invalid Code. Please try again!") {
    unset($_SESSION['error']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header('Location: forgot-password.php');
        exit();
    }

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $reset_code = rand(100000, 999999);

        $update = $pdo->prepare("UPDATE users SET reset_code = ? WHERE email = ?");
        $update->execute([$reset_code, $email]);

        $_SESSION['email'] = $email;

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'julietangcoddinganon@gmail.com';
            $mail->Password = 'hsdn einz fafl tdrq';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('julietangcoddinganon@gmail.com', 'Ashley Hair Beauty Salon');
            $mail->addAddress($email, 'Client');

            $mail->isHTML(true);
            $mail->Subject = "Password Reset Code";
            $mail->Body = "<p>Hello, this is your password reset code: <strong>{$reset_code}</strong></p>";
            $mail->AltBody = "Hello, use the code below to reset your password:\n\n{$reset_code}\n\n";

            $mail->send();

            $_SESSION["email_sent"] = true;
            $_SESSION['success'] = "A verification code has been sent to your email. Please enter the code below.";
            header('Location: send_code.php');
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            header('Location: forgot_password.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "No user found with that email address.";
        header('Location: forgot_password.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon Booking - Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-purple-400 to-pink-300 flex items-center justify-center min-h-screen py-10">
    <div class="container mx-auto px-4">
        <div class="flex justify-center">
            <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-6 text-center rounded-t-xl">
                    <div class="flex justify-center mb-4">
                        <img src="images/logo.jpg" alt="Company Logo" class="w-24 h-24 rounded-full object-cover">
                    </div>
                    <h2 class="text-2xl font-semibold">Forgot Password?</h2>
                    <p class="text-sm opacity-80 mt-2">Enter your email to reset your password</p>
                </div>
                <div class="p-6">
                    <form action="" method="POST" class="space-y-4">
                        <div>
                            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                            <input type="email" id="email" name="email" required
                                class="shadow appearance-none border rounded-md w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-300 bg-gray-50"
                                placeholder="Your email address">
                        </div>
                        <div>
                            <button type="submit"
                                class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-3 px-6 rounded-md focus:outline-none focus:shadow-outline w-full transition duration-300 ease-in-out">
                                Send Reset Code
                            </button>
                        </div>
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mt-4"
                                role="alert">
                                <strong class="font-bold">Success!</strong>
                                <span class="block sm:inline"><?php echo htmlspecialchars($_SESSION['success']); ?></span>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative mt-4"
                                role="alert">
                                <strong class="font-bold">Error:</strong>
                                <span class="block sm:inline"><?php echo htmlspecialchars($_SESSION['error']); ?></span>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                    </form>
                    <div class="mt-6 text-center">
                        <a href="login.php"
                            class="inline-block text-sm font-semibold text-purple-500 hover:text-purple-700 focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                            Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>