<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white py-4 px-6 text-center rounded-t-xl">
                    <div class="flex justify-center mb-3">
                        <img src="images/logo.jpg" alt="Company Logo" class="w-20 h-20 rounded-full object-cover">
                    </div>
                    <h2 class="text-xl font-semibold">Create Account</h2>
                    <p class="text-sm opacity-80 mt-1">Sign up to begin</p>
                </div>
                <div class="p-4">
                    <form action="signup_process.php" method="POST" class="space-y-2">
                        <div>
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-0.5">Fullname</label>
                            <input type="text" id="name" name="name" required
                                class="shadow appearance-none border rounded-md w-full py-1.5 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-300 bg-gray-50 text-sm">
                        </div>
                        <div>
                            <label for="username" class="block text-gray-700 text-sm font-bold mb-0.5">Username</label>
                            <input type="text" id="username" name="username" required
                                class="shadow appearance-none border rounded-md w-full py-1.5 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-300 bg-gray-50 text-sm">
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 text-sm font-bold mb-0.5">Email</label>
                            <input type="email" id="email" name="email" required
                                class="shadow appearance-none border rounded-md w-full py-1.5 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-300 bg-gray-50 text-sm">
                        </div>
                        <div>
                            <label for="password" class="block text-gray-700 text-sm font-bold mb-0.5">Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password" required
                                    class="shadow appearance-none border rounded-md w-full py-1.5 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-300 bg-gray-50 pr-8 text-sm">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 cursor-pointer"
                                    id="password-toggle">
                                    <i class="fas fa-eye text-gray-500 hover:text-purple-500 text-sm"></i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-0.5">Confirm
                                Password</label>
                            <div class="relative">
                                <input type="password" id="confirm_password" name="confirm_password" required
                                    class="shadow appearance-none border rounded-md w-full py-1.5 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-300 bg-gray-50 pr-8 text-sm">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 cursor-pointer"
                                    id="confirm-password-toggle">
                                    <i class="fas fa-eye text-gray-500 hover:text-purple-500 text-sm"></i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <label for="contact_number" class="block text-gray-700 text-sm font-bold mb-0.5">Contact
                                Number</label>
                            <input type="text" id="contact_number" name="contact_number" required
                                class="shadow appearance-none border rounded-md w-full py-1.5 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-purple-300 bg-gray-50 text-sm">
                        </div>
                        <button type="submit"
                            class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline w-full transition duration-300 ease-in-out text-sm">
                            Sign Up
                        </button>
                        <?php if (isset($_GET['error'])): ?>
                            <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded-md relative mt-2 text-sm"
                                role="alert">
                                <strong class="font-bold">Error:</strong>
                                <span class="block sm:inline"><?php echo htmlspecialchars($_GET['error']); ?></span>
                            </div>
                        <?php endif; ?>
                    </form>
                    <div class="mt-3 text-center text-sm">
                        <a href="login.php"
                            class="inline-block font-semibold text-purple-500 hover:text-purple-700 focus:outline-none focus:shadow-outline transition duration-200 ease-in-out">
                            Already have an account? LogIn
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('password-toggle');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const confirmPasswordToggle = document.getElementById('confirm-password-toggle');

        passwordToggle.addEventListener('click', function () {
            togglePasswordVisibility(passwordInput, this);
        });

        confirmPasswordToggle.addEventListener('click', function () {
            togglePasswordVisibility(confirmPasswordInput, this);
        });

        function togglePasswordVisibility(inputElement, toggleElement) {
            if (inputElement.type === 'password') {
                inputElement.type = 'text';
                toggleElement.querySelector('i').classList.remove('fa-eye');
                toggleElement.querySelector('i').classList.add('fa-eye-slash');
            } else {
                inputElement.type = 'password';
                toggleElement.querySelector('i').classList.remove('fa-eye-slash');
                toggleElement.querySelector('i').classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>