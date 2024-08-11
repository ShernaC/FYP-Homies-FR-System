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

// Check email in sysadmin table
$sql_sysadmin = "SELECT * FROM sysadmin WHERE email='$email'";
$rs_sysadmin = mysqli_query($conn, $sql_sysadmin) or die(mysqli_error($conn));

// Check email in businessowner table
$sql_businessowner = "SELECT * FROM businessowner WHERE email='$email'";
$rs_businessowner = mysqli_query($conn, $sql_businessowner) or die(mysqli_error($conn));

// Obtain the name
if (mysqli_num_rows($rs_sysadmin) > 0) {
    $row = mysqli_fetch_assoc($rs_sysadmin);
    $name = $row['name'];  
} elseif (mysqli_num_rows($rs_businessowner) > 0) {
    $row = mysqli_fetch_assoc($rs_businessowner);
    $name = $row['name'];  
}

if (!empty($name)) {
    $_SESSION['email'] = $email;
    $otp = rand(11111, 99999);

    if (mysqli_num_rows($rs_sysadmin) > 0) {
        $sql = "UPDATE sysadmin SET otp='$otp' WHERE email='$email'";
    } else {
        $sql = "UPDATE businessowner SET otp='$otp' WHERE email='$email'";
    }
    
    $rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    
    // Send OTP email
    send_otp($email, $name, "OTP LOGIN", $otp); 
    
} else {
    // Redirect or handle invalid email appropriately
    // header("location:index.php?msg=Invalid email.");
}

?>
