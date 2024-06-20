<?php
include_once '../model/troubleshoot.php';
include_once '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
    $admin_id = $_POST['admin_id'];
    $details = $_POST['details'];

    $troubleshoot = new Troubleshoot($admin_id, $details);
    $result = $troubleshoot->create();
    
    echo $result;
    exit;
}
?>