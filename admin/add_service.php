<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
ensure_admin();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];

    if (empty($name) || empty($price) || empty($duration)) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO services (name, description, price, duration) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdi", $name, $description, $price, $duration);

        if ($stmt->execute()) {
            redirect('services.php?success=Service added successfully');
        } else {
            $error = "Error adding service: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Service</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: pink
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
            <h2>Add New Service</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="add_service.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Service Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description"></textarea>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="duration" class="form-label">Duration (minutes)</label>
                    <input type="number" class="form-control" id="duration" name="duration" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Service</button>
                <a href="services.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/script.js"></script>
</body>

</html>