<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

ensure_admin(); // Ensure only admin users can access this page

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $feedback_id = $_GET['delete'];
    $stmt_delete = $conn->prepare("DELETE FROM feedbacks WHERE id = ?");
    $stmt_delete->bind_param("i", $feedback_id);

    if ($stmt_delete->execute()) {
        $message = '<div class="alert alert-success">Feedback deleted successfully.</div>';
    } else {
        $message = '<div class="alert alert-danger">Error deleting feedback: ' . htmlspecialchars($stmt_delete->error) . '</div>';
    }
    $stmt_delete->close();
}

$sql_select = "SELECT id, name, email, feedback_text, submission_date FROM feedbacks ORDER BY submission_date DESC";
$result = $conn->query($sql_select);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Customer Feedbacks</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/style.css">
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

        .content-area {
            margin-left: 220px;
            padding: 20px;
            flex-grow: 1;
        }

        .content-navbar {
            background-color: #343a40;
            color: white;
            padding: 1rem 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-navbar h4 {
            margin-bottom: 0;
            font-size: 1.5rem;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .btn-danger {
            margin-left: 10px;
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
                padding: 15px;
            }

            .content-navbar {
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 10px;
            }

            .content-navbar h4 {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content-area">
        <div class="content-navbar">
            <h4>Customer Feedbacks</h4>
        </div>

        <div class="container-fluid">
            <?php echo $message; ?>

            <div class="table-responsive">
                <?php if ($result->num_rows > 0): ?>
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Feedback</th>
                                <th>Submitted On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row["id"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["name"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["email"]); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($row["feedback_text"])); ?></td>
                                    <td><?php echo htmlspecialchars($row["submission_date"]); ?></td>
                                    <td>
                                        <a href="?delete=<?php echo $row["id"]; ?>" class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to delete this feedback?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No customer feedbacks yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>