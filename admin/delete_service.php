<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
ensure_admin();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $service_id = $_GET['id'];

    // Check if there are any appointments linked to this service
    $stmt_check = $conn->prepare("SELECT COUNT(*) FROM appointments WHERE service_id = ? AND status != 'cancelled'");
    $stmt_check->bind_param("i", $service_id);
    $stmt_check->execute();
    $count = $stmt_check->get_result()->fetch_row()[0];
    $stmt_check->close();

    if ($count > 0) {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            // AJAX request
            echo json_encode(['success' => false, 'error' => 'Cannot delete service with active appointments.']);
            exit;
        } else {
            redirect('services.php?error=Cannot delete service with active appointments.');
        }
    } else {
        $stmt_delete = $conn->prepare("DELETE FROM services WHERE service_id = ?");
        $stmt_delete->bind_param("i", $service_id);

        if ($stmt_delete->execute()) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                // AJAX request
                echo json_encode(['success' => true]);
                exit;
            } else {
                redirect('services.php?success=Service deleted successfully');
            }
        } else {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                // AJAX request
                echo json_encode(['success' => false, 'error' => $stmt_delete->error]);
                exit;
            } else {
                redirect('services.php?error=Error deleting service: ' . $stmt_delete->error);
            }
        }
        $stmt_delete->close();
    }
} else {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        // AJAX request
        echo json_encode(['success' => false, 'error' => 'Invalid service ID']);
        exit;
    } else {
        redirect('services.php?error=Invalid service ID');
    }
}
?>