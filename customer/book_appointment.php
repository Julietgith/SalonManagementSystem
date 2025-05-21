<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
ensure_customer();

$services = get_services($conn);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = filter_input(INPUT_POST, 'service_id', FILTER_VALIDATE_INT);
    $appointment_date = filter_input(INPUT_POST, 'appointment_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $appointment_time = filter_input(INPUT_POST, 'appointment_time', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (empty($service_id) || $service_id === false || empty($appointment_date) || empty($appointment_time)) {
        $error = "Please select a service, date, and time.";
    } else {
        // Fetch the price of the selected service
        $stmt_price = $conn->prepare("SELECT price FROM services WHERE service_id = ?");
        if ($stmt_price === false) {
            $error = "Error preparing statement (price): " . $conn->error;
        } else {
            $stmt_price->bind_param("i", $service_id);
            if ($stmt_price->execute()) {
                $result_price = $stmt_price->get_result();
                if ($result_price && $result_price->num_rows > 0) {
                    $row_price = $result_price->fetch_assoc();
                    $service_price = $row_price['price'];
                    $upfront_percentage = 0.30;
                    $payment_amount = $service_price * $upfront_percentage;

                    if (!isset($_SESSION['user_id'])) {
                        $error = "Error: User not logged in. \$_SESSION['user_id'] is not set.";
                    } else {
                        $customer_id = $_SESSION['user_id'];

                        // *** BEGIN TRANSACTION ***
                        $conn->begin_transaction();

                        // 1.  Insert into appointments, using customer_id and get the new appointment ID
                        $stmt_book = $conn->prepare("INSERT INTO appointments ( user_id, service_id, appointment_date, appointment_time, payment_amount, status) VALUES ( ?, ?, ?, ?,?, 'pending')");
                        if ($stmt_book === false) {
                            $error = "Error preparing statement (booking): " . $conn->error;
                            $conn->rollback();
                        } else {
$stmt_book->bind_param("iissi",  $customer_id, $service_id, $appointment_date, $appointment_time, $payment_amount);
                            if ($stmt_book->execute()) {
                                $appointment_id = $conn->insert_id; // Get the ID of the newly inserted appointment!
                                $conn->commit();
                                // 2.  Redirect to payment page.  Pass the appointment ID and payment amount.
                                header("Location: payment.php?appointment_id=$appointment_id&amount=$payment_amount");
                                exit();

                            } else {
                                $error = "Error booking appointment: " . $stmt_book->error;
                                $conn->rollback();
                            }
                            $stmt_book->close();
                        }
                    }
                } else {
                    $error = "Selected service not found.";
                }
            } else {
                $error = "Error executing price query: " . $stmt_price->error;
            }
            $stmt_price->close();
        }
    }
}

// Pre-select service if ID is provided in the URL
$selected_service_id = isset($_GET['service_id']) && is_numeric($_GET['service_id']) ? (int) $_GET['service_id'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
    <?php include 'customer_sidebar.php'; ?>

    <div class="content-area">
        <div class="content-navbar">
            <h4>Appointment</h4>
        </div>

        <div class="container mt-4">
            <h2>Book an Appointment</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <form action="book_appointment.php" method="POST">
                <div class="mb-3">
                    <label for="service_id" class="form-label">Select Service</label>
                    <select class="form-select" id="service_id" name="service_id" required>
                        <option value="">-- Select a Service --</option>
                        <?php if (!empty($services)): ?>
                            <?php foreach ($services as $service): ?>
                                <option value="<?php echo $service['service_id']; ?>" <?php if ($service['service_id'] == $selected_service_id) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($service['name']); ?>
                                    (<?php echo htmlspecialchars($service['price']); ?> -
                                    <?php echo htmlspecialchars($service['duration']); ?> min)
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No services available.</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="appointment_date" class="form-label">Date</label>
                    <input type="text" class="form-control flatpickr" id="appointment_date" name="appointment_date"
                           required>
                </div>
                <div class="mb-3">
                    <label for="appointment_time" class="form-label">Time</label>
                    <input type="text" class="form-control timepicker" id="appointment_time" name="appointment_time"
                           required>
                </div>
                <button type="submit" class="btn btn-primary">Book Appointment</button>
                <a href="services.php" class="btn btn-secondary">Cancel</a>
            </form>
            <p class="mt-3">Note: A 30% upfront payment is required to confirm your booking.</p>
            
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr(".flatpickr", {
            enableTime: false,
            dateFormat: "Y-m-d",
            minDate: "today"
        });
        flatpickr(".timepicker", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            minuteIncrement: 15
        });
    </script>
</body>
</html>