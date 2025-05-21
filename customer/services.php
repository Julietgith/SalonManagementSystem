<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
ensure_customer();

$services = get_services($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services</title>
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

        .service-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.8); /* Optional: Semi-transparent background for the table */
        }

        .service-table th,
        .service-table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        .service-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .service-table .btn-primary {
            /* Style for the button in the table */
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

            .service-table {
                overflow-x: auto; /* Enable horizontal scrolling for small screens */
            }
        }
    </style>
</head>

<body>
    <?php include 'customer_sidebar.php'; ?>

    <div class="content-area">
        <div class="content-navbar">
            <h4>Services</h4>
            
        </div>

        <div class="container mt-4">
            <h2>Our Services</h2>
            <?php if (!empty($services)): ?>
                <table class="service-table">
                    <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $service): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($service['name']); ?></td>
                                <td><?php echo htmlspecialchars($service['description']); ?></td>
                                <td><?php echo htmlspecialchars($service['price']); ?></td>
                                <td><?php echo htmlspecialchars($service['duration']); ?> minutes</td>
                                <td>
                                    <a href="book_appointment.php?service_id=<?php echo $service['service_id']; ?>"
                                       class="btn btn-primary btn-sm">Book Now</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No services available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/script.js"></script>
</body>

</html>