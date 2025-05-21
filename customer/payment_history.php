<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
ensure_customer();

$user_id = $_SESSION['user_id'];
$sql = "SELECT p.payment_id, p.amount, p.payment_date, p.payment_method,
            a.appointment_id, s.name AS service_name
        FROM payments p
        JOIN appointments a ON p.appointment_id = a.appointment_id
        JOIN services s ON a.service_id = s.service_id
        WHERE a.user_id = ?
        ORDER BY p.payment_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$payments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
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

        .payment-history-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: transparent !important; /* Make the table background transparent */
        }

        .payment-history-table th,
        .payment-history-table td {
            padding: 10px;
            border: none !important; /* Remove table cell borders */
            text-align: left;
            color: #000; /* Ensure text is readable */
        }

        .payment-history-table thead th {
            /* You might want a subtle background for the header for better readability */
            /* background-color: rgba(255, 255, 255, 0.3); */
            font-weight: bold;
            color: #000;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1); /* Add a subtle bottom border to the header */
        }

        .payment-history-table tbody tr {
            /* Optional: Add a subtle bottom border between rows for separation */
            /* border-bottom: 1px solid rgba(0, 0, 0, 0.05); */
        }

        .payment-history-table tbody tr:last-child {
            /* Remove bottom border from the last row if you added one to tbody tr */
            /* border-bottom: none; */
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

            .payment-history-table {
                overflow-x: auto; /* Enable horizontal scrolling for small screens */
            }
        }
    </style>
</head>

<body>
    <?php include 'customer_sidebar.php'; ?>

    <div class="content-area">
        <div class="content-navbar">
            <h4>Payment</h4>
        </div>

        <div class="container mt-4">
            <h2>Payment History</h2>
            <?php if (!empty($payments)): ?>
                <table class="payment-history-table">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Appointment ID</th>
                            <th>Service</th>
                            <th>Amount Paid</th>
                            <th>Payment Date</th>
                            <th>Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?php echo $payment['payment_id']; ?></td>
                                <td><?php echo $payment['appointment_id']; ?></td>
                                <td><?php echo htmlspecialchars($payment['service_name']); ?></td>
                                <td>$<?php echo htmlspecialchars($payment['amount']); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($payment['payment_date']))); ?></td>
                                <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No payment history available.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/script.js"></script>
</body>

</html>