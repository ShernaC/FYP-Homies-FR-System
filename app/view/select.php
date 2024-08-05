<?php

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_GET['username']))
{
    $username = $_GET['username'];
    $subscriptionId = $_GET['subscriptionId'];
}

include_once '../controller/businessOwnerController.php';
$businessOwner = new searchBusinessOwnerAccount();
$userData = $businessOwner->handleSearchRequest($username);

include_once '../controller/paymentController.php';
$paymentController = new PaymentController();

$list=[
    ['title'=>'Free Trial Plan','cost'=>'Free ',"body2"=>"for one-month","select"=>["3 facial slots for trial users","Basic facial recognition"]],
    ['title'=>'Small Business Plan','cost'=>'S$50 ',"body2"=>"per year","select"=>["50 face datasets","Upgrade face recognition","S$4.17 SGD charged monthly"]],
    ['title'=>'Medium-Sized Business Plan','cost'=>'S$100 ',"body2"=>"per year","select"=>["100 face datasets","Advanced facial recognition","Role based permission system","Attendance function","S$8.33 SGD charged monthly"]],
    ['title'=>'Large Enterprise Plan','cost'=>'S$125 ',"body2"=>"per year","select"=>["200 face datastes","All functions of Medium-Sized Business Plan are included","Identity recognition function","S$10.42 SGD charged monthly"]],
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

    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link rel="stylesheet" href="style2.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous">

    <!--Stripe -->
    <script src="https://js.stripe.com/v3/"></script>
        
</head>
<body>
<div>
    <div>
        <div class="card-top">
            <h3>  Select Subscription Plans</h3>
        </div>
        <div class="card-bottom">
            <section class="pricing">
                <div class="back fas fa-chevron-left" onclick="navigatorTo('subscription.php?username=<?php echo urlencode($username);?>&subscriptionId=<?php echo urlencode($subscriptionId);?>')"></div>
                <?php foreach ($list as $key):?>
                <div class="card-wrapper">
                    <!-- card header -->
                    <div class="card-header">
                        <h3><?= $key["title"]?></h3>
                    </div>
                    
                    <!-- card detail -->
                    <?php foreach ($key["select"] as $value):?>
                    <div class="card-detail">
                        <p><span class="fas fa-check check"></span><?=$value?></p> 
                    </div>
                    <?php endforeach;?> 
                    
                    <!-- card price -->
                    <div class="card-price">
                        <p><?=$key["cost"]?><sub><?=$key["body2"]?></sub></p>
                    </div>
                    
                    <!-- button -->
                    <div>
                        <?php if ($key["title"] == 'Small Business Plan'): ?>
                            <button data-toggle="modal" data-target="#exampleModal" data-id=2 class="card-button">Select</button>
                        <?php elseif ($key["title"] == 'Medium-Sized Business Plan'): ?>
                            <button data-toggle="modal" data-target="#exampleModal" data-id=3 class="card-button">Select</button>   
                        <?php elseif ($key["title"] == 'Large Enterprise Plan'): ?>
                            <button data-toggle="modal" data-target="#exampleModal" data-id=4 class="card-button">Select</button>                                
                        <?php else:?>
                             <button data-toggle="modal" data-target="#exampleModal" data-id=1 class="card-button">Select</button>  
                        <?php endif;?>
                    </div>
                </div>
                <?php endforeach;?>
            </section>
            <div class="logout" id="111">
                <p>Logout</p>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="zhezhao">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                     <h6 class="modal-title" id="exampleModalLabel" style="color:green;">Confirm selection</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure select this?
                </div>
                <div class="modal-footer">
                    <button  data-dismiss="modal" style="background-color: #545b62">Cancel</button>
                    <button id="checkout-button" data-dismiss="modal" style="background-color: #2F67EF">Submit</button>
                    <script>
                       
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card fix hidden" id="loadd">
    <div class="card-body fixx">
        <p>Redirecting</p>
        <p>to</p>
        <p>third-party</p>
        <p>payment</p>
        <p>website</p>
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        // Event listener for showing the modal
        $(document).ready(function(){
        $('#exampleModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            window.selectedId = id;
            const btn = document.getElementById('checkout-button');
            var stripe = Stripe('pk_test_51PbbhBIIYoco0kNrrJljmbnplFpnPQMSdNJx3v2FyV5iZE5mrDbUV8uHXHRYqAP7M0PcF6lekiKPBWvROAyCDyFl00dZqWqxw0');
            btn.addEventListener('click', function(){
                $.ajax({
                    url: '../controller/paymentController.php',
                    type: 'POST',
                    data: {
                        action: 'create_checkout_session',
                        subscriptionId: window.selectedId,
                        username: '<?php echo $username; ?>',
                    },
                    success: function(response) {
                        var sessionId = response.sessionId;
                        stripe.redirectToCheckout({
                            sessionId: sessionId
                        }).then(function (result) {
                            if (result.error) {
                                alert(result.error.message);
                            }
                        });
                    },
                    error: function(xhr, status, error){
                        console.error('AJAX Error:', error);
                        alert('Failed to create checkout session. Please try again.');
                    }
                });
            });
        });
    });
});

            // if (selectedId==2){
            //     console.log('here in 2');
                <?php // $checkoutSessionId = $paymentController->createCheckoutSession(
                                            //     5000, // Amount in cents
                                            //     'sgd',
                                            //     'http://localhost/otp_test/FYP-Homies-FR-System/app/view/payment_success.php?username=' . $username . '&subscriptionId=' . 2,
                                            //     'http://localhost:8080/FaceRecognition/app/view/payment_cancel.php?username=' . $username . '&subscriptionId=' . 2,
                                            // );
            //     ?>
            // }
            // else if (selectedId==3){
            //     console.log('here in 3');
            //     <?php //$checkoutSessionId = $paymentController->createCheckoutSession(
            //                                     10000, // Amount in cents
            //                                     'sgd',
            //                                     'http://localhost/otp_test/FYP-Homies-FR-System/app/view/payment_success.php?username=' . $username . '&subscriptionId=' . 3,
            //                                     'http://localhost:8080/FaceRecognition/app/view/payment_cancel.php?username=' . $username . '&subscriptionId=' . 3,
            //                                 );
            //     ?>
            // }
            // else if (selectedId==4){
            //     <?php //$checkoutSessionId = $paymentController->createCheckoutSession(
            //                                     12500, // Amount in cents
            //                                     'sgd',
            //                                     'http://localhost/otp_test/FYP-Homies-FR-System/app/view/payment_success.php?username=' . $username . '&subscriptionId=' . 4,
            //                                     'http://localhost:8080/FaceRecognition/app/view/payment_cancel.php?username=' . $username . '&subscriptionId=' . 4,
            //                                 );
            //     ?>
            // }
            

            // console.log('Checkout session ID:', '<?php //echo $checkoutSessionId; ?>');

            // btn.addEventListener('click', function () {
            //     stripe.redirectToCheckout({
            //         sessionId: '<?php //echo $checkoutSessionId; ?>'
            //     });
            // });
            // loadNav();
  

    // const loadNav=()=>{
    //     // document.getElementById("loadd").classList.remove("hidden")
    //     setTimeout(()=>{
    //         document.getElementById("loadd").classList.add("hidden")
    //         //$('#exampleModal').modal()

    //         // console.log('Selected ID before AJAX:', window.selectedId)

    //         if (window.selectedId == 1) {
    //             window.location.replace();
    //         } else if (window.selectedId == 2) {
    //             window.location.replace('https://buy.stripe.com/test_00g9Bl5H48I59xubII');
    //         } else if (window.selectedId == 3) {
    //             window.location.replace('https://buy.stripe.com/test_3cseVF8TgcYl3967st');
    //         } else if (window.selectedId == 4) {
    //             window.location.replace('https://buy.stripe.com/test_28odRB0mKgax4da4gi');
    //         }
            
    //         // // Make an AJAX request to the PHP script        
    //         // $.ajax({
    //         //     url: '../controller/businessOwnerController.php',
    //         //     type: 'POST',
    //         //     data: {
    //         //         action: 'update',
    //         //         subscriptionId: window.selectedId, 
    //         //         username: '<?php echo $username;?>'
    //         //     },
    //         //     success: function(response) {
    //         //         //alert('successfully!');
    //         //         console.log(response);
    //         //         window.location.replace("subscription.php?isOne=true&username=<?php echo urlencode($username);?>&subscriptionId=" + window.selectedId)
    //         //     },
    //         //     error: function(xhr, status, error) {
    //         //         console.error('AJAX Error:', error);
    //         //         alert('Failed to update subscription details. Please try again.');
    //         //     }
    //         // });

    //         // Comment out this line to prevent immediate redirection
    //         // window.location.replace("subscription.php?isOne=true&username=<?php echo urlencode($username);?>&subscriptionId=<?php echo urlencode($subscriptionId);?>")
    //     },2000)
    // }
    
</script>
</body>
<style>
    .hidden{
        display: none;
    }
    /*显示元素*/
    .show{
        display: block;
    }
    .main{
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .card1{
        width: 100%;
        height: 100%;
        border-radius: 12px;
        /*border: #777777 2px solid;*/
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .card-top{
        display: flex;
        flex-direction: row;
        justify-content:center;
        align-items: center;
        height: 75px;
        background-color: transparent;
        /* border-bottom: #DFEDF6 1px solid; */
        padding: 20px;
    }
    .card-bottom{
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    button{
        background-color: #040404;
        color: #FFF;
        padding: 10px;
        border-radius: 5px;
        border: none;
        margin: 10px;
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
        color: black;
        cursor: pointer;
    }
    .back{
        font-size: 40px;
        font-weight: bold;
        padding: 10px;
    }
    .card-bottom-card{
        width: 100%;
        display: flex;
        justify-content: space-around;
        align-items: center;
    }
    .bottom-card-item{
        width: 270px;
        height: 350px;
        background-color: #D3E5FD;
        border: #000108 2px solid;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        /*align-items: center;*/
        margin: 10px;
        padding: 10px;
        font-size: 13px;
    }
    .card-item-texts{
        display: flex;
        flex-wrap: nowrap;
        align-items: baseline;
    }
    .header{
        background-color: #CBEAD0;
    }
    .header1{
        background-color: #F3CCD6;
    }
    .zhezhao{
        position: fixed;
        z-index: 1;
        background-color: rgba(0, 0, 0, 0.6);
        width: 100%;
        height: 100%;
    }
    .fix{
        position: fixed;
        left: 0;
        right: 0;
        width: 500px; /* 需要指定一个宽度 */
        margin: auto;
        top: 50%; /* 可选，使元素在垂直方向上居中 */
        transform: translateY(-50%); /* 可选，配合top使用以垂直居中 */
    }
    .fixx{
        display: flex;
        flex-direction: column;
        align-items: center;
        font-weight: bold;
        font-size: 24px;
    }
</style>
</html>
