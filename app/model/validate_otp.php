<?php
global $conn;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!$conn || !$conn instanceof mysqli) {
    die("Account: Database connection not established.");
}

include_once "../db/db.php";

$otp = $_POST["otp"];
$email = $_SESSION['email'];

// Check OTP in sysadmin table
$sql_sysadmin = "SELECT * FROM sysadmin WHERE email='$email' AND otp='$otp'";
$rs_sysadmin = mysqli_query($conn, $sql_sysadmin) or die(mysqli_error($conn));

// Check OTP in businessowner table
$sql_businessowner = "SELECT * FROM businessowner WHERE email='$email' AND otp='$otp'";
$rs_businessowner = mysqli_query($conn, $sql_businessowner) or die(mysqli_error($conn));

$response = array('success' => false);

if (mysqli_num_rows($rs_sysadmin) > 0 )
{
    $sql = "UPDATE sysadmin SET otp='' WHERE email='$email'";
    $response['success'] = true;
}
else if (mysqli_num_rows($rs_businessowner) > 0)
{
    $sql = "UPDATE businessowner SET otp='' WHERE email='$email'";
    $response['success'] = true;
}
else
{
    $response['message'] = 'Invalid OTP. Please try again.';
}

// if (mysqli_num_rows($rs_sysadmin) > 0 || mysqli_num_rows($rs_businessowner) > 0) {
//     $sql="update users set user_otp='' where user_email='$email'"; //to delete OTP in database after verification
//     $response['success'] = true;
// } else {
//     $response['message'] = 'Invalid OTP. Please try again.';
// }

header('Content-Type: application/json');
echo json_encode($response);
?>
