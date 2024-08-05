<?php
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_GET['username']))
{
    $username = $_GET['username'];
    $subscriptionId = $_GET['subscriptionId'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Failed</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="failure-message">
        <div class="failure-icon">
            <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" stroke="red" stroke-width="2" fill="none"/>
                <line x1="8" y1="8" x2="16" y2="16" stroke="red" stroke-width="2"/>
                <line x1="16" y1="8" x2="8" y2="16" stroke="red" stroke-width="2"/>
            </svg>
        </div>
        <p class="message-text">Your transaction has failed. Please try again later or contact support.</p>
    </div>
    <script>
        // Redirect to another page after a delay
        setTimeout(function() {
            window.location.href = 'select.php?username=<?php echo urlencode($username);?>&subscriptionId=<?php echo urlencode($subscriptionId);?>';
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>
</body>
</html>
