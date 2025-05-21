<?php
//  payment_confirmation.php
//  This script is called by your payment gateway.

require_once '../includes/db_connect.php'; // Assuming this file establishes your PDO connection ($pdo)
require_once '../includes/functions.php';

//  1.  Get payment details
//  Replace with your payment gateway's data.
$payment_status = $_POST['payment_status'] ?? '';
$transaction_id = $_POST['transaction_id'] ?? '';
$paid_amount = $_POST['amount'] ?? 0;
$appointment_id = $_POST['appointment_id'] ?? 0;

//  Logging
$log_message = "Payment received: Status: $payment_status, Transaction ID: $transaction_id, Amount: $paid_amount, Appt ID: $appointment_id.  POST data: " . json_encode($_POST);
error_log($log_message);

//  2.  Validate the payment data
$valid_payment = false;
if (
    $payment_status === 'success' &&
    !empty($transaction_id) &&
    is_numeric($paid_amount) && $paid_amount > 0 &&
    is_numeric($appointment_id) && $appointment_id > 0
) {
    //  *** VERIFY WITH PAYMENT GATEWAY  ***
    //  Replace with your gateway's API call to verify the transaction.
    //  For security, DO NOT rely solely on the POST data.
    //  Example (replace with your gateway's API interaction):
    //  $verification_result = call_payment_gateway_api($transaction_id, $paid_amount);
    //  if ($verification_result['status'] === 'VERIFIED') {
    //      $valid_payment = true;
    //  }
    $valid_payment = true; // FOR DEMO PURPOSES ONLY - REMOVE IN PRODUCTION
}

if ($valid_payment) {
    //  3.  Update the database using PDO
    $sql_update = "UPDATE appointments SET status = :status, transaction_id = :transaction_id WHERE appointment_id = :appointment_id";
    $stmt_update = $pdo->prepare($sql_update);

    if ($stmt_update) {
        $stmt_update->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt_update->bindParam(':transaction_id', $transaction_id, PDO::PARAM_STR);
        $stmt_update->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);

        $status = 'confirmed'; // Set the status to 'confirmed'

        try {
            if ($stmt_update->execute()) {
                //  4.  Success
                $log_message = "Appointment confirmed: $appointment_id, Transaction ID: $transaction_id";
                error_log($log_message);
                header("Location: appointment_confirmation.php?appointment_id=$appointment_id");
                exit();
            } else {
                $errorInfo = $stmt_update->errorInfo();
                $log_message = "DB error: " . $errorInfo[2];
                error_log($log_message);
                header("Location: payment_error.php?appointment_id=$appointment_id&error=db_error");
                exit();
            }
        } catch (PDOException $e) {
            $log_message = "PDO Exception: " . $e->getMessage();
            error_log($log_message);
            header("Location: payment_error.php?appointment_id=$appointment_id&error=db_error");
            exit();
        } finally {
            // Close the statement
            $stmt_update->closeCursor();
        }
    } else {
        $errorInfo = $pdo->errorInfo();
        $log_message = "Error preparing statement: " . $errorInfo[2];
        error_log($log_message);
        header("Location: payment_error.php?appointment_id=$appointment_id&error=db_error");
        exit();
    }
} else {
    //  Payment was not valid
    $log_message = "Invalid payment: Status: $payment_status, Transaction ID: $transaction_id, Amount: $paid_amount, Appointment ID: $appointment_id";
    error_log($log_message);
    header("Location: payment.php?appointment_id=$appointment_id&error=payment_failed");
    exit();
}
?>