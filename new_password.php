<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['reset_code_verified']) || $_SESSION['reset_code_verified'] !== true) {
    $_SESSION['error'] = "Password reset process incomplete. Please restart.";
    header('Location: forgot-password.php');
    exit();
}

if (!isset($_SESSION['reset_email'])) {
    $_SESSION['error'] = "No email associated with this reset request. Please restart.";
    header('Location: forgot-password.php');
    exit();
}

$resetEmail = $_SESSION['reset_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (strlen($newPassword) < 6) {
        $_SESSION['error'] = "New password must be at least 6 characters long.";
    } elseif ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = "New password and confirm password do not match.";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_code = NULL WHERE email = ?");
        $stmt->execute([$hashedPassword, $resetEmail]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = "Your password has been reset successfully. You can now log in with your new password.";
            unset($_SESSION['reset_code_verified']);
            unset($_SESSION['reset_email']);
            header('Location: login.php');
            exit();
        } else {
            $_SESSION['error'] = "Error updating password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon Booking - New Password</title>
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
                    <h2 class="text-2xl font-semibold">Set New Password</h2>
                    <p class="text-sm opacity-80 mt-2">Enter your new password</p>
                </div>
                <div class="p-6">
                    <form action="new_password.php" method="POST" class="space-y-4">
                        <div>
                            <label for="new_password" class="block text-gray-700 text-sm font-bold mb-2">New
                                Password</label>
                            <div class="relative">
                                <input type="password" id="new_password" name="new_password" required
                                    class="shadow appearance-none border rounded-md w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-300 bg-gray-50 pr-10">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer"
                                    id="new-password-toggle">
                                    <i class="fas fa-eye text-gray-500 hover:text-purple-500"></i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-2">Confirm New
                                Password</label>
                            <div class="relative">
                                <input type="password" id="confirm_password" name="confirm_password" required
                                    class="shadow appearance-none border rounded-md w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-300 bg-gray-50 pr-10">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer"
                                    id="confirm-password-toggle">
                                    <i class="fas fa-eye text-gray-500 hover:text-purple-500"></i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <button type="submit" href="login.php"
                                class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-3 px-6 rounded-md focus:outline-none focus:shadow-outline w-full transition duration-300 ease-in-out">
                                Reset Password
                            </button>
                        </div>
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
    <script>
        const newPasswordInput = document.getElementById('new_password');
        const newPasswordToggle = document.getElementById('new-password-toggle');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const confirmPasswordToggle = document.getElementById('confirm-password-toggle');

        newPasswordToggle.addEventListener('click', function () {
            if (newPasswordInput.type === 'password') {
                newPasswordInput.type = 'text';
                this.querySelector('i').classList.remove('fa-eye');
                this.querySelector('i').classList.add('fa-eye-slash');
            } else {
                newPasswordInput.type = 'password';
                this.querySelector('i').classList.remove('fa-eye-slash');
                this.querySelector('i').classList.add('fa-eye');
            }
        });

        confirmPasswordToggle.addEventListener('click', function () {
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                this.querySelector('i').classList.remove('fa-eye');
                this.querySelector('i').classList.add('fa-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                this.querySelector('i').classList.remove('fa-eye-slash');
                this.querySelector('i').classList.add('fa-eye');
            }
        });
    </script>
</body>

</html>