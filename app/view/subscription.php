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
    $subscriptionData = $subscriptionDetails->viewSubscriptionDetails($userData['id'], $subscriptionId);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="../view/public.js"></script>
    <script src="../view/jquery-3.2.1.slim.min.js"></script>
    <script src="../view/jquery.min.js"></script>
    <script src="../view/popper.min.js"></script>
    <script src="../view/bootstrap.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
          crossorigin="anonymous">
    <link rel="stylesheet" href="../view/main.css">
    <script src="../view/public.js"></script>
    <link rel="stylesheet" href="../view/main.css">
    <link rel="stylesheet" href="../view/bootstrap.min.css">
<style>
    .header{
        background-color: #CBEAD0;
    }
    .header1{
        background-color: #F3CCD6;
    }
    .main{
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .card{
        width: 100%;
        height: 100%;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .card-top{
        display: flex;
        flex-direction: row;
        justify-content:space-between;
        align-items: center;
        height: 100px;
        background-color: #D3E5FD;
        border-bottom: #DFEDF6 1px solid;
        padding: 10px;
    }
    .card-bottom{
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .card-bottom-visiting{
        width: 50%;
        height: 60%;
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background-color:#D3E5FD;
        border: black 2px solid;
    }
    .bottom-visiting-title{
        width: 100%;
        display: flex;
        justify-content: center;
    }
    button{
        background-color: #040404;
        color: #FFF;
        padding: 10px;
        border-radius: 5px;
        border: none;
        margin: 20px;
    }
    .logout{
        width: 100%;
        position: fixed;
        bottom: 0;
        display: flex;
        justify-content: flex-end;
        font-size: 20px;
        font-weight: 400;
        padding-right: 20px;
        color: #595F6D;
        cursor: pointer;
    }
.back{
    font-size: 40px;
    font-weight: bold;
    padding: 10px;
}
.bottom-visiting-body {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
}

.visiting-body-select {
    display: flex;
    flex-wrap: nowrap;
    font-size: 20px;
    padding: 10px;
    width: 100%;
    justify-content: space-around;
    align-items: center;
    /*text-align: center;*/
}
.bottom-visiting-button{
    display: flex;
    justify-content: center;
    padding: 20px;
}
</style>
</head>
<body>
<div class="main">
    <div class="card shadow-sm bg-white rounded">
        <div class="card-top">
            <h3> View Subscription Page</h3>
            <p style="font-size: 25px;">Business owner</p>
        </div>
        <i class="back fas fa-chevron-left" onclick="navigatorTo('personal.php?username=<?php echo urlencode($username)?>')"></i>
        <div class="card-bottom">
            <div class="card-bottom-visiting">
                <div class="bottom-visiting-title">
                    <h3>Subscription Details</h3>
                </div>
                <div class="bottom-visiting-body" style="width:100%"; display:>
<!--                    <div class="visiting-body-select">-->
<!--                        <div>-->
<!--                            <i class="icon-calendar"></i>-->
<!--                            <p>Subscription Type:</p>-->
<!--                        </div>-->
<!--                        <div>-->
<!--                            <span style="font-weight: bold; margin-left: 170px;">Free Trial</span>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="visiting-body-select">
                        <div style="flex: 1;display: flex;justify-content: flex-end;margin-right: 40px">
                            <i class="icon-calendar"></i>
                            <p>Subscription Type:</p>
                        </div>
                        <div style="flex: 1;display: flex;justify-content: flex-start">
                            <p style="font-weight: bold;"><?php echo $subscriptionName?></p>
                        </div>
                    </div>
                    <?php foreach ($list as $key): ?>
                    <div class="visiting-body-select">
                        <div style="flex: 1;display: flex;justify-content: flex-end;margin-right: 40px">
<!--                            <img src="--><?php //= $key['img'] ?><!--" alt="--><?php //= $key['value'] ?><!--" style="width: 20px; margin-right: 5px;">-->
                            <p style="display:inline;"><?= $key["value"] ?></p>
                        </div>
                        <div style="flex: 1;display: flex;justify-content: flex-start">
                            <p style="font-weight:bold"><?= $key["time"] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="bottom-visiting-button">
                    <!-- ERROR HERE -->
                    <button onclick="navigatorTo('select.php?username=<?php echo urlencode($username);?>&subscriptionId=<?php echo urlencode($subscriptionId);?>')">Upgrade Subscription</button>
                    <!--<button onclick="navigatorTo('select.php?username=<?php echo $username?>&subscriptionId=<?php echo $subscriptionId?>')" style="margin-left: 20px;">Upgrade Subscription</button>-->
                    <button data-toggle="modal" data-target="#exampleModal" style="margin-right: 20px;">Cancel Subscription</button>
                </div>
                </div>
            </div>
            <div class="logout">
                <p>Logout</p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel" style="color:green;">Confirm Cancellation</h6>
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
                <button data-dismiss="modal" style="background-color: #545b62">Cancel</button>
                <button data-dismiss="modal" onclick="loadNav()" style="background-color: #2F67EF">Submit</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header">
                <h5 class="modal-title" id="exampleModalLabel">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Update successfully!
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" style="background-color: #2F67EF">OK</button>
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
                        ownerId: '<?php echo $userData['id'];?>'
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
