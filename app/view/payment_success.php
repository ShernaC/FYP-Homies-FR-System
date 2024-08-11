<?php
if (!isset($_SESSION)) {
    session_start();
}

// Check if autoload file exists
if (!file_exists('../vendor/autoload.php')) {
    die('Autoload file not found. Did you run `composer install`?');
}

require_once '../vendor/autoload.php';

// Check if stripe_config file is included
if (!file_exists('../config/stripe_config.php')) {
    die('Stripe configuration file not found.');
}

require_once '../config/stripe_config.php';

// Check if the parameters are set
if (!isset($_GET['username']) || !isset($_GET['subscriptionId'])) {
    die('Required parameters missing.');
}

$username = $_GET['username'];
$subscriptionId = intval($_GET['subscriptionId']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Success</title>
    <script src="../view/jquery-3.2.1.slim.min.js"></script>
    <script src="../view/jquery.min.js"></script>
    <script src="../view/popper.min.js"></script>
    <script src="../view/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f7f7f7;
            overflow: hidden;
        }
        .card {
            padding: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
        }
        .confetti {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }
        .confetti-piece {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #ffc107;
            opacity: 0.7;
            transform: rotate(45deg);
            animation: fall 3s linear infinite;
            top: -15px;
        }
        .confetti-piece:nth-child(2n) {
            background-color: #28a745;
        }
        .confetti-piece:nth-child(3n) {
            background-color: #17a2b8;
        }
        .confetti-piece:nth-child(4n) {
            background-color: #ff6f61;
        }
        .confetti-piece:nth-child(5n) {
            background-color: #e83e8c;
        }
        @keyframes fall {
            0% {
                opacity: 1;
                transform: translateY(0) rotate(45deg);
            }
            100% {
                opacity: 0;
                transform: translateY(100vh) rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <div class="confetti">
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="display-4 text-success mb-3">
                <i class="fas fa-check-circle"></i>
            </div>
            <h4 class="card-title">Payment succeeded!</h4>
            <p class="card-text">Thank you for processing your most recent payment. You will be redirected back to our page shortly.</p>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Generate multiple confetti pieces
        function generateConfetti() {
            const confettiContainer = document.querySelector('.confetti');
            for (let i = 0; i < 100; i++) {
                const confettiPiece = document.createElement('div');
                confettiPiece.classList.add('confetti-piece');
                confettiPiece.style.left = `${Math.random() * 100}%`;
                confettiPiece.style.animationDelay = `${Math.random() * 3}s`;
                confettiPiece.style.backgroundColor = getRandomColor();
                confettiContainer.appendChild(confettiPiece);
            }
        }

        function getRandomColor() {
            const colors = ['#ffc107', '#28a745', '#17a2b8', '#ff6f61', '#e83e8c'];
            return colors[Math.floor(Math.random() * colors.length)];
        }

        generateConfetti();
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
                    window.location.replace("subscription.php?isOne=true&username=<?php echo urlencode($username);?>&subscriptionId=<?php echo urlencode($subscriptionId);?>")
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('Failed to update subscription details. Please try again.');
                }
            });
        }, 3000);
    </script>
</body>
</html>

