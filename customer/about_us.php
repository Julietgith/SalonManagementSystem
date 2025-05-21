<?php

session_start();

// Define page title
$page_title = "About Us";

// Optional: Authentication and role-based access
// If you have different "About Us" content for different roles, you can use this.
// Otherwise, you can remove or simplify it.
/*
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_role = get_user_role($conn, $user_id); // You'll need to create this function
} else {
    $user_role = 'guest'; // Or 'public', or however you want to handle non-logged-in users
}
*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
          integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: pink;
            margin: 0; /* Remove default body margin */
        }

        .sidebar {
            background-color: #343a40; /* Dark background for sidebar */
            color: white;
            padding-top: 20px;
            width: 220px; /* Adjust width as needed */
            position: fixed; /* Fixed sidebar */
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 100; /* Ensure it's above other content */
            overflow-y: auto; /* Enable scrolling if content overflows */
        }

        .sidebar .sidebar-brand {
            padding: 1rem 1.5rem;
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.25rem;
            font-weight: bold;
            color: white;
            display: block;
            text-decoration: none;
        }

        .sidebar .nav-item a {
            padding: 0.8rem 1.5rem;
            display: block;
            text-decoration: none;
            color: white;
            transition: background-color 0.15s ease-in-out;
        }

        .sidebar .nav-item a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-item.active a {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: bold;
        }

        .content-area {
            flex-grow: 1;
            padding: 0; /* Remove default padding from content area */
            margin-left: 220px; /* Adjust to match sidebar width */
            display: flex;
            flex-direction: column;
        }

        /* Style for the top navbar in the content area */
        .content-navbar {
            background-color: #343a40; /* Same as sidebar background */
            color: white;
            padding: 1.5rem 20px; /* Add horizontal padding to keep content inside */
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.1rem;
            width: 100%; /* Make the navbar span the full width of the content area */
            position: sticky; /* Try sticky positioning */
            top: 0; /* Stick it to the top */
            margin-top: 0; /* Ensure no top margin */
            margin-left: 0; /* Ensure no left margin */
            margin-right: 0; /* Ensure no right margin */
            z-index: 99; /* Ensure it's below the fixed sidebar if overlapping */
        }

        .content-navbar h4 {
            margin-bottom: 0;
            font-size: 1.5rem;
        }

        .content-navbar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
            padding: 0.5rem 0;
        }

        .content-navbar a:hover {
            color: rgba(255, 255, 255, 0.7);
        }

        .container {
            padding: 20px;
            margin-top: 20px; /* Push container below sticky navbar */
        }

        .about-us-content {
            padding: 2rem;
            text-align: center;
            background-color: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .about-us-content h2 {
            color: #007bff;
            margin-bottom: 1rem;
        }

        .about-us-content p {
            margin-bottom: 1rem;
            color: #555;
            line-height: 1.7;
        }

        .team-members-container {
            /* New container for team members */
            display: flex;
            /* Use flexbox for horizontal layout */
            flex-wrap: wrap;
            /* Allow wrapping to multiple rows if needed */
            justify-content: center;
            /* Center the items horizontally */
            gap: 2rem;
            /* Add some gap between team members */
            margin-bottom: 2rem;
        }

        .team-member {
            text-align: center;
            /* width: 200px;  Removed fixed width, adjust as needed */
        }

        .team-member img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
            border: 5px solid #ddd;
        }

        .team-member h3 {
            font-size: 1.2rem;
            color: #343a40;
            margin-bottom: 0.5rem;
        }

        .team-member p {
            font-size: 0.9rem;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column; /* Stack sidebar and content on smaller screens */
            }
            .sidebar {
                width: 100%;
                position: static;
                border-right: none;
                border-bottom: 1px solid #dee2e6;
            }
            .content-area {
                margin-left: 0;
                padding: 0; /* Remove content area padding */
            }
            .content-navbar {
                padding: 1rem;
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 10px;
                position: static;
                top: auto;
                margin-top: 0;
                margin-left: 0;
                margin-right: 0;
            }
            .content-navbar h4 {
                font-size: 1.3rem;
                margin-bottom: 10px;
            }
            .content-navbar a {
                margin-left: 0;
                margin-top: 10px;
                padding: 0.3rem 0;
            }
            .container {
                padding: 15px;
                margin-top: 15px;
            }
        }
    </style>
</head>

<body>
    <?php
    // Include the appropriate navbar based on user role.
    // You'll need to create these navbar files.
    if (isset($user_role)) {
        if ($user_role == 'admin') {
            include 'admin_sidebar.php';
        } elseif ($user_role == 'customer') {
            include 'customer_sidebar.php'; // Changed to sidebar
        } else {
            include 'public_navbar.php'; // Or a default navbar for guests
        }

    } else {
        include 'customer_sidebar.php'; // Default for non-logged-in users
    }
    ?>

    <div class="content-area">
        <div class="content-navbar">
            <h4>Abouts</h4>
            <div>
                <?php
                // Conditionally display the Dashboard link for admin users
                if (isset($user_role) && $user_role == 'admin') {
                    echo '<a href="admin_dashboard.php">Dashboard</a>';
                }
                ?>
            </div>
        </div>

        <div class="container mt-4">
            <div class="about-us-content">
                <h2>About Us</h2>
                <?php
                // Display different content based on user role, if needed
                //  You can customize this as per your requirements.
                if (isset($user_role)) {
                    if ($user_role == 'admin') {
                        echo "<p>Welcome to the admin section of our About Us page.  Here, you can find information about our salon's management and operations.</p>";
                        echo "<p>Our salon is dedicated to providing the best services. We also value our employees.</p>";
                    } elseif ($user_role == 'customer') {
                        echo "<p>Welcome to our salon, where beauty meets relaxation. We are dedicated to providing you with the best services and experience.</p>";
                        echo "<p>We offer a wide range of services, including haircuts, styling, coloring, facials, massages, and more. We use only the highest quality products to ensure the best results for our clients.</p>";
                    } else {
                        echo "<p>Welcome to our salon! We are passionate about beauty and providing excellent service to everyone.</p>";
                    }
                } else {
                    echo "<p>Welcome to our salon! We are passionate about beauty and providing excellent service to everyone.</p>";
                }
                ?>
                <p>Our mission is to create a welcoming and comfortable atmosphere where you can unwind and enjoy a
                    personalized pampering session. We believe that everyone deserves to feel beautiful and confident, and
                    we're here to help you achieve that.</p>
            </div>

            <div class="team-members-container">
                <div class="team-member">
                    <img src="../images/juliet.jpg" alt="Stylist 1">
                    <h3>Hacker</h3>
                    <p>Juliet Dingnaon</p>
                </div>
                <div class="team-member">
                    <img src="../images/asha.jpg" alt="Stylist 2">
                    <h3>Hustler</h3>
                    <p>Ahsa Causing</p>
                </div>
                <div class="team-member">
                    <img src="../images/marykris.jpg" alt="Stylist 3">
                    <h3>Hipster</h3>
                    <p>Marykris Gaviola</p>
                </div>
            </div>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        //  Any custom JavaScript for the page can go here
    </script>
</body>

</html>