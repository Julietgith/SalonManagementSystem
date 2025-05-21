<?php
// Check if the user is logged in and is a customer
if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'customer'):
    ?>
    <style>
        .sidebar {
            background-color: gray; /* Dark background for sidebar */
            color: white;
            padding-top: 20px;
            width: 270px; /* Adjust width as needed */
            position: fixed; /* Fixed sidebar */
            top: 0;
            left: 0;
            height: 100vh; /* Full viewport height */
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
            display: flex; /* Align image and text in brand */
            align-items: center;
            justify-content: center; /* Center content horizontally */
            text-decoration: none; /* Remove link underline */
        }

        .sidebar .sidebar-brand img {
            width: 60px; /* Medium size for logo */
            height: 60px;
            border-radius: 50%; /* Make logo circular */
            margin-right: 0.75rem; /* Space between logo and text */
        }

        .sidebar .nav-item a {
            padding: 0.8rem 1.5rem;
            display: block;
            text-decoration: none;
            color: white;
            transition: background-color 0.15s ease-in-out;
            display: flex; /* Use flexbox to align image and text */
            align-items: center; /* Vertically align items */
            font-size: 1.25rem; /* Adjust this value to change the font size of the sidebar items */
        }

        .sidebar .nav-item a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-item.active a {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: bold;
        }

        .sidebar .nav-item a img {
            margin-right: 0.75rem; /* Space between icon and text */
            width: 24px; /* Medium icon width */
            height: 24px; /* Make height equal to width for a circle */
            border-radius: 50%; /* Make the image a circle */
        }

        .content-area {
            margin-left: 250px; /* Adjust to match sidebar width */
            padding: 20px;
            flex-grow: 1; /* Take remaining width */
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: static;
                height: auto;
                overflow-y: visible;
                margin-bottom: 20px;
                display: flex; /* For horizontal layout on small screens */
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding: 10px;
            }
            .sidebar-brand {
                text-align: left !important;
                margin-bottom: 0;
            }
            .sidebar .sidebar-brand img {
                width: 40px;
                height: 40px;
                margin-right: 0.5rem;
            }
            .sidebar .nav {
                flex-direction: row;
                width: 100%;
                justify-content: space-around;
                margin-top: 10px;
            }
            .sidebar .nav-item {
                margin: 0;
            }
            .sidebar .nav-item a {
                padding: 0.5rem;
                text-align: center;
                flex-direction: column;
                align-items: center;
                margin-right: 0;
                font-size: 1.25rem; /* Adjust font size for smaller screens */
            }
            .sidebar .nav-item a img {
                margin-right: 0;
                margin-bottom: 0.3rem;
                width: 30px;
                height: 20px;
            }
            .content-area {
                margin-left: 0;
                padding-top: 0; /* Adjust padding for top navbar */
            }
        }
    </style>

    <div class="sidebar">
       <a class="sidebar-brand" href="customer_dashboard.php" style="display: flex; align-items: center; justify-content: center; text-decoration: none; color: white;">
    <img src="../images/logo.jpg" alt="User Icon" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 0.75rem;">
    Customer Portal
</a>
        <hr class="bg-secondary">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="customer_dashboard.php">
                    <img src="../images/d.png" alt="Dashboard Icon"> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="services.php">
                    <img src="../images/s.png" alt="Services Icon"> Services
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="book_appointment.php">
                    <img src="../images/bk.png" alt="Book Appointment Icon"> Book Appointment
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view_appointments.php">
                    <img src="../images/ap.png" alt="My Appointments Icon"> My Appointments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="payment_history.php">
                    <img src="../images/py.png" alt="Payment History Icon"> Payment History
                </a>
            </li>
            </li>
             <li class="nav-item">
                <a class="nav-link" href="feedback.php">
                    <img src="../images/feed.jpg" alt="feedbacks icon"> Feedback
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about_us.php">
                    <img src="../images/abt.png" alt="About Us Icon"> About Us
                </a>
            
            <li class="nav-item mt-4">
                <a class="nav-link" href="../logout.php">
                    <img src="../images/lg.png" alt="Logout Icon"> Logout
                </a>
            </li>
        </ul>
    </div>

    <?php
else:
    echo '<p class="text-center mt-3"><a href="../login.php">Login as Customer</a></p>';
endif;
?>