<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';


if (!isset($_GET['appointment_id']) || !isset($_GET['amount'])) {
    //  Handle the error.  The user should not be able to access this page directly without an appointment.
    error_log("payment.php accessed without appointment_id or amount.  Invalid access.");
    redirect('customer_dashboard.php'); // Redirect to a safe page
    exit;
}

$appointment_id = filter_input(INPUT_GET, 'appointment_id', FILTER_VALIDATE_INT);
$amount = filter_input(INPUT_GET, 'amount', FILTER_VALIDATE_FLOAT);

if ($appointment_id === false || $amount === false || $amount <= 0) {
    error_log("payment.php accessed with invalid appointment_id or amount.  Invalid values.");
    redirect('customer_dashboard.php'); // Redirect to a safe page
    exit;
}
// Generate QR code data (This is a placeholder - replace with your actual QR code generation)
$qr_data = "Appointment ID: $appointment_id, Amount: $amount";
//  Use a QR code library to generate the actual image.
//  Example (Conceptual using a library):
// include 'phpqrcode/qrlib.php'; //  Include the library
//$qr_image_name = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
//QRcode::png($qr_data, $qr_image_name, QR_ECLEVEL_L, 6); // Size 6 for medium
$qr_image_url = "https://via.placeholder.com/200x200?text=QR+Code";  // Placeholder - replace with your generated URL
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: pink;
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
            padding: 20px;
            margin-left: 220px; /* Adjust to match sidebar width */
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
            }
        }
    </style>
</head>

<body>
    <?php include 'customer_sidebar.php'; ?>

    <div class="content-area">
        <div class="container mt-4">
            <h2>Payment for Appointment #<?php echo $appointment_id; ?></h2>
            <div class="alert alert-info">
                <p><strong>Scan the QR code below to pay P<?php echo number_format($amount, 2); ?>.</strong></p>
                <p>Use your preferred mobile payment app to scan the code.</p>
            </div>

            <div class="text-center">
                <img src="../images/qr.jpg" alt="QR Code for Payment" class="img-thumbnail" style="max-width: 250px; max-height: 250px;">
                <p>Scan this QR code to complete your payment.</p>
            </div>
            <div class="mt-4 alert alert-warning">
                <p><strong>Important:</strong> After payment, please wait for confirmation. Do not close this page until you
                    see a payment confirmation.</p>
            </div>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>