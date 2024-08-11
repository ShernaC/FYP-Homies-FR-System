<?php

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_GET['username']) && isset($_GET['subscriptionId'])) {
    $username = $_GET['username'];
    $subscriptionId = $_GET['subscriptionId'];
}

include_once '../controller/businessOwnerController.php';
$businessOwner = new searchBusinessOwnerAccount();
$userData = $businessOwner->handleSearchRequest($username);

include_once '../controller/subscriptionController.php';
$subscription = new viewSubscriptionController();
$subscriptionData = $subscription->viewSubscription($subscriptionId);
$subscriptionName = $subscriptionData['name'];
$subscriptionPrice = $subscriptionData['price'];
$subscriptionDescription = $subscriptionData['description'];

if ($subscriptionId == 1) {
    $startTime = '-';
    $endTime = '-';
} else {
    include_once '../controller/subscriptionController.php';
    $subscriptionDetails = new viewSubscriptionDetailsController();
    $subscriptionData = $subscriptionDetails->viewSubscriptionDetails($username, $subscriptionId);
    $startTime = $subscriptionData['startDate'];
    $endTime = $subscriptionData['endDate'];
}

$list=[
    ['icon'=>'icon-time','value'=>'Start Date:',"time"=>$startTime,'img'=>'../view/images/clock-regular.svg'],
    ['icon'=>'icon-time','value'=>'End Date :',"time"=>$endTime,'img'=>'../view/images/clock-solid.svg'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/lux/bootstrap.min.css">
    <script src="../view/public.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .profile-container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }
        .profile-header {
            background-color: #333;
            color: white;
            padding: 1rem;
            border-radius: 0.5rem 0.5rem 0 0;
            text-align: center;
            font-size: 1.25rem;
        }
        .profile-content {
            padding: 2rem;
            font-size: 1.10rem;
        }
        .profile-content img {
            width: 20px;
            height: 20px;
            margin-right: 0.5rem;
        }
        .profile-content .item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        .profile-content .item i {
            margin-left: 0.5rem;
        }
        .profile-content button {
            background-color: #000;
            color: #FFF;
            padding: 0.5rem 1rem;
            border-radius: 0.3rem;
            border: none;
            margin-right: 1rem;
            font-size: 1rem;
        }
        .profile-content .spinner-border {
            display: none;
        }
        .profile-content .spinner-border.show {
            display: inline-block;
        }
        .modal-header {
            background-color: #333;
            color: white;
        }
        .bottom-right {
            position: fixed;
            bottom: 10px;
            right: 10px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
<div class="profile-container">
    <div>
        <i class="back fas fa-chevron-left top-left" onclick="navigatorTo('personal.php?username=<?php echo urlencode($username)?>')"></i>
    </div>
    <div class="profile-header">
        <h2 class="font-bold text-white">Subscription Details</h2>
    </div>
    <div class="profile-content">
        <div class="item">
            <i class="icon-calendar"></i>
            <p>Subscription Type: <strong><u><b><?php echo $subscriptionName; ?></b></u></strong></p>
        </div>
        <?php foreach ($list as $key): ?>
        <div class="item">
            <p><?= $key['value'] ?><strong><?= $key['time'] ?></strong></p>      
        </div>
        <?php endforeach; ?>
        <div class="button-group item">
            <button onclick="navigatorTo('select.php?username=<?php echo urlencode($username);?>&subscriptionId=<?php echo urlencode($subscriptionId);?>')">Upgrade Subscription</button>
            <button data-toggle="modal" data-target="#exampleModal">Cancel Subscription</button>
        </div>
    </div>
    <div class="w-full max-w-6xl mt-3 text-right text-gray-500 bottom-right">
        <a href="login.php">Logout</a>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-white" id="exampleModalLabel">Confirm Cancellation</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Confirm subscription cancellation? 
                <?php if (date("Y-m-d") < $endTime): ?>
                    <p style="color:red;"><br>Note: You will not be refunded for the remaining days of your subscription.</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-secondary">Cancel</button>
                <button data-dismiss="modal" class="btn btn-primary" onclick="loadNav()">Submit</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Update successfully!
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal"  class="btn btn-primary" >OK</button>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    (function () {
        let paramValue = getURLParameter('isOne')
        if (paramValue === 'true')$('#exampleModal1').modal()
    })()

    function loadNav() {
        setTimeout(()=>{
            $('#exampleModal').modal('hide');
            if ((<?php echo $_GET['subscriptionId']?>) != 1){
                // Make an AJAX request to the PHP script        
                $.ajax({
                    url: '../controller/businessOwnerController.php',
                    type: 'POST',
                    data: {
                        action: 'update',
                        subscriptionId: 1, 
                        username: '<?php echo $username;?>'
                    },
                    success: function(response) {
                        //alert('successfully!');
                        console.log(response);
                        window.location.replace("subscription.php?isOne=true&username=<?php echo urlencode($username);?>&subscriptionId=" + 1)
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        alert('Failed to update subscription details. Please try again.');
                    }
                });
            }
            else
            {
                alert('You are on free trial. Please upgrade your subscription to cancel it.')
            }
            // Comment out this line to prevent immediate redirection
            // window.location.replace("subscription.php?isOne=true&username=<?php echo urlencode($username);?>&subscriptionId=<?php echo urlencode($subscriptionId);?>")
        },2000)
    }
</script>

</html>
