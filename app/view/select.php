<?php
$list=[
    ['title'=>'Free Trial Plan','body1'=>'Free ',"body2"=>"","body3"=>"for one-month","select"=>["3 facial slots for trial users","Free for users."]],
    ['title'=>'Small Business Plan','body1'=>'S$50 ',"body2"=>"SGD","body3"=>"per year","select"=>["50 face datasets","S$4.17 SGD charged monthly"]],
    ['title'=>'Medium-Sized Business Plan','body1'=>'S$100 ',"body2"=>"SGD","body3"=>"per year","select"=>["100 face datasets","S$8.33 SGD charged monthly"]],
    ['title'=>'Large Enterprise Plan','body1'=>'S$125 ',"body2"=>"SGD","body3"=>"per year","select"=>["200 face datasets","S$10.42 SGD charged monthly"]],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="static/js/public.js"></script>
    <script src="static/js/jquery-3.2.1.slim.min.js"></script>
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <script src="static/js/jquery.min.js"></script>
    <script src="static/js/popper.min.js"></script>
    <script src="static/js/bootstrap.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
          crossorigin="anonymous">
    <link rel="stylesheet" href="static/css/main.css">
</head>
<body>
<div class="main">
    <div class="card1 shadow-sm bg-white rounded">
        <div class="card-top">
            <h3>Select Subscription Page</h3>
            <p style="font-size: 25px;">Business owner</p>
        </div>
        <i class="back fas fa-chevron-left" onclick="navigatorTo('subscription.php')"></i>
        <div class="card-bottom">
            <div class="card-bottom-card">
                <?php foreach ($list as $key):?>
                <div class="bottom-card-item">
                    <p><?= $key["title"]?></p>
                    <div class="card-item-texts">
                        <h1><?= $key["body1"]?></h1>
                        &nbsp;
                        <H3><?= $key["body2"]?></H3>
                        &nbsp;
                        <p><?= $key["body3"]?></p>
                    </div>
                    <ul>
                        <?php foreach ($key["select"] as $value):?>
                        <li style="margin-top: 10px"><?= $value?></li>
                        <?php endforeach;?>
                    </ul>
                    <button data-toggle="modal" data-target="#exampleModal">Select</button>
                </div>
                <?php endforeach;?>
            </div>
            <div class="logout" id="111">
                <p>Logout</p>
            </div>
        </div>
    </div>
</div>

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
                    <button onclick="loadNav()" data-dismiss="modal" style="background-color: #2F67EF">Submit</button>
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
</body>
<script>
    const loadNav=()=>{
        document.getElementById("loadd").classList.remove("hidden")
        setTimeout(()=>{
            document.getElementById("loadd").classList.add("hidden")
            // $('#exampleModal').modal()
            window.location.replace("subscription.php?isOne=true")
        },2000)
    }
</script>
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
        color: #595F6D;
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
