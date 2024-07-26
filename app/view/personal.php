<?php
include_once '../controller/businessOwnerController.php';
include_once '../controller/subscriptionController.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];
}

$businessOwner = new searchBusinessOwnerAccount();
$userData = $businessOwner->handleSearchRequest($username);

$accountId = $userData['id'];
$name = $userData['name'];
$subscriptId = $userData['subscription_id'];

$subscription = new viewSubscriptionController();
$subscriptionData = $subscription->viewSubscription($subscriptId);
$subscriptionPrice = $subscriptionData['price'];
$subscriptionDescription = $subscriptionData['description'];

$list=[
    ['icon'=>'icon-tags', 'label'=>'Username:', 'value'=>$userData['userName'], 'img'=>'../view/images/id-card-regular.svg'],
    ['icon'=>'icon-envelope', 'label'=>'Email:', 'value'=>$userData['email'], 'img'=>'../view/images/envelope-regular.svg'],
    ['icon'=>'icon-zoom-in', 'label'=>'Subscription Name:','value'=>$subscriptionData['name'], 'img'=>'../view/images/calendar-days-regular.svg'],
    ['icon'=>'icon-home', 'label'=>'Company Name:', 'value'=>$userData['company'], 'img'=>'../view/images/building-regular.svg'],
];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src='../view/public.js'></script>
    <script src="../view/jquery-3.2.1.slim.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../view/jquery.min.js"></script>
    <script src="../view/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
          crossorigin="anonymous">
    <link rel="stylesheet" href="../view/main.css">
    <script src="../view/public.js"></script>
    <link rel="stylesheet" href="../view/main.css">
    <link rel="stylesheet" href="../view/bootstrap.min.css">
    <!--    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap.min.css">-->
</head>
<body>
<div class="main">
    <div class="card shadow-sm bg-white rounded">
        <div class="card-top">
            <h3>Profile Page</h3>
        </div>
        <div class="card-bottom" style="font-family:montserrat">
            <div class="card-bottom-visiting">
                <div class="bottom-visiting-top">
                    <div class="visiting-top-image"> </div>
                    <div class="visiting-top-texts">
                        <div class="large-font" style="text-align: left;">
                            <i style="margin-left: 0;" >Welcome, <?php echo $name ?></i>
                        </div>
                    </div>
                </div>
                <div class="bottom-visiting-bottom">
                    <div class="visiting-bottom-body">
                        <div class="bottom-body-list">
                            <?php foreach ($list as $key) :?>
                            <div class="body-list-item">
                                <img src="<?= $key['img'] ?>" alt="<?= $key['label'] ?> icon" style="width: 20px; height: 20px;">
                                <i><?= $key['label'] ?><?= $key['value'] ?></i>
                            </div>
                            <?php endforeach;?>
                            <div class="body-list-item">
                                <button id="viewSubscriptionBtn" onclick="navigatorTo()">View my subscription</button>
                            </div>
                            <div class="body-list-item">
                                <button onclick="upload()">Upload datasets</button>
                                <div class="spinner-border hidden" id="load" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <div  class="body-list-item">
                                <p style="font-weight: bold"></p>
                            </div>
                            <div  class="body-list-item">
                                <p style="font-weight: bold" class="hidden" id="font1">Verified</p>
                            </div>
                            <div>
                                <p style="color: red;font-weight: bold" class="hidden" id="font2">Rejected File Name XXX. Please Reupload.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full max-w-6xl mt-3 text-right text-gray-500">
            <a href="login.php" style="font-size: 24px;">Logout</a>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header">
                <h5 class="modal-title" id="exampleModalLabel">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Cancel successfully!
            </div>
            <div class="modal-footer">
                
                <button data-dismiss="modal" style="background-color: #2F67EF">OK</button>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    // const getURLParameter=(name)=>{
    //     name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    //     var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    //         results = regex.exec(location.search);
    //     return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    // }
    (function () {
        let paramValue = getURLParameter('isOne')
        if (paramValue === 'true')$('#exampleModal').modal()
    })()
    //加载动画
    const upload=()=>{
        document.getElementById("load").classList.add("show")
        setTimeout(()=>{
            document.getElementById("load").classList.remove("show")
            document.getElementById("font1").classList.remove("hidden")
        },2000)
    }
    //跳转subscript页面
    document.getElementById('viewSubscriptionBtn').addEventListener('click', function() {
        window.location.href = "subscription.php?username=" + "<?php echo $username;?>" + "&subscriptionId=" + "<?php echo $subscriptId;?>";
    });
</script>
<style>
    .header{
        background-color: #CBEAD0;
    }
    .header1{
        background-color: #F3CCD6;
    }
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
    .large-font {
        font-size: 30px;
    }
    .card{
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
        justify-content: flex-start;
        align-items: center;
        height: 100px;
        background-color: #D3E5FD;
        border-bottom: #DFEDF6 1px solid;
        padding-left: 10px;
    }
    .card-bottom{
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .card-bottom-visiting{
        width: 70%;
        height: 60%;
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        border: #909090 2px solid;
    }
    .bottom-visiting-top{
        height:90px;
        background-color: #D3E5FD;
        border: #9DB1B7 1.5px solid;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-start;

    }
    .visiting-top-image{
        background-image: url("../view/images/img.png");
        height: 100px;
        width: 100px;
        background-size: 100% 100%;
        background-position: center;
    }
    .visiting-top-texts{
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        padding:0 10px 0 10px;
        align-items: center;
        /*border: rgba(87, 102, 105, 0.6) 1.5px solid;*/
        flex: 1;
        height: 50px;
        margin-right: 20px;
        font-size:17px ;
    }
    .large-font {
        text-align: left; 
    }
    .large-font i {
        margin-left: 0;
    }
    .bottom-visiting-bottom{
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .visiting-bottom-body{
        width: 100%;
        display: flex;
        flex-direction: column;
       align-items: center;
    }
    .bottom-body-list{
        width: 80%;
        display: grid;
        font-size: 20px;
        grid-template-columns: auto auto;
        text-align:justify;
    }
    .body-list-item{
        padding: 10px;
        display: flex;
        flex-wrap: nowrap;
    }
    button{
        background-color: #040404;
        color: #FFF;
        padding: 5px;
        border-radius: 5px;
        border: none;
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
    .bottom-body-text{
        display: flex;
        justify-content: flex-end;
    }
    .montserrat{
    font-family: "Montserrat", sans-serif;
    font-optical-sizing: auto;
    font-weight: 200;
    font-style: normal;
    }
    
</style>
</html>