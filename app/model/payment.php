<?php
include_once "../db/db.php";
include_once "../view/email.php";

global $conn;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!$conn || !$conn instanceof mysqli) {
    die("Account: Database connection not established.");
}

$email = $_POST["email"];
$name = "";

// Check email in businessowner table
$sql_businessowner = "SELECT * FROM businessowner WHERE email='$email'";
$rs_businessowner = mysqli_query($conn, $sql_businessowner) or die(mysqli_error($conn));


class Payment {
    public function createTransactionRecord($ownerId, $subscriptionId, $amount, $status, $date) {
        // Create a new record in the transaction table
        $sql = "INSERT INTO transaction (businessowner_id, subscription_id, paid_amount, payment_status, transaction_date) VALUES (?, ?, ?, ?, ?)";
    }
}
?>

