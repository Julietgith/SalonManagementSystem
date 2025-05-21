<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php'; // Ensure customer access

if (!is_logged_in() || !is_customer()) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];

// Fetch the total number of services
$stmt_services = $pdo->prepare("SELECT COUNT(*) FROM services");
$stmt_services->execute();
$services_count = $stmt_services->fetchColumn();

// Fetch the total number of appointments for the current customer
$stmt_total_appointments = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE user_id = ?");
$stmt_total_appointments->execute([$user_id]);
$appointments_count = $stmt_total_appointments->fetchColumn();

// Fetch the number of completed appointments for the current customer
$stmt_done_appointments = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE user_id = ? AND status = 'completed'");
$stmt_done_appointments->execute([$user_id]);
$done_appointments_count = $stmt_done_appointments->fetchColumn();

// Fetch the number of pending appointments for the current customer
$stmt_pending_appointments = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE user_id = ? AND status = 'pending'");
$stmt_pending_appointments->execute([$user_id]);
$pending_appointments_count = $stmt_pending_appointments->fetchColumn();

// Fetch the number of confirmed appointments for the current customer
$stmt_confirmed_appointments = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE user_id = ? AND status = 'confirmed'");
$stmt_confirmed_appointments->execute([$user_id]);
$confirmed_appointments_count = $stmt_confirmed_appointments->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/style.css">
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
            background-color: rgba(255, 255, 255, 0.2);
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

        .dashboard-card {
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
            margin-bottom: 20px;
            width: 100%;
            max-width: 300px;
        }

        .dashboard-row {
            display: flex;
            justify-content: center; /* Center the cards horizontally */
            gap: 20px; /* Space between the cards */
            flex-wrap: wrap; /* Allow cards to wrap to the next line on smaller screens */
        }

        .dashboard-card h5 {
            margin-bottom: 10px;
            font-size: 1.4rem;
        }

        .dashboard-card p {
            font-size: 2em; /* Adjust as needed */
            font-weight: bold;
        }

        .dashboard-card .card-body{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
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
             .dashboard-card {
                max-width: 100%;
            }
            .dashboard-row{
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <?php include 'customer_sidebar.php'; ?>

    <div class="content-area">
        <div class="content-navbar">
            <h4>Customer Dashboard</h4>
            
        </div>

        <div class="container mt-4">
            <h2 class="text-center mb-4">STATUS</h2>

            <div class="dashboard-row">
                <div class="card bg-primary text-white dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Services Offered</h5>
                        <p class="card-text"><?php echo htmlspecialchars($services_count); ?></p>
                    </div>
                </div>
                <div class="card bg-info text-white dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Total Appointments</h5>
                        <p class="card-text"><?php echo htmlspecialchars($appointments_count); ?></p>
                    </div>
                </div>
                <div class="card bg-success text-white dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Completed Appointments</h5>
                        <p class="card-text"><?php echo htmlspecialchars($done_appointments_count); ?></p>
                    </div>
                </div>
                <div class="card bg-warning text-dark dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Pending Appointments</h5>
                        <p class="card-text"><?php echo htmlspecialchars($pending_appointments_count); ?></p>
                    </div>
                </div>
                <div class="card bg-secondary text-white dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Confirmed Appointments</h5>
                        <p class="card-text"><?php echo htmlspecialchars($confirmed_appointments_count); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/script.js"></script>
</body>

</html>
