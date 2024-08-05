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
    <title>Transaction Success</title>
    <script src="../view/jquery-3.2.1.slim.min.js"></script>
    <script src="../view/jquery.min.js"></script>
</head>
<body>
    <div class="success-message">
        <div class="success-icon">
            <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" stroke="green" stroke-width="2" fill="none"/>
                <path d="M6 12l4 4 8-8" stroke="green" stroke-width="2" fill="none"/>
            </svg>
        </div>
        <p class="message-text">Your transaction has been completed successfully.</p>
    </div>
    <script>
        // Redirect to another page after a delay
        console.log(<?php echo $subscriptionId?>);
        console.log('<?php echo $username;?>');

        setTimeout(function() {
            $.ajax({
                url: '../controller/businessOwnerController.php',
                type: 'POST',
                data: {
                    action: 'update',
                    subscriptionId: <?php echo $subscriptionId?>, 
                    username: '<?php echo $username;?>'
                },
                success: function(response) {
                    //alert('successfully!');
                    console.log(response);
                    window.location.replace("subscription.php?isOne=true&username=<?php echo urlencode($username);?>&subscriptionId=<?php echo urlencode($subscriptionId);?>")
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('Failed to update subscription details. Please try again.');
                }
            });
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>
</body>
</html>

