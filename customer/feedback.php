<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php'; // Ensure customer access
ensure_customer();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$submission_message = ''; // Initialize a variable to store the submission message

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_feedback'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $feedback_text = mysqli_real_escape_string($conn, $_POST['feedback']);
    $submission_date = date("Y-m-d H:i:s");
    $customer_id = $_SESSION['user_id']; // Assuming you store customer ID in session

    $sql = "INSERT INTO feedbacks (name, email, feedback_text, submission_date)
            VALUES ('$name', '$email', '$feedback_text', '$submission_date')";

    if ($conn->query($sql) === TRUE) {
        $submission_message = '<div class="alert alert-success" role="alert">Thank you for your feedback!</div><p><a href="feedback.php" class="btn btn-primary">Submit Another Feedback</a></p>';
    } else {
        $submission_message = '<div class="alert alert-danger" role="alert">Error submitting feedback: ' . htmlspecialchars($conn->error) . '</div><p><a href="feedback.php" class="btn btn-secondary">Try Again</a></p>';
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Feedback</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: pink;
        }
        .content-area {
            display: flex; /* Enable flexbox for centering */
            justify-content: center; /* Center horizontally */
            align-items: flex-start; /* Align items to the top for the message */
            min-height: calc(100vh - 0px);
            padding: 20px;
            flex-direction: column; /* Stack heading, message, and form */
            align-items: center; /* Center items horizontally */
        }
        .feedback-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 60%; /* Adjust for medium size */
            max-width: 600px; /* Optional: set a maximum width */
            margin-top: 20px; /* Add some space between heading/message and form */
        }
        h2 {
            color: #007bff;
            margin-bottom: 20px;
            text-align: center; /* Center the heading */
        }
        label {
            font-weight: bold;
            margin-top: 10px;
        }
        textarea {
            resize: vertical;
        }
        .btn-primary {
            margin-top: 20px;
        }
        
    </style>
</head>
<body>
    <?php include 'customer_sidebar.php'; ?>
    <div class="content-area">
        <h2>Share Your Feedback</h2>
        <?php echo $submission_message; // Display the submission message here ?>
        <div class="feedback-container">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="feedback">Feedback:</label>
                    <textarea class="form-control" id="feedback" name="feedback" rows="5" required></textarea>
                </div>
                <button type="submit" name="submit_feedback" class="btn btn-primary btn-block">Submit Feedback</button>
            </form>
        </div>
    </div>
</body>
</html>