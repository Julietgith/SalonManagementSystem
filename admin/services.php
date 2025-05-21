<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
ensure_admin();

$services = get_services($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
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
            background-color: #343a40;
            /* Dark background for sidebar */
            color: white;
            padding-top: 20px;
            width: 220px;
            /* Adjust width as needed */
            position: fixed;
            /* Fixed sidebar */
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 100;
            /* Ensure it's above other content */
            overflow-y: auto;
            /* Enable scrolling if content overflows */
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
            /* Adjust to match sidebar width */
            padding: 0; /* Remove default padding from content area */
            flex-grow: 1;
            /* Take remaining width */
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
            padding: 20px; /* Add padding back to the main container */
            margin-top: 20px; /* Push the container below the sticky navbar */
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
                /* Stack sidebar and content on smaller screens */
                margin: 0;
                /* Ensure no body margin */
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
                /* Remove content area padding */
            }

            .content-navbar {
                padding: 1rem;
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 10px;
                /* Adjust margin below navbar on small screens */
                position: static;
                /* Revert to static on small screens */
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
                /* Adjust container padding for small screens */
                margin-top: 15px;
                /* Adjust container top margin for small screens */
            }
        }
    </style>
</head>

<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content-area">
        <div class="content-navbar">
            <h4>Manage Services</h4>
        </div>

        <div class="container mt-4">
            <p>
                <a href="add_service.php" class="btn btn-success mb-3">Add New Service</a>
                <button id="printServicesBtn" class="btn btn-primary mb-3 ms-2">Print Services</button>
            </p>
            <?php if (!empty($services)): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Duration (minutes)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $service): ?>
                            <tr>
                                <td><?php echo $service['service_id']; ?></td>
                                <td><?php echo htmlspecialchars($service['name']); ?></td>
                                <td><?php echo htmlspecialchars($service['description']); ?></td>
                                <td><?php echo htmlspecialchars($service['price']); ?></td>
                                <td><?php echo htmlspecialchars($service['duration']); ?></td>
                                <td>
                                    <a href="edit_service.php?id=<?php echo $service['service_id']; ?>"
                                       class="btn btn-sm btn-primary">Edit</a>
                                    <button class="btn btn-sm btn-danger delete-service-btn" data-id="<?php echo $service['service_id']; ?>">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No services offered yet.</p>
            <?php endif; ?>

        </div>
    </div>

    <script>
        document.getElementById('printServicesBtn').addEventListener('click', function() {
            window.open('../adm/simple_print.php', '_blank');
        });

        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-service-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    if (confirm('Are you sure you want to delete this service?')) {
                        const serviceId = this.getAttribute('data-id');
                        fetch(`delete_service.php?id=${serviceId}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove the row from the table
                                const row = this.closest('tr');
                                row.parentNode.removeChild(row);
                                alert('Service deleted successfully.');
                            } else {
                                alert('Error deleting service: ' + data.error);
                            }
                        })
                        .catch(error => {
                            alert('Error deleting service: ' + error);
                        });
                    }
                });
            });
        });
    </script>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/script.js"></script>
</body>

</html>