<?php
class Payment {
    public function createTransactionRecord($ownerId, $subscriptionId, $amount, $status, $date) {
        // Create a new record in the transaction table
        $sql = "INSERT INTO transaction (businessowner_id, subscription_id, paid_amount, payment_status, transaction_date) VALUES (?, ?, ?, ?, ?)";
    }
}
?>

