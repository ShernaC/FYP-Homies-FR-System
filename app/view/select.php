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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/lux/bootstrap.min.css">
    <script src="../view/public.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!--Stripe -->
    <script src="https://js.stripe.com/v3/"></script>
        
    <style>
        .bottom-right {
            position: fixed;
            bottom: 10px;
            right: 10px;
        }
        .card:hover {
            transform: scale(1.05);
            transition: transform 0.3s;
        }
        .row.flex-nowrap {
            flex-wrap: wrap; /* Wrap into the next row if the width exceeds the container */
        }

        .col-md-3 {
            min-width: 250px; /* Adjust the width to ensure no overlap */
        }
    </style>
</head>
<body>
<div class="container mb-4 my-5">
    <div class="d-flex justify-content-between align-items-center mb-4 w-100">
        <div class="back fas fa-chevron-left" onclick="navigatorTo('subscription.php?username=<?php echo urlencode($username);?>&subscriptionId=<?php echo urlencode($subscriptionId);?>')"></div>
        <h3 class="text-xl font-bold text-center">Select Subscription Plans</h3>
        <div></div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="row flex-nowrap" style="padding-top: 100px; gap: 20px;">
            <?php foreach ($list as $key):?>
            <div class="mb-4 d-flex align-items-stretch">
                <div class="card h-100 w-100">
                    <div class="card-header text-center">
                        <h4><?= $key["title"] ?></h4>
                    </div>
                    <div class="card-body">
                        <?php foreach ($key["select"] as $value):?>
                            <p><span class="fas fa-check check" style="color:forestgreen; padding-right:10px;"></span><?=$value?></p><br>
                        <?php endforeach;?> 
                    </div>
                    <div class="card-footer text-center">
                        <p class="h5"><?=$key["cost"]?><sub><?=$key["body2"]?></sub></p><br>
                        <div>
                            <?php if (($key["title"] == 'Small Business Plan') && ($subscriptionId!=2)):?>
                                <button data-toggle="modal" data-target="#exampleModal" class="btn btn-primary" data-id=2>Select</button>
                            <?php elseif (($key["title"] == 'Medium-Sized Business Plan') && ($subscriptionId!=3)): ?>
                                <button data-toggle="modal" data-target="#exampleModal" data-id=3 class="btn btn-primary">Select</button>   
                            <?php elseif ($key["title"] == 'Large Enterprise Plan' && ($subscriptionId!=4)): ?>
                                <button data-toggle="modal" data-target="#exampleModal" data-id=4 class="btn btn-primary">Select</button>                                
                            <?php else:?>
                                <button data-toggle="modal" data-target="#exampleModal" data-id=1 class="btn btn-primary" disabled>Select</button>  
                            <?php endif;?>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>
<div class="text-end bottom-right">
    <a href="login.php" class="btn btn-secondary">Logout</a>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Confirm selection</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to select this plan?
            </div>
            <div class="modal-footer">
                <button  data-dismiss="modal" class="btn btn-secondary">Cancel</button>
                <button id="checkout-button" data-dismiss="modal" class="btn btn-primary">Submit</button>
            </div>
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
            console.log('Selected ID:', window.selectedId);
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
    
</script>
</body>
<!-- <style>
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
</style> -->
</html>
