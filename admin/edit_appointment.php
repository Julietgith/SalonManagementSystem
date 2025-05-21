<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
ensure_admin();

$error = '';
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $appointment_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT a.*, u.name AS customer_name, s.name AS service_name
                                 FROM appointments a
                                 JOIN users u ON a.user_id = u.user_id
                                 JOIN services s ON a.service_id = s.service_id
                                 WHERE a.appointment_id = ?");
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointment = $result->fetch_assoc();
    $stmt->close();

    if (!$appointment) {
        redirect('appointments.php?error=Appointment not found');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $status = $_POST['status'];
        $appointment_date = $_POST['appointment_date'];
        $appointment_time = $_POST['appointment_time'];

        $stmt_update = $conn->prepare("UPDATE appointments SET status = ?, appointment_date = ?, appointment_time = ? WHERE appointment_id = ?");
        $stmt_update->bind_param("sssi", $status, $appointment_date, $appointment_time, $appointment_id);

        if ($stmt_update->execute()) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                echo json_encode(['success' => true]);
                exit;
            } else {
                // Log success message
                error_log("Appointment ID $appointment_id updated successfully with status: $status");
                redirect('appointments.php?success=Appointment updated successfully');
            }
        } else {
            // Log error message
            error_log("Error updating appointment ID $appointment_id: " . $stmt_update->error);
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                echo json_encode(['success' => false, 'error' => $stmt_update->error]);
                exit;
            } else {
                $error = "Error updating appointment: " . $stmt_update->error;
            }
        }
        $stmt_update->close();
    }
} else {
    redirect('appointments.php?error=Invalid appointment ID');
}

// Fetch available status options
$status_options = ['pending', 'confirmed', 'completed', 'cancelled'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
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
            margin-left: 220px; /* Adjust to match sidebar width */
            padding: 20px;
            flex-grow: 1; /* Take remaining width */
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column; /* Stack sidebar and content on smaller screens */
            }
            .sidebar {
                width: 100%;
                position: static;
                height: auto;
                overflow-y: visible;
                margin-bottom: 20px;
            }
            .content-area {
                margin-left: 0;
            }
            .sidebar-brand {
                text-align: left !important;
            }
        }
    </style>
</head>

<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content-area">
        <div class="container mt-4">
            <h2>Edit Appointment</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($appointment): ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="customer_name" value="<?php echo htmlspecialchars($appointment['customer_name']); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="service_name" class="form-label">Service Name</label>
                        <input type="text" class="form-control" id="service_name" value="<?php echo htmlspecialchars($appointment['service_name']); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="appointment_date" class="form-label">Appointment Date</label>
                        <input type="date" class="form-control" id="appointment_date" name="appointment_date" value="<?php echo htmlspecialchars($appointment['appointment_date']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="appointment_time" class="form-label">Appointment Time</label>
                        <input type="time" class="form-control" id="appointment_time" name="appointment_time" value="<?php echo htmlspecialchars($appointment['appointment_time']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <?php foreach ($status_options as $option): ?>
                                <option value="<?php echo htmlspecialchars($option); ?>" <?php if ($appointment['status'] === $option) echo 'selected'; ?>><?php echo htmlspecialchars(ucfirst($option)); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Appointment</button>
                    <a href="appointments.php" class="btn btn-secondary">Cancel</a>
                </form>
            <?php else: ?>
                <p>No appointment details found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/script.js"></script>
</body>

</html>