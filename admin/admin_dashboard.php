<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php'; // Ensure admin access


if (!is_logged_in() || !is_admin()) {
    redirect('../index.php');
}

// Fetch dashboard statistics
$services_count = $conn->query("SELECT COUNT(*) FROM services")->fetch_row()[0];
$appointments_count = $conn->query("SELECT COUNT(*) FROM appointments")->fetch_row()[0];
$done_appointments_count = $conn->query("SELECT COUNT(*) FROM appointments WHERE status = 'completed'")->fetch_row()[0];
$pending_appointments_count = $conn->query("SELECT COUNT(*) FROM appointments WHERE status = 'pending'")->fetch_row()[0];
$confirmed_appointments_count = $conn->query("SELECT COUNT(*) FROM appointments WHERE status = 'confirmed'")->fetch_row()[0];

// ... fetch other necessary data

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
          xintegrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: pink;
            margin: 0;
        }

        .sidebar {
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            width: 220px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 100;
            overflow-y: auto;
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
            padding: 0;
            margin-left: 220px;
            display: flex;
            flex-direction: column;
        }

        .content-navbar {
            background-color: #343a40;
            color: white;
            padding: 1.5rem 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.1rem;
            width: 100%;
            position: sticky;
            top: 0;
            margin-top: 0;
            margin-left: 0;
            margin-right: 0;
            z-index: 99;
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
            margin-top: 20px;
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
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .dashboard-card h5 {
            margin-bottom: 10px;
            font-size: 1.4rem;
        }

        .dashboard-card p {
            font-size: 2em;
            font-weight: bold;
        }

        .dashboard-card .card-body{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        @media (max-width: 992px) {
            .dashboard-card {
                max-width: 45%;
            }
            .dashboard-row {
                justify-content: space-around;
            }
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                position: static;
                border-right: none;
                border-bottom: 1px solid #dee2e6;
            }

            .content-area {
                margin-left: 0;
                padding: 0;
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
    <?php include 'admin_sidebar.php'; ?>

    <div class="content-area">
        <div class="content-navbar">
            <h4>Admin Dashboard</h4>
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
