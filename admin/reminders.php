<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
ensure_admin();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Adjust path if needed

function sendReminderEmail($to, $customer_name, $appointment_date, $appointment_time, $service_name, $reminder_message)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'julietangcoddinganon@gmail.com';
        $mail->Password = 'hsdn einz fafl tdrq'; // Replace with your actual app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('julietangcoddinganon@gmail.com', 'Ashley Hair Beauty Salon');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = "Reminder: Your Upcoming Appointment";
        $mail->Body = "<p>Dear " . htmlspecialchars($customer_name) . ",</p>" .
            "<p>This is a reminder for your appointment on <strong>" . htmlspecialchars($appointment_date) . "</strong> at <strong>" . htmlspecialchars(date('h:i A', strtotime($appointment_time))) . "</strong> for <strong>" . htmlspecialchars($service_name) . "</strong>.</p>" .
            "<p>Reminder message: " . nl2br(htmlspecialchars($reminder_message)) . "</p>" .
            "<p>We look forward to seeing you!</p>" .
            "<p>Sincerely,<br>Ashley Hair Beauty Salon</p>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Reminder email error: " . $mail->ErrorInfo);
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reminder'])) {
    error_log("Delete reminder POST received");
    $reminder_id = $_POST['reminder_id'] ?? '';
    if (!empty($reminder_id)) {
        $stmt_delete = $conn->prepare("DELETE FROM reminders WHERE reminder_id = ?");
        $stmt_delete->bind_param("i", $reminder_id);

        if ($stmt_delete->execute()) {
            $stmt_delete->close();
            header("Location: reminders.php");
            exit();
        } else {
            $message = '<div class="alert alert-danger">Error deleting reminder.</div>';
        }
        $stmt_delete->close();
    }
}

$sql_appointments = "SELECT
                        a.appointment_id,
                        u.name AS customer_name,
                        u.email AS customer_email,
                        u.contact_number AS customer_contact,
                        s.name AS service_name,
                        a.appointment_date,
                        a.appointment_time
                     FROM appointments a
                     JOIN users u ON a.user_id = u.user_id
                     JOIN services s ON a.service_id = s.service_id
                     WHERE a.status IN ('confirmed', 'pending')
                     ORDER BY a.appointment_date, a.appointment_time";
$result_appointments = $conn->query($sql_appointments);
$appointments = $result_appointments->fetch_all(MYSQLI_ASSOC);

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_reminder'])) {
    $appointment_id = $_POST['appointment_id'] ?? '';
    $reminder_message = trim($_POST['reminder_message'] ?? '');
    $send_datetime = date('Y-m-d H:i:s');

    if (!empty($appointment_id) && !empty($reminder_message)) {
        $stmt = $conn->prepare("SELECT u.name AS customer_name, u.email AS customer_email, u.contact_number AS customer_contact,
                                            s.name AS service_name, a.appointment_date, a.appointment_time
                                     FROM appointments a
                                     JOIN users u ON a.user_id = u.user_id
                                     JOIN services s ON a.service_id = s.service_id
                                     WHERE a.appointment_id = ?");
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $customer_name = $row['customer_name'];
            $customer_email_to = $row['customer_email'];
            $customer_contact = $row['customer_contact'];
            $service_name = $row['service_name'];
            $appointment_date = $row['appointment_date'];
            $appointment_time = $row['appointment_time'];

            if (!empty($customer_email_to)) {
                if (
                    sendReminderEmail(
                        $customer_email_to,
                        $customer_name,
                        $appointment_date,
                        $appointment_time,
                        $service_name,
                        $reminder_message
                    )
                ) {
                    $stmt_insert = $conn->prepare("INSERT INTO reminders (appointment_id, message, send_datetime, email_sent) VALUES (?, ?, ?, 1)");
                    $stmt_insert->bind_param("iss", $appointment_id, $reminder_message, $send_datetime);
                    if ($stmt_insert->execute()) {
                        $message = '<div class="alert alert-success">Reminder sent successfully via email and recorded!</div>';
                    } else {
                        $message = '<div class="alert alert-warning">Email sent, but error recording reminder: ' . htmlspecialchars($stmt_insert->error) . '</div>';
                    }
                    $stmt_insert->close();
                } else {
                    $message = '<div class="alert alert-danger">Error sending email reminder. Please check your email configuration.</div>';
                }
            } else {
                $stmt_insert = $conn->prepare("INSERT INTO reminders (appointment_id, message, send_datetime, email_sent) VALUES (?, ?, ?, 0)");
                $stmt_insert->bind_param("iss", $appointment_id, $reminder_message, $send_datetime);
                if ($stmt_insert->execute()) {
                    $message = '<div class="alert alert-info">Customer has no email address. Reminder recorded.</div>';
                } else {
                    $message = '<div class="alert alert-warning">Error recording reminder: ' . htmlspecialchars($stmt_insert->error) . '</div>';
                }
                $stmt_insert->close();
            }
        } else {
            $message = '<div class="alert alert-danger">Appointment not found or invalid.</div>';
        }
        $stmt->close();
    } else {
        $message = '<div class="alert alert-warning">Please select an appointment and enter a reminder message.</div>';
    }
}

$sql_reminders = "SELECT r.reminder_id, r.message, r.send_datetime, r.email_sent, a.appointment_id,
                        u.name AS customer_name, u.email AS customer_email, u.contact_number AS customer_contact,
                        s.name AS service_name
                 FROM reminders r
                 JOIN appointments a ON r.appointment_id = a.appointment_id
                 JOIN users u ON a.user_id = u.user_id
                 JOIN services s ON a.service_id = s.service_id
                 ORDER BY r.send_datetime DESC";
$result_reminders = $conn->query($sql_reminders);
$existing_reminders = $result_reminders->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Reminders</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../styles/style.css" />
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
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-item.active a {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: bold;
        }

        .content-area {
            margin-left: 220px;
            padding: 0;
            flex-grow: 1;
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

        @media (max-width: 768px) {
            body {
                flex-direction: column;
                margin: 0;
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
        }
    </style>
</head>

<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content-area">
        <div class="content-navbar">
            <h4>Manage Reminders</h4>
        </div>

        <div class="container mt-4">
            <?php echo $message; ?>

            <h3>Send New Reminder</h3>
            <form method="POST" action="reminders.php">
                <div class="mb-3">
                    <label for="appointment_id" class="form-label">Select Appointment</label>
                <select class="form-select" id="appointment_id" name="appointment_id" required>
                    <option value="">-- Select Appointment --</option>
                    <?php if (!empty($appointments)): ?>
                        <?php foreach ($appointments as $appt): ?>
                            <option value="<?php echo $appt['appointment_id']; ?>"
                                data-date="<?php echo htmlspecialchars($appt['appointment_date']); ?>"
                                data-time="<?php echo htmlspecialchars(date('h:i A', strtotime($appt['appointment_time']))); ?>">
                                <?php echo htmlspecialchars($appt['customer_name']); ?>
                                <?php if (!empty($appt['customer_email'])): ?>
                                    (<?php echo htmlspecialchars($appt['customer_email']); ?>)
                                <?php endif; ?>
                                <?php if (!empty($appt['customer_contact'])): ?>
                                    - <?php echo htmlspecialchars($appt['customer_contact']); ?>
                                <?php endif; ?> -
                                <?php echo htmlspecialchars($appt['service_name']); ?> -
                                <?php echo htmlspecialchars($appt['appointment_date']); ?>
                                <?php echo htmlspecialchars(date('h:i A', strtotime($appt['appointment_time']))); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No pending or confirmed appointments.</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="reminder_message" class="form-label">Reminder Message</label>
                <textarea class="form-control" id="reminder_message" name="reminder_message" rows="3"
                    required>Your appointment is scheduled for <?php echo date('Y-m-d h:i A'); ?>. We look forward to seeing you!</textarea>
            </div>
                <button type="submit" name="send_reminder" class="btn btn-primary">Send Reminder</button>
            </form>

            <hr />

            <h3>Existing Reminders</h3>
            <?php if (!empty($existing_reminders)): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Service</th>
                                <th>Message</th>
                                <th>Sent On</th>
                                <th>Email Sent</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($existing_reminders as $reminder): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reminder['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($reminder['customer_email']); ?></td>
                                    <td><?php echo htmlspecialchars($reminder['customer_contact']); ?></td>
                                    <td><?php echo htmlspecialchars($reminder['service_name']); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($reminder['message'])); ?></td>
                                    <td><?php 
                                        $date = new DateTime($reminder['send_datetime']);
                                        $date->setTimezone(new DateTimeZone('Asia/Manila'));
                                        echo htmlspecialchars($date->format('Y-m-d H:i:s'));
                                    ?></td>
                                    <td><?php echo $reminder['email_sent'] ? 'Yes' : 'No'; ?></td>
                                    <td>
                                        <form method="POST" style="margin:0;">
                                            <input type="hidden" name="reminder_id"
                                                value="<?php echo $reminder['reminder_id']; ?>" />
                                            <button type="submit" name="delete_reminder" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this reminder?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No reminders found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.getElementById('appointment_id').addEventListener('change', function () {
            var select = this;
            var selectedOption = select.options[select.selectedIndex];
            var date = selectedOption.getAttribute('data-date');
            var time = selectedOption.getAttribute('data-time');
            var messageTextarea = document.getElementById('reminder_message');

            if (date && time) {
                messageTextarea.value = "Your appointment is scheduled for " + date + " at " + time + ". We look forward to seeing you!";
            } else {
                messageTextarea.value = "Please select an appointment to see the reminder message.";
            }
        });
    </script>
</body>

</html>
