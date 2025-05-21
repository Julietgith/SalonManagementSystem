<?php
session_start();
require_once __DIR__ . '\vendor\autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$siteKey = $_ENV['RECAPTCHA_SITE_KEY'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon Booking - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            /* Prevent scrolling */
        }
    </style>
</head>

<body class="bg-gradient-to-br from-purple-400 to-pink-300 flex items-center justify-center h-screen">
    <div class="container mx-auto px-4">
        <div class="flex justify-center">
            <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-4 text-center rounded-t-xl">
                    <div class="flex justify-center mb-3">
                        <img src="images/logo.jpg" alt="Company Logo" class="w-24 h-24 rounded-full object-cover">
                    </div>
                    <h2 class="text-2xl font-semibold">Welcome</h2>
                    <p class="text-sm opacity-80 mt-2">Login to your account to continue</p>
                </div>

                <div class="p-6">
                    <!-- Login Form -->
                    <form method="POST" action="login_process.php">
                        <div>
                            <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username or
                                Email</label>
                            <input type="text" id="username" name="username" required
                                class="shadow appearance-none border rounded-md w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-300 bg-gray-50">
                        </div>

                        <div class="mt-4 relative">
                            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                            <input type="password" id="password" name="password" required
                                class="shadow appearance-none border rounded-md w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-300 bg-gray-50">
                            <button id="password-toggle" type="button" class="absolute right-3 top-10 text-gray-500">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>

                        <br>

                        <!-- Google reCAPTCHA -->
                        <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars($siteKey) ?>"></div>

                        <!-- Login Button -->
                        <button type="submit"
                            class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-3 px-6 rounded-md focus:outline-none focus:shadow-outline w-full transition duration-300 ease-in-out mt-6">
                            Log In
                        </button>
                    </form>

                    <!-- Display error message -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative mt-4"
                            role="alert">
                            <strong class="font-bold">Error:</strong>
                            <span class="block sm:inline"><?= $_SESSION['error']; ?></span>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <!-- Create Account & Forgot Password Links -->
                    <div class="mt-6 text-center">
                        <a href="signup.php"
                            class="inline-block text-sm font-semibold text-purple-500 hover:text-purple-700 focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                            Create an Account
                        </a>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="forgot_password.php"
                            class="text-purple-500 hover:text-purple-700 text-sm font-semibold focus:outline-none focus:shadow-outline">
                            Forgot Password?
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('password-toggle');

        passwordToggle.addEventListener('click', function () {
            const icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>

    <script src="https://www.google.com/recaptcha/api.js"></script>
</body>

</html>