<?php
// set_time_limit(60); // Set execution time limit to 60 seconds

// $action = $_GET['action'] ?? '';

// $command = 'python C:\xampp\htdocs\otp_test\FYP-Homies-FR-System\app\ImageCheckingSystem\Scripts\WebcamCode.py'; // Adjust paths as necessary
// $output = shell_exec($command);

// if ($output === NULL || $output == '') {
//     echo "Error: No output from Python script.";
// } else {
//     // Proceed with normal flow, possibly passing the output to your HTML
//     echo file_get_contents('CaptureCode.html');// Example: Displaying the output of the Python script
// }

// Set the time limit to ensure the PHP script can manage long-running processes
set_time_limit(0); 

$action = $_GET['action'] ?? '';

// Command to start the Python script
$command = 'python C:\xampp\htdocs\otp_test\FYP-Homies-FR-System\app\ImageCheckingSystem\Scripts\WebcamCode.py';

// Start the Python script
if ($action === 'start') {
    // Use exec to start the script in the background
    exec($command . " > /dev/null 2>&1 &", $output, $return_var);
    echo "Python script started.";
}

// Stop the Python script
if ($action === 'stop') {
    // Use pkill to terminate the Python process (make sure pkill is available on your system)
    exec("taskkill /F /IM python.exe > /dev/null 2>&1", $output, $return_var);
    echo "Python script stopped.";
}
?>


